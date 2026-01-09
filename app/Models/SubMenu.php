<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubMenu extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['menu_id','title', 'description', 'index', 'route_id', 'icon','is_active'];

    public function getIconUrlAttribute()
    {
        if ($this->icon) {
            return Storage::url($this->icon);
        }
        return null;
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function route(){
        return $this->belongsTo(Route::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}
