<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MetaTag extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [
        'route_id', 'description', 'keywords', 'og_title', 'og_description',
        'og_image', 'og_url', 'twitter_card', 'twitter_title', 'twitter_description',
        'twitter_image', 'twitter_site', 'twitter_creator'
    ];

    public function route(){
        return $this->belongsTo(Route::class);
    }

    public function getTwitterImageUrlAttribute()
    {
        if ($this->twitter_image) {
            return Storage::url($this->twitter_image);
        }
        return null;
    }

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return Storage::url($this->og_image);
        }
        return null;
    }
}
