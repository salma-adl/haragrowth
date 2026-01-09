<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description','index', 'route_id', 'type','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function route(){
        return $this->belongsTo(Route::class);
    }

    public function subMenus(){
        return $this->hasMany(SubMenu::class)->where('is_active', true);
    }

    public function relationSubMenus(){
        return $this->hasMany(SubMenu::class);
    }

    public function getTitleWithTypeAttribute()
    {
        return "{$this->title} - {$this->type}";
    }
}
