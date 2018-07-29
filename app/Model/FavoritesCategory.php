<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FavoritesCategory extends Model
{
    protected $table = 'favorites_category';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'type', 'favorites_id', 'favorites_title', 'category', 'order_self', 'is_show'];

    // 与选品库宝贝列表建立一对多的关系
    public function items() {
    	return $this->hasMany('App\Model\FavoritesItem', 'favorites_id', 'favorites_id');
    }
}
