<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Events\ItemSubmitted;
use App\Events\WithdrawalSubmitted;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ItemResource;
use App\Jobs\SendFollowersNewItemNotification;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\Sale;
use App\Models\SubCategory;
use App\Models\Withdrawal;
use App\Models\WithdrawalMethod;
use App\Methods\ImageToWebp;
use App\Methods\Watermark;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Get Producer dashboard statistics.
     */
    public function dashboard(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->is_author) {
            return response()->json([
                'success' => false,
                'message' => 'You must be registered as an author to access this studio.',
            ], 403);
        }

        $itemsCount = Item::where('author_id', $author->id)->count();
        $approvedItemsCount = Item::where('author_id', $author->id)->where('status', Item::STATUS_APPROVED)->count();
        $pendingItemsCount = Item::where('author_id', $author->id)->where('status', Item::STATUS_PENDING)->count();

        $pendingWithdrawals = Withdrawal::where('author_id', $author->id)
            ->where('status', Withdrawal::STATUS_PENDING)
            ->sum('amount');

        return response()->json([
            'success' => true,
            'data'    => [
                'balance'              => (float) $author->balance,
                'total_sales'          => (int) $author->total_sales,
                'total_sales_amount'   => (float) $author->total_sales_amount,
                'total_reviews'        => (int) $author->total_reviews,
                'avg_reviews'          => (float) $author->avg_reviews,
                'total_followers'      => (int) $author->total_followers,
                'pending_withdrawals'  => (float) $pendingWithdrawals,
                'items_count'          => (int) $itemsCount,
                'approved_items_count' => (int) $approvedItemsCount,
                'pending_items_count'  => (int) $pendingItemsCount,
            ],
        ], 200);
    }

    /**
     * List all items uploaded by the author with statuses.
     */
    public function items(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $query = Item::where('author_id', $author->id)->with(['category', 'discount']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('id', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $items->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'slug'          => $item->slug,
                    'status'        => (int) $item->status,
                    'status_name'   => $item->getStatusName(),
                    'regular_price' => (float) $item->regular_price,
                    'total_sales'   => (int) $item->total_sales,
                    'thumbnail_url' => $item->getThumbnailLink(),
                    'created_at'    => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ], 200);
    }

    /**
     * List author sales.
     */
    public function sales(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $sales = Sale::where('author_id', $author->id)
            ->with(['item', 'buyer'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $sales->map(function ($sale) {
                return [
                    'id'            => $sale->id,
                    'price'         => (float) $sale->price,
                    'author_earning'=> (float) $sale->author_earning,
                    'license_type'  => (int) $sale->license_type,
                    'item'          => $sale->item ? [
                        'id'   => $sale->item->id,
                        'name' => $sale->item->name,
                        'slug' => $sale->item->slug,
                    ] : null,
                    'buyer'         => $sale->buyer ? [
                        'name'     => $sale->buyer->getName(),
                        'username' => $sale->buyer->username,
                    ] : null,
                    'created_at'    => $sale->created_at ? $sale->created_at->toISOString() : null,
                ];
            }),
            'meta'    => [
                'current_page' => $sales->currentPage(),
                'last_page'    => $sales->lastPage(),
                'per_page'     => $sales->perPage(),
                'total'        => $sales->total(),
            ],
        ], 200);
    }

    /**
     * Get withdrawal methods and author payout history.
     */
    public function withdrawals(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $methods = WithdrawalMethod::all();
        $withdrawals = Withdrawal::where('author_id', $author->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => [
                'available_methods' => $methods->map(function ($m) {
                    return [
                        'id'      => $m->id,
                        'name'    => $m->name,
                        'minimum' => (float) $m->minimum,
                    ];
                }),
                'current_method'    => $author->withdrawalMethod ? [
                    'id'      => $author->withdrawalMethod->id,
                    'name'    => $author->withdrawalMethod->name,
                    'account' => $author->withdrawal_account,
                ] : null,
                'history'           => $withdrawals->map(function ($w) {
                    return [
                        'id'         => $w->id,
                        'amount'     => (float) $w->amount,
                        'method'     => $w->method,
                        'account'    => $w->account,
                        'status'     => (int) $w->status,
                        'created_at' => $w->created_at ? $w->created_at->toISOString() : null,
                    ];
                }),
            ],
        ], 200);
    }

    /**
     * Submit a withdrawal payout request.
     */
    public function requestWithdrawal(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->hasWithdrawalAccount()) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure your payout withdrawal account in your profile first.',
            ], 400);
        }

        if ($author->balance < $author->withdrawalMethod->minimum) {
            return response()->json([
                'success' => false,
                'message' => 'Your balance is below the minimum withdrawal limit of ' . $author->withdrawalMethod->minimum,
            ], 400);
        }

        $amount = $author->balance;

        $withdrawal = new Withdrawal();
        $withdrawal->author_id = $author->id;
        $withdrawal->amount = $amount;
        $withdrawal->method = $author->withdrawalMethod->name;
        $withdrawal->account = $author->withdrawal_account;
        $withdrawal->status = Withdrawal::STATUS_PENDING;
        $withdrawal->save();

        $author->decrement('balance', $amount);

        try {
            event(new WithdrawalSubmitted($withdrawal));
        } catch (\Throwable $th) {}

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully.',
        ], 200);
    }

    /**
     * Upload a new beat / item with full metadata, category attributes, files, and licensing.
     * Matches the exact business logic and file processing of the Web Producer Studio.
     */
    public function uploadBeat(Request $request)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$author->is_author) {
            return response()->json([
                'success' => false,
                'message' => 'Only registered authors can upload beats.',
            ], 403);
        }

        $itemSettings = settings('item');

        $rules = [
            'name'                   => ['required', 'string', 'max:150'],
            'description'            => ['required', 'string'],
            'category_id'            => ['nullable'],
            'category'               => ['nullable', 'string'],
            'sub_category_id'        => ['nullable'],
            'sub_category'           => ['nullable', 'string'],
            'version'                => ['nullable', 'string', 'max:100'],
            'demo_link'              => ['nullable', 'string', 'max:255'],
            'tags'                   => ['nullable'],
            'regular_price'          => ['nullable', 'numeric'],
            'regular_license_price'  => ['nullable', 'numeric'],
            'extended_price'         => ['nullable', 'numeric'],
            'extended_license_price' => ['nullable', 'numeric'],
            'is_supported'           => ['nullable'],
            'support'                => ['nullable'],
            'support_instructions'   => ['nullable', 'string', 'max:2000'],
            'is_free'                => ['nullable'],
            'free_item'              => ['nullable'],
            'purchasing_status'      => ['nullable'],
            'main_file_source'       => ['nullable'],
            'is_main_file_external'  => ['nullable'],
            'main_file'              => ['nullable'],
            'main_file_link'         => ['nullable', 'url', 'max:500'],
            'message'                => ['nullable', 'string', 'max:3000'],
            'thumbnail'              => ['nullable'],
            'preview_image'          => ['nullable'],
            'preview_video'          => ['nullable'],
            'preview_audio'          => ['nullable'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // 1. Resolve Category
        $category = null;
        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);
        }
        if (!$category && $request->filled('category')) {
            $catVal = $request->category;
            $category = Category::where('slug', $catVal)
                ->orWhere('name', $catVal)
                ->orWhere('id', $catVal)
                ->first();
        }
        if (!$category) {
            $category = Category::first();
        }

        // 2. Resolve SubCategory
        $subCategory = null;
        if ($request->filled('sub_category_id')) {
            $subCategory = SubCategory::find($request->sub_category_id);
        }
        if (!$subCategory && $request->filled('sub_category')) {
            $subVal = $request->sub_category;
            $subCategory = SubCategory::where('slug', $subVal)
                ->orWhere('name', $subVal)
                ->orWhere('id', $subVal)
                ->first();
        }

        // 3. Pricing & Minimums from Item Settings
        $regularPrice = $request->regular_license_price ?? $request->regular_price ?? 29.99;
        $extendedPrice = $request->extended_license_price ?? $request->extended_price ?? ($regularPrice * 2);

        $minPrice = @$itemSettings->minimum_price;
        if ($minPrice && $regularPrice < $minPrice) {
            $regularPrice = $minPrice;
        }
        if ($minPrice && $extendedPrice < $minPrice) {
            $extendedPrice = $minPrice;
        }

        // 4. Tags
        $tags = $request->tags;
        if (is_array($tags)) {
            $tags = implode(', ', $tags);
        }

        // 5. Support Configuration
        $isSupported = (bool) ($request->support ?? $request->is_supported ?? false);
        $supportInstructions = $isSupported ? $request->support_instructions : null;

        // 6. Free Item & Purchasing Status
        $free = Item::NOT_FREE;
        $purchasing = Item::PURCHASING_STATUS_ENABLED;
        $isFreeInput = (bool) ($request->free_item ?? $request->is_free ?? false);
        if (@$itemSettings->free_item_option && $isFreeInput) {
            $free = Item::FREE;
            $purchasingStatusVal = $request->input('purchasing_status');
            $purchasing = ($purchasingStatusVal === '0' || $purchasingStatusVal === 0 || $purchasingStatusVal === false)
                ? Item::PURCHASING_STATUS_DISABLED
                : Item::PURCHASING_STATUS_ENABLED;
        }

        // 7. File Storage Processor (Matches web UploadController & StorageProvider)
        $storageProvider = storageProvider();
        $userHashId = strtolower(hash_encode($author->id));
        $storagePath = "files/items/{$userHashId}/";

        $saveUploadedFile = function ($file, $fallbackFolder) use ($storageProvider, $storagePath, $itemSettings) {
            if (!$file) {
                return null;
            }

            $fileMimeType = $file->getMimeType();
            $originalExt = strtolower($file->getClientOriginalExtension());
            $tempDir = storage_path('app/temp/');
            if (!File::isDirectory($tempDir)) {
                File::makeDirectory($tempDir, 0755, true, true);
            }

            // Move uploaded file to local temp directory so PHP's upload handle doesn't lock/restrict access
            $tempFileName = Str::random(15) . '_' . time() . '.' . ($originalExt ?: 'tmp');
            $file->move($tempDir, $tempFileName);
            $movedPath = $tempDir . $tempFileName;

            // Reconstruct UploadedFile pointing to the moved local file
            $fileToUpload = new \Illuminate\Http\UploadedFile(
                $movedPath,
                $tempFileName,
                $fileMimeType,
                null,
                true
            );

            // If image and convert to webp is enabled (matching web UploadController!)
            if (in_array($fileMimeType, ['image/png', 'image/jpg', 'image/jpeg'])) {
                if (class_exists('\App\Methods\Watermark') && function_exists('isAddonActive') && isAddonActive('watermark') && @settings('watermark')->status) {
                    try {
                        $watermark = new \App\Methods\Watermark();
                        $fileToUpload = $watermark->add($fileToUpload);
                    } catch (\Throwable $e) {}
                }

                if (@$itemSettings->convert_images_webp && class_exists('\App\Methods\ImageToWebp')) {
                    try {
                        $image = new \App\Methods\ImageToWebp();
                        $fileToUpload = $image->convert($fileToUpload);
                    } catch (\Throwable $e) {}
                }
            }

            $uploadedPath = null;

            // 1. Try official storage provider processor (matches web UploadController)
            if ($storageProvider && class_exists($storageProvider->processor)) {
                try {
                    $processor = new $storageProvider->processor;
                    $response = $processor->upload($fileToUpload, $storagePath, $fileToUpload->getMimeType());
                    if (isset($response->type) && $response->type === 'success' && !empty($response->path)) {
                        $uploadedPath = $response->path;
                    }
                } catch (\Throwable $e) {}
            }

            // 2. If processor failed but external storage provider is active, stream directly to the configured disk
            if (!$uploadedPath && $storageProvider && !$storageProvider->isLocal()) {
                try {
                    $diskName = $storageProvider->alias;
                    $ext = $fileToUpload->getClientOriginalExtension() ?: $originalExt;
                    $filename = Str::random(15) . '_' . time() . '.' . strtolower($ext);
                    $targetPath = $storagePath . $filename;
                    $stream = @fopen($fileToUpload->getPathname(), 'r');
                    if ($stream) {
                        $put = Storage::disk($diskName)->put($targetPath, $stream);
                        if (is_resource($stream)) {
                            @fclose($stream);
                        }
                        if ($put) {
                            $uploadedPath = $targetPath;
                        }
                    }
                } catch (\Throwable $e) {}
            }

            // Clean up temporary local files
            try {
                if ($fileToUpload && file_exists($fileToUpload->getPathname())) {
                    @unlink($fileToUpload->getPathname());
                }
                if (file_exists($movedPath)) {
                    @unlink($movedPath);
                }
            } catch (\Throwable $e) {}

            if ($uploadedPath) {
                return $uploadedPath;
            }

            // 3. Fallback to local storage only if storage provider is local
            $stored = $file->store($fallbackFolder, 'public');
            return 'storage/' . $stored;
        };

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $saveUploadedFile($request->file('thumbnail'), 'thumbnails');
        }

        $previewImagePath = null;
        if ($request->hasFile('preview_image')) {
            $previewImagePath = $saveUploadedFile($request->file('preview_image'), 'previews/images');
        }

        $previewVideoPath = null;
        if ($request->hasFile('preview_video')) {
            $previewVideoPath = $saveUploadedFile($request->file('preview_video'), 'previews/video');
        }

        $previewAudioPath = null;
        if ($request->hasFile('preview_audio')) {
            $previewAudioPath = $saveUploadedFile($request->file('preview_audio'), 'previews/audio');
        }

        // Determine Main File (Direct File Upload vs External Link)
        $isMainFileExternal = (int) ($request->main_file_source ?? $request->is_main_file_external ?? 0);
        $mainFilePath = null;

        if ($request->hasFile('main_file')) {
            $mainFilePath = $saveUploadedFile($request->file('main_file'), 'items/main');
            $isMainFileExternal = 0;
        } elseif ($request->filled('main_file_link')) {
            $mainFilePath = $request->main_file_link;
            $isMainFileExternal = 1;
        } elseif ($request->filled('main_file') && is_string($request->main_file) && (Str::startsWith($request->main_file, 'http://') || Str::startsWith($request->main_file, 'https://'))) {
            $mainFilePath = $request->main_file;
            $isMainFileExternal = 1;
        }

        if (!$mainFilePath) {
            return response()->json([
                'success' => false,
                'message' => 'A main file (audio/stems package) or external download link is required.',
                'errors'  => [
                    'main_file' => ['A main file (audio/stems package) or external download link is required.'],
                ],
            ], 422);
        }

        // Determine Preview Type
        $previewType = Item::PREVIEW_FILE_TYPE_AUDIO;
        if ($previewVideoPath) {
            $previewType = Item::PREVIEW_FILE_TYPE_VIDEO;
        } elseif ($previewAudioPath) {
            $previewType = Item::PREVIEW_FILE_TYPE_AUDIO;
        } elseif ($previewImagePath) {
            $previewType = Item::PREVIEW_FILE_TYPE_IMAGE;
        }

        // Review Status & History Title from System Settings
        $status = @$itemSettings->adding_require_review ? Item::STATUS_PENDING : Item::STATUS_APPROVED;
        $itemHistoryTitle = @$itemSettings->adding_require_review ? ItemHistory::TITLE_SUBMISSION : ItemHistory::TITLE_TRUST_SUBMISSION;

        // Build and Save Item
        $item = new Item();
        $item->author_id = $author->id;
        $item->name = $request->name;
        $item->slug = SlugService::createSlug(Item::class, 'slug', $request->name, ['unique' => false]);
        $item->description = $request->description;
        $item->category_id = $category ? $category->id : null;
        $item->sub_category_id = $subCategory ? $subCategory->id : null;
        $item->version = $request->version ?? '1.0';
        $item->demo_link = $request->demo_link;
        $item->tags = $tags ?: 'beat';
        $item->regular_price = (float) $regularPrice;
        $item->extended_price = (float) $extendedPrice;
        $item->thumbnail = $thumbnailPath;
        $item->preview_type = $previewType;
        $item->preview_image = $previewImagePath;
        $item->preview_video = $previewVideoPath;
        $item->preview_audio = $previewAudioPath;
        $item->main_file = $mainFilePath;
        $item->is_main_file_external = $isMainFileExternal;
        $item->is_supported = $isSupported ? 1 : 0;
        $item->support_instructions = $supportInstructions;
        $item->purchasing_status = $purchasing;
        $item->status = $status;
        $item->is_free = $free;
        $item->price_updated_at = Carbon::now();

        try {
            $item->save();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save beat: ' . $e->getMessage(),
            ], 500);
        }

        // Create ItemHistory record
        if (class_exists('\App\Models\ItemHistory')) {
            try {
                $history = new ItemHistory();
                $history->item_id = $item->id;
                $history->author_id = $author->id;
                $history->title = $itemHistoryTitle;
                $history->body = $request->message ?? 'Submitted for review from Mobile Studio';
                $history->save();
            } catch (\Throwable $th) {}
        }

        // Dispatch ItemSubmitted event (matches web)
        try {
            event(new ItemSubmitted($item));
        } catch (\Throwable $th) {}

        // Notify followers if auto-approved
        if (!@$itemSettings->adding_require_review && class_exists('\App\Jobs\SendFollowersNewItemNotification')) {
            try {
                dispatch(new SendFollowersNewItemNotification($item));
            } catch (\Throwable $th) {}
        }

        $successMessage = @$itemSettings->adding_require_review
            ? translate('Your item has been submitted successfully, we will review it as soon as possible.')
            : translate('Your item has been added successfully.');

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'item'    => [
                'id'            => $item->id,
                'name'          => $item->name,
                'slug'          => $item->slug,
                'status'        => $item->status,
                'status_name'   => $item->getStatusName(),
                'category_id'   => $item->category_id,
                'category'      => $category ? $category->name : null,
                'regular_price' => (float) $item->regular_price,
                'extended_price'=> (float) $item->extended_price,
                'thumbnail_url' => $item->getThumbnailLink(),
                'is_free'       => (bool) $item->is_free,
                'is_supported'  => (bool) $item->is_supported,
                'created_at'    => $item->created_at ? $item->created_at->toIso8601String() : null,
            ],
        ], 201);
    }

    /**
     * Delete an item.
     */
    public function deleteBeat(Request $request, $id)
    {
        $author = $request->user();
        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }
        $item = Item::where('author_id', $author->id)->where('id', $id)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        if ($item->total_sales > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete an item that already has purchase sales.',
            ], 400);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Beat deleted successfully.',
        ], 200);
    }
}

