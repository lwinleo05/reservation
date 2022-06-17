<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable=['name', 'image', 'description'];

    public function menus(){

        return $this->belongsToMany(Menu::class, 'category_menu');      //category_menu=foreign key

    }
}
