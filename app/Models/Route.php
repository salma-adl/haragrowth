<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'is_active'];

    public function menu(){
        return $this->hasMany(Menu::class);
    }

    public function subMenu(){
        return $this->hasMany(SubMenu::class);
    }

    public function metaTag()
    {
        return $this->hasOne(MetaTag::class); // Route memiliki satu MetaTag
    }
}
