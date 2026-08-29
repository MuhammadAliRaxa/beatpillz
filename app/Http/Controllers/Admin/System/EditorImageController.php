<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Models\EditorImage;
use Exception;

class EditorImageController extends Controller
{
    public function index()
    {
        $editorImages = EditorImage::query();

        if (request()->filled('search')) {
            $searchTerm = '%' . request('search') . '%';
            $editorImages->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('filename', 'like', $searchTerm)
                    ->orWhere('path', 'like', $searchTerm);
            });
        }

        $editorImages = $editorImages->orderbyDesc('id')->paginate(20);
        $editorImages->appends(request()->only(['search']));

        return view('admin.system.editor-images', [
            'editorImages' => $editorImages,
        ]);
    }

    public function destroy(EditorImage $editorImage)
    {
        try {
            $editorImage->deleteImage();

            toastr()->success(translate('Deleted Successfully'));
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }
}