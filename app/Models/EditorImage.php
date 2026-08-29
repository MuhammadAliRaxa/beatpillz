<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'filename',
        'path',
    ];

    public function getLink()
    {
        return getLinkFromStorageProvider($this->path);
    }

    public function deleteImage()
    {
        $storageProvider = storageProvider();
        $processor = new $storageProvider->processor;
        $processor->delete($this->path);

        $this->delete();
    }
}