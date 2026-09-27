<?php

namespace App\Methods;

use App\Traits\InteractWithFileStorage;
use Exception;
use Intervention\Image\Facades\Image;

class Watermark
{
    use InteractWithFileStorage;

    function add($image)
    {
        $originalImage = $image;

        try {
            $watermarkSettings = settings('watermark');

            $watermarkPath = public_path(@$watermarkSettings->image ?? '');
            $position = @$watermarkSettings->position ?? 'bottom-right';
            $width = @$watermarkSettings->width ?? 200;
            $height = @$watermarkSettings->height ?? 70;
            $rotate = @$watermarkSettings->rotate ?? 0;
            $opacity = @$watermarkSettings->opacity ?? 100;

            if (!file_exists($watermarkPath)) {
                throw new Exception(translate('Watermark image does not exist'));
            }

            if (is_string($image)) {
                $image = Image::make(file_get_contents($image));
            } else {
                $image = Image::make($image);
            }

            $watermark = Image::make(file_get_contents($watermarkPath));

            $watermark->resize($width, $height);
            $watermark->rotate($rotate);
            $watermark->opacity($opacity);

            if ($position == "fill") {
                $imageWidth = $image->width();
                $imageHeight = $image->height();
                $watermarkWidth = $watermark->width();
                $watermarkHeight = $watermark->height();

                for ($y = 0; $y < $imageHeight; $y += $watermarkHeight) {
                    for ($x = 0; $x < $imageWidth; $x += $watermarkWidth) {
                        $image->insert($watermark, 'top-left', $x, $y);
                    }
                }
            } else {
                $image->insert($watermark, $position, 5, 5);
            }

            $directory = storage_path("app/temp/");
            makeDirectory($directory);

            $filename = $this->generateUniqueFileName($originalImage);
            $fileDestination = $directory . $filename;
            $image->save($fileDestination);

            return $this->pathToUploadedFile($fileDestination);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
