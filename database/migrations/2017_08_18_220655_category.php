<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Category extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->unique('id');
            $table->index('id');
            $table->string('name', 30);    // 栏目名称
            $table->char('adzone_id', 20); // 淘宝的推广位id

            $table->integer('order_self')->default('0'); // 分类排列顺序
            $table->char('is_show',3)->default('否');    // 分类是否显示
            $table->char('form', 1)->default('0');       // 栏目所属的模型，0表示未分配，1表示选品库列表，2表示定向招商的活动列表，3表示精选优质商品清单（Excel），4表示官方精选热推清单（最高佣金50%）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
