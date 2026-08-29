<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EditorImage;
use App\Traits\InteractWithFileStorage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    use InteractWithFileStorage;

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return response()->json(['error' => $error], 400);
            }
        }

        $image = $request->file('image');

        if (!in_array($image->getClientMimeType(), ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'])) {
            return response()->json([
                'error' => translate('Invalid file type. Only image files are allowed (JPEG, JPG, PNG, GIF).'),
            ], 400);
        }

        try {
            $storageProvider = storageProvider();
            if (!$storageProvider) {
                return $this->error(translate('Unavailable storage provider'));
            }

            $imageExtension = $image->getClientOriginalExtension();
            $imageMimeType = ($this->fileMimeType($imageExtension)) ? $this->fileMimeType($imageExtension) : $image->getMimeType();

            $processor = new $storageProvider->processor;
            $response = $processor->upload($image, 'images/editor/', $imageMimeType);

            $editorImage = new EditorImage();
            $editorImage->name = $image->getClientOriginalName();
            $editorImage->filename = $response->filename;
            $editorImage->path = $response->path;
            $editorImage->save();

            return response()->json([
                'uploaded' => true,
                'default' => $editorImage->getLink(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}