<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['category_id', 'title', 'slug', 'attachment', 'content', 'is_published'];

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
    protected $casts = [
        'is_published' => 'boolean',
        'tags' => 'array',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // public function blogComments(){
    //     return $this->hasMany(BlogComment::class)->orderBy('created_at', 'desc');
    // }
}
