<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Traits\InteractWithFileStorage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class IdriveE2Controller extends Controller
{
    use InteractWithFileStorage;

    public $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('idrive');
    }

    public static function setCredentials($credentials)
    {
        setEnv('IDRIVEE2_ACCESS_KEY_ID', $credentials->access_key_id);
        setEnv('IDRIVEE2_SECRET_ACCESS_KEY', $credentials->secret_access_key);
        setEnv('IDRIVEE2_DEFAULT_REGION', $credentials->default_region);
        setEnv('IDRIVEE2_BUCKET', $credentials->bucket);
        setEnv('IDRIVEE2_URL', $credentials->url);
        setEnv('IDRIVEE2_ENDPOINT', $credentials->endpoint);
    }

    public function upload($file, $path)
    {
        try {
            $filename = $this->generateUniqueFileName($file);
            $path = $path . $filename;
            $upload = $this->disk->put($path, fopen($file, 'r+'));
            if ($upload) {
                return $this->success([
                    "filename" => $filename,
                    "path" => $path,
                ]);
            } else {
                return $this->error(translate('The file upload failed due to an error in the storage provider'));
            }
        } catch (Exception $e) {
            return $this->error(translate('The file upload failed due to an error in the storage provider'));
        }
    }

    public function download($path, $filename)
    {
        try {
            if ($this->disk->has($path)) {
                $downloadLink = $this->disk->temporaryUrl($path, Carbon::now()->addHour(), [
                    'ResponseContentDisposition' => 'attachment; filename="' . $filename . '"',
                ]);
                return redirect($downloadLink);
            } else {
                return $this->error(lang('The requested file are not exists', 'file system'));
            }
        } catch (Exception $e) {
            return $this->error(lang('The download failed due to an error on the storage provider', 'file system'));
        }
    }

    public function delete($path)
    {
        if ($this->disk->has($path)) {
            $this->disk->delete($path);
        }
        return true;
    }

}
