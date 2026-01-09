<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['user_id', 'phone', 'gender', 'birthdate', 'address', 'bio', 'str_number', 'name', 'attachment', 'sipp_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function services()
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
