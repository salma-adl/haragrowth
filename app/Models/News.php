<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'attachment', 'link'];

    protected static function booted()
    {
        static::saving(function ($blog) {
            if ($blog->isDirty('attachment') && $blog->getOriginal('attachment') != $blog->attachment) {
                $oldFile = $blog->getOriginal('attachment');
                if ($oldFile && Storage::exists($oldFile)) {
                    Storage::delete($oldFile);
                }
            }
        });

        static::deleted(function ($blog) {
            if ($blog->attachment && Storage::exists($blog->attachment)) {
                Storage::delete($blog->attachment);
            }
        });
    }

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return Storage::url($this->attachment);
        }
        return null;
    }
}
