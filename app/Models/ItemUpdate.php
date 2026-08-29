<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ItemUpdate extends Model
{
    use HasFactory;

    public function scopeWhereReviewerCategories($query, $reviewer)
    {
        $categoryIds = $reviewer->categories->pluck('id')->toArray();
        return $query->whereIn('category_id', $categoryIds);
    }

    public function isFree()
    {
        return $this->is_free == Item::FREE;
    }

    public function isPurchasingEnabled()
    {
        return $this->purchasing_status == Item::PURCHASING_STATUS_ENABLED;
    }

    public function isMainFileExternal()
    {
        return $this->is_main_file_external == Item::MAIN_FILE_EXTERNAL;
    }

    public function isPreviewFileTypeImage()
    {
        return $this->preview_type == Item::PREVIEW_FILE_TYPE_IMAGE;
    }

    public function isPreviewFileTypeVideo()
    {
        return $this->preview_type == Item::PREVIEW_FILE_TYPE_VIDEO;
    }

    public function isPreviewFileTypeAudio()
    {
        return $this->preview_type == Item::PREVIEW_FILE_TYPE_AUDIO;
    }

    public function scopeSupported($query)
    {
        $query->where('is_supported', Item::SUPPORTED);
    }

    public function isSupported()
    {
        return $this->is_supported == Item::SUPPORTED;
    }

    protected $fillable = [
        'author_id',
        'item_id',
        'name',
        'description',
        'category_id',
        'sub_category_id',
        'options',
        'version',
        'demo_link',
        'tags',
        'thumbnail',
        'preview_type',
        'preview_image',
        'preview_video',
        'preview_audio',
        'main_file',
        'is_main_file_external',
        'screenshots',
        'regular_price',
        'extended_price',
        'is_supported',
        'support_instructions',
        'purchasing_status',
        'is_free',
    ];

    protected $casts = [
        'options' => 'array',
        'screenshots' => 'object',
    ];

    protected $with = [
        'category',
        'subCategory',
    ];

    public function getRegularPrice()
    {
        if ($this->regular_price) {
            return ($this->regular_price + $this->category->regular_buyer_fee);
        }
        return null;
    }

    public function getExtendedPrice()
    {
        if ($this->extended_price) {
            return ($this->extended_price + $this->category->extended_buyer_fee);
        }
        return null;
    }

    public function getThumbnailLink()
    {
        if ($this->thumbnail) {
            return getLinkFromStorageProvider($this->thumbnail);
        }
        return $this->item->getThumbnailLink();
    }

    public function getPreviewImageLink()
    {
        if ($this->preview_image) {
            return getLinkFromStorageProvider($this->preview_image);
        }
        return $this->item->getPreviewImageLink();
    }

    public function getPreviewVideoLink()
    {
        if ($this->preview_video) {
            return getLinkFromStorageProvider($this->preview_video);
        }
        return $this->item->getPreviewVideoLink();
    }

    public function getPreviewAudioLink()
    {
        if ($this->preview_audio) {
            return getLinkFromStorageProvider($this->preview_audio);
        }
        return $this->item->getPreviewAudioLink();
    }

    public function getPreviewLink()
    {
        if ($this->isPreviewFileTypeVideo()) {
            return $this->getPreviewVideoLink();
        } elseif ($this->isPreviewFileTypeAudio()) {
            return $this->getPreviewAudioLink();
        } else {
            return $this->getPreviewImageLink();
        }
    }

    public function getScreenshotLinks()
    {
        $screenshots = [];
        foreach ($this->screenshots as $screenshot) {
            $screenshots[] = getLinkFromStorageProvider($screenshot);
        }
        return (object) $screenshots;
    }

    public function getImageLink()
    {
        if ($this->preview_image) {
            return $this->getPreviewImageLink();
        }
        return $this->getThumbnailLink();
    }

    public function getTags()
    {
        $tags = explode(',', $this->tags);
        return (object) $tags;
    }

    public function download()
    {
        $storageProvider = storageProvider();
        $processor = new $storageProvider->processor;

        $siteName = Str::slug(@settings('general')->site_name);
        $filename = $siteName . '-updated-' . time() . '-' . Str::slug($this->name) . '.' . File::extension($this->main_file);

        return $processor->download($this->main_file, $filename);
    }

    public function deleteFiles()
    {
        $storageProvider = storageProvider();
        $processor = new $storageProvider->processor;

        if ($this->thumbnail) {
            $processor->delete($this->thumbnail);
        }

        if ($this->preview_image) {
            $processor->delete($this->preview_image);
        }

        if ($this->preview_video) {
            $processor->delete($this->preview_video);
        }

        if ($this->preview_audio) {
            $processor->delete($this->preview_audio);
        }

        if ($this->main_file && !$this->isMainFileExternal()) {
            $processor->delete($this->main_file);
        }

        if ($this->screenshots) {
            foreach ($this->screenshots as $screenshot) {
                $processor->delete($screenshot);
            }
        }
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
