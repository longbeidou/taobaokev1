<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'adzone_id', 'category_id', 'order_self', 'is_show'];
}
