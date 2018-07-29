<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FavoritesItem extends Model
{
    protected $table = 'favorites_item';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
}
