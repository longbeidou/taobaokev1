{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('seohead')
    <title>{{$pageInfo['title'] or ''}} - {{config('website.name')}}</title>
    <meta name="description" value="{{$pageInfo['description'] or config('website.name')}}" />
@endsection
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
    <!-- 顶部的导航 -->
    @include('home.layouts.GoodListTop')
      
    <!-- 导航 -->
    @include('home.layouts.GoodsListNav')

    <div class="container">
      <?php 
          // 商品是否推荐的条件
          if (!empty($_GET['isrecommend']) && $_GET['isrecommend'] === 'yes') {
            $isred = 'yes';
          } else {
            $isred = '';
          }

          if (!empty($_GET['cate'])) {
            $param = ['categoryId'  => $categoryInfo['id'],
                      'isrecommend' => $isred,
                      'cate'        => $_GET['cate']
                     ];
          } else {
            $param = ['categoryId' => $categoryInfo['id'],
                      'isrecommend' => $isred
                     ];
          }
      ?>
      @if(!empty($_GET['isrecommend']) && $_GET['isrecommend'] == 'yes')
      <!-- 推荐栏目 -->
      <div class="row fenlei bgwhiter font14" >
        <ul class="list-inline marginbottom0">
          <li><strong>每日推荐商品的栏目</strong></li>
          @foreach($categoryAllInfo as $category)
            <li><a href="{{ route('homeGoodsListCategory', ['categoryId'=>$category['id'], 'isrecommend'=>'yes']) }}" @if($categoryInfo['id'] == $category['id']) style="color:#ed2a7a;" @endif >{{ $category['name'] }}</a></li>
          @endforeach
        </ul>
      </div>
      @endif

      <?php
          if(!empty($_GET['isrecommend']) && $_GET['isrecommend'] == 'yes') {
            $style = 'margin-top:-20px;';
          } else {
            $style = '';
          }
      ?>

      <!-- 分类 -->
      <div  class="row fenlei bgwhiter font14" style="{{$style}}">
        <ul class="list-inline marginbottom0">
          <li><a  href="{{route('homeGoodsListCategory', ['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id']])}}"><strong>全部分类</strong></a></li>
          @foreach($couponInfo['category'] as $cate)
            <li><a href="{{route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'cate'=>$cate->self_where])}}" @if(!empty($_GET['cate']) && $_GET['cate'] == $cate->self_where) style="color:#ed2a7a;" @endif>{{$cate->category_name}}</a></li>
          @endforeach
        </ul>
      </div>
      <!-- 排序条件 -->
      <div class="row tiaojian bgwhiter font12">
        <ul class="list-inline marginbottom0">
          <li><a href="{{route('homeGoodsListCategory',  $param)}}"><strong>综合排序</strong></a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'new')   style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'new', ]); ?>">最新</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'rdown') style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'rdown']); ?>">优惠幅度降序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'rup')   style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'rup'  ]); ?>">优惠幅度升序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'pdown') style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'pdown']); ?>">现价降序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'pup')   style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'pup'  ]); ?>">现价升序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'sdown') style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'sdown']); ?>">销量降序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'sup')   style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'sup'  ]); ?>">销量升序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'time')  style="color:#ed2a7a;" @endif href="<?php $cate=''; if(!empty($_GET['cate'])) $cate=$_GET['cate']; echo route('homeGoodsListCategory',['categoryId'=>$categoryInfo['id'], 'isrecommend'=>$isred, 'cate'=>$cate, 'order'=>'time' ]); ?>">剩余时间</a></li>
        </ul>
      </div>

      <!-- 商品列表 -->
      <div class="row goodslist bgwhiter8">
        @if( !count($couponInfo['goods']) )
        <div class="row">
          <div class="col-xs-12 text-center ">
            <div class="col-xs-12 bgwhiter ">
              <h3>暂时没有发布相关商品信息</h3>
            </div>
          </div>          
        </div>
        @endif

        <div style="width:100%;height:20px; background-color:#fff;"></div>

        @foreach ($couponInfo['goods'] as $goods)
        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="{{ route('urlCouponInfo',['id'=>$goods->id]) }}" target="_blank"><img src="{{ $goods->image }}" width="100%" id="picBox" class="picBox" ></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="{{ route('urlCouponInfo',['id'=>$goods->id]) }}" target="_blank" class="gfontb">{{ $goods->goods_name }}</a>
          </div>
          <div class="col-md-6 col-xs-12 gquan">券后:<span class="gfontr">{{ $goods->price_now }}</span></div>
          <div class="col-md-6 col-xs-12 gquan">原价:{{ $goods->price }}</div>
          <!-- <div class="col-md-6 col-xs-12 text-right hidden-xs gxiaoliang">券后<span class="gfontr"> $goods->price_now </span></div> -->
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-3 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠：</strong></div>
            <div class="col-md-9 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">{{ $goods->yhq_info }}</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="{{ route('urlCouponInfo',['id'=>$goods->id]) }}"     target="_blank" class="gfontb">领取<span class="hidden-lg hidden-md "><br></span>优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="{{ route('urlCouponInfo',['id'=>$goods->id]) }}" target="_blank" class="gfontb">商品<span class="hidden-lg hidden-md "><br></span>详情</a>
          </div>
        </div>
        @endforeach

        <div style="clear:both;"></div>
        <div style="width:100%;height:10px; background-color:#fff;"></div>

      </div><!-- 商品列表 -->

      <script type="text/javascript" src="/home/style/js/jquery-3.2.1.min.js"></script>
      <script type="text/javascript">          
          var w=$('#picBox').width();
          $('.picBox').attr('height',w*1);
          $('.picBox').attr('width',w);
      </script>

      <!-- 分页 -->
      <div class="row text-center">
          <nav aria-label="Page navigation">
              {!! $couponInfo['goods']->appends($oldRequest)->render() !!}
          </nav>
      </div>
    </div><!-- container框 -->

    <!-- 底部 -->
    @include('home.layouts.GoodListFooter')
@endsection