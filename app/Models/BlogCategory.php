<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    //Какие поля методу fill() мы доверяем обновлять
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description'
    ];
}
