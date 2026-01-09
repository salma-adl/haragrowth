<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key','alt', 'type', 'is_dark_mode', 'attachment', 'dark_attachment'];
    
    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return Storage::url($this->attachment);
        }
        return null;
    }

    public function getDarkAttachmentUrlAttribute()
    {
        if ($this->dark_attachment) {
            return Storage::url($this->dark_attachment);
        }
        return null;
    }

    protected $casts = [
        'is_dark_mode' => 'boolean',
    ];
}
