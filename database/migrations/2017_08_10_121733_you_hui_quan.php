<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class YouHuiQuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('you_hui_quan', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->unique('id');
            $table->index('id');
            $table->char('goods_id',15);      //商品id
            $table->string('goods_name',120); //商品名称
            $table->string('image');          //商品主图
            $table->string('info_link');      //商品详情页链接地址
            $table->string('cate');           //商品一级类目
            $table->string('taobaoke_link');  //淘宝客链接
            $table->decimal('price',7,2);     //商品价格(单位：元)
            $table->integer('sales');     //商品月销量
            $table->decimal('rate',5,2);  //收入比率(%)
            $table->decimal('money',5,2); //佣金
            $table->string('wangwang');   //卖家旺旺
            $table->string('ww_id');      //卖家id
            $table->string('shop_name')->nullable();//店铺名称
            $table->char('flat',6);       //平台类型
            $table->string('yhq_id');     //优惠券id
            $table->integer('yhq_total'); //优惠券总量
            $table->integer('yhq_last');  //优惠券剩余量
            $table->string('yhq_info');   //优惠券面额
            $table->date('yhq_begin');    //优惠券开始时间
            $table->date('yhq_end');      //优惠券结束时间
            $table->string('yhq_link');   //优惠券链接
            $table->string('yhq_goods');  //商品优惠券推广链接

            $table->decimal('price_now',7,2)->nullable();  //商品的现价(单位：元)
            $table->decimal('rate_sales',5,2)->nullable(); //商品的优惠幅度（单位：%）
            $table->char('is_recommend',3)->default('否'); //商品是否推荐
            $table->char('tao_kou_ling',20)->nullable();   //淘口令
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
