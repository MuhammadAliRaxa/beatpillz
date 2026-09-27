<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\StorageProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function index()
    {
        $storageProviders = StorageProvider::all();
        return view('admin.settings.storage', ['storageProviders' => $storageProviders]);
    }

    public function update(Request $request)
    {
        $storageProvider = StorageProvider::where('alias', $request->storage_provider)->firstOrFail();

        if (!$storageProvider->isLocal()) {
            $credentials = $request->credentials[$storageProvider->alias];
            foreach ($credentials as $key => $value) {
                if (!array_key_exists($key, (array) $storageProvider->credentials)) {
                    toastr()->error(translate('Mismatch credentials'));
                    return back();
                }
            }
        }

        if (!$storageProvider->isLocal()) {
            $storageProvider->credentials = $credentials;
            $storageProvider->update();
            $storageProvider->processor::setCredentials($storageProvider->credentials);
        }

        setEnv('FILESYSTEM_DRIVER', $storageProvider->alias);
        toastr()->success(translate('Updated Successfully'));
        return back();

    }

    public function storageTest(Request $request)
    {
        $defaultStorage = config('filesystems.default');
        if ($defaultStorage != "local") {
            try {
                $disk = Storage::disk($defaultStorage);
                $upload = $disk->put('test.txt', 'public');
                if (!$upload) {
                    toastr()->error(translate('Connection Failed'));
                    return back();
                }
                $disk->delete('test.txt');
                toastr()->success(translate('Connected successfully'));
                return back();
            } catch (\Exception $e) {
                toastr()->error(translate('Connection Failed'));
                return back();
            }
        }
    }
}