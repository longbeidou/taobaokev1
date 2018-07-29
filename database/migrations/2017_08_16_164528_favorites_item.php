<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FavoritesItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites_item', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->unique('id');
            $table->index('id');
            $table->char('num_iid', 15);       // 商品id
            $table->string('title');          // 商品标题
            $table->string('pict_url');       // 商品主图
            $table->string('small_images');   // 小商品列表
            $table->decimal('reserve_price', 8, 2);  // 商品一口价格
            $table->decimal('zk_final_price', 8, 2); // 商品折扣价格
            $table->char('user_type', 1);     // 卖家类型，1表示集市，2表示商城=>new 0:taobao  1:tmall
            $table->char('provcity', 24);     // 宝贝所在地
            $table->string('item_url');       // 商品所在地
            $table->string('click_url', 400);      // 淘客地址
            $table->string('nick', 60);       // 卖家昵称
            $table->integer('seller_id');     // 卖家id
            $table->integer('volume');        // 30天销量
            $table->decimal('tk_rate', 5, 2); // 收入比例
            $table->decimal('zk_final_price_wap', 8, 2); // 无线折扣价，即宝贝在无线上的实际售价
            $table->string('shop_title');           // 店铺名
            $table->string('event_start_time', 20); // 招商活动开始时间；如果该宝贝取自普通选品组，则取值为1970-01-01 00:00:00；
            $table->string('event_end_time', 20);   // 招行活动的结束时间；如果该宝贝取自普通的选品组，则取值为1970-01-01 00:00:00
            $table->char('type', 1);                // 宝贝类型：1 普通商品； 2 鹊桥高佣金商品；3 定向招商商品；4 营销计划商品;
            $table->char('status', 1);              // 宝贝状态，0失效，1有效；注：失效可能是宝贝已经下线或者是被处罚不能在进行推广
            $table->integer('category');            // 后台一级类目
            $table->string('coupon_click_url');     // 商品优惠券推广链接
            $table->char('coupon_end_time', 10);    // 优惠券结束时间
            $table->char('coupon_info', 30);        // 优惠券面额
            $table->char('coupon_start_time', 10);  // 优惠券开始时间
            $table->integer('coupon_total_count');  // 优惠券总量
            $table->integer('coupon_remain_count'); // 优惠券剩余量


            $table->decimal('money', 6, 2);                // 宝贝推广的佣金,根据tk_rate * zk_final_price_wap计算而得
            $table->integer('favorites_id');               // 选品库列表的id
            $table->char('is_recommend',3)->default('否'); // 商品是否推荐
            $table->char('tao_kou_ling',20)->nullable();   // 淘口令
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
