<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FavoritesCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites_category', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->unique('id');
            $table->index('id');
            $table->string('name', 30)->nullable();  // 分类的自定义名称
            $table->char('type','1');                // 选品库的类型，1为普通类型，2为高佣金类型
            $table->integer('favorites_id');         // 选品库id
            $table->string('favorites_title', 60);   //选品组名称
            $table->char('category', 2)->nullable(); // 选聘组所属的栏目id，

            $table->integer('order_self')->default('0'); // 分类排列顺序
            $table->char('is_show',3)->default('否');    // 分类是否显示
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
