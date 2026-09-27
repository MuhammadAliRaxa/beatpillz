<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Methods\ImageToWebp;
use App\Methods\Watermark;
use App\Models\Category;
use App\Models\StorageProvider;
use App\Models\UploadedFile;
use App\Traits\InteractWithFileStorage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Vironeer\ChunkUpload\Handler\HandlerFactory;
use Vironeer\ChunkUpload\Receiver\FileReceiver;

class UploadController extends Controller
{
    use InteractWithFileStorage;

    public function upload(Request $request, $category_id)
    {
        if (demoMode()) {
            return $this->error('Some features are disabled in the demo version');
        }

        $originalFileName = $request->file('file')->getClientOriginalName();

        if (strip_tags($originalFileName) !== $originalFileName) {
            return $this->error(translate('The file name contain blocked patterns'));
        }

        if (preg_match('/\{\{[^}]*\}\}|{!![^}]*!!}|<\?php|\{\}|\{[^}]*\}/', $originalFileName)) {
            return $this->error(translate('The file name contain blocked patterns'));
        }

        $author = authUser();

        $category = Category::where('id', hash_decode($category_id))->firstOrFail();

        $uploadedFileExists = UploadedFile::where('author_id', $author->id)
            ->where('category_id', $category->id)
            ->where('name', $originalFileName)->first();
        if ($uploadedFileExists) {
            return $this->error(translate('You cannot attach the same file twice'));
        }

        $mainFileTypes = explode(',', $category->main_file_types);
        $extensions = array_merge($mainFileTypes, ['jpeg', 'jpg', 'png']);
        if (!in_array($request->file('file')->getClientOriginalExtension(), $extensions)) {
            return $this->error(translate('You cannot upload files of this type'));
        }

        try {
            $storageProvider = storageProvider();
            if (!$storageProvider) {
                return $this->error(translate('Unavailable storage provider'));
            }

            $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                return $this->error(translate('Failed to upload (:filename)', ['filename' => $originalFileName]));
            }

            $save = $receiver->receive();
            if (!$save->isFinished()) {
                return $this->error(translate('Failed to upload (:filename)', ['filename' => $originalFileName]));
            }

            $file = $save->getFile();
            $fileExtension = $file->getClientOriginalExtension();
            $fileMimeType = ($this->fileMimeType($fileExtension)) ? $this->fileMimeType($fileExtension) : $file->getMimeType();
            $fileSize = $file->getSize();

            if ($fileSize == 0) {
                return $this->error(translate('Empty files cannot be uploaded'));
            }

            $itemSettings = settings('item');

            $maxFileSize = @$itemSettings->max_file_size;
            if ($fileSize > $maxFileSize) {
                return $this->error(translate('File is too big, Max file size :max_file_size', ['max_file_size' => formatBytes($maxFileSize)]));
            }

            if (in_array($fileMimeType, ['image/png', 'image/jpg', 'image/jpeg'])) {
                if (isAddonActive('watermark') && @settings('watermark')->status) {
                    $watermark = new Watermark();
                    $file = $watermark->add($file);
                }

                if (@$itemSettings->convert_images_webp) {
                    $image = new ImageToWebp();
                    $file = $image->convert($file);
                }
            }

            $userHashId = strtolower(hash_encode($author->id));

            $path = "files/items/{$userHashId}/";
            $processor = new $storageProvider->processor;
            $response = $processor->upload($file, $path, $fileMimeType);

            if ($response->type == "error") {
                return $this->error($response->message);
            }

            if ($response->type != "success") {
                return $this->error(translate('Failed to upload (:filename)', ['filename' => $originalFileName]));
            }

            $uploadedFile = UploadedFile::create([
                'author_id' => $author->id,
                'category_id' => $category->id,
                'name' => $originalFileName,
                'mime_type' => $fileMimeType,
                'extension' => $fileExtension,
                'size' => $fileSize,
                'path' => $response->path,
                'expiry_at' => Carbon::now()->addHours(@$itemSettings->file_duration),
            ]);

            if (!$uploadedFile) {
                return $this->error(translate('Failed to upload (:filename)', ['filename' => $originalFileName]));
            }

            return $this->success([
                'id' => hash_encode($uploadedFile->id),
                'name' => $uploadedFile->name,
                'size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->mime_type,
                'extension' => $uploadedFile->extension,
                'link' => $uploadedFile->getFileLink(),
                'time' => $uploadedFile->created_at->diffforhumans(),
                'delete_link' => route('workspace.items.files.delete', [hash_encode($category->id), hash_encode($uploadedFile->id)]),
            ]);

        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}