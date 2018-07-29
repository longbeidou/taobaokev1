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
      ?>
      @if(!empty($_GET['isrecommend']) && $_GET['isrecommend'] == 'yes')
      <!-- 推荐栏目 -->
      <div class="row fenlei bgwhiter font14">
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
      <div class="row fenlei bgwhiter font14"  style="{{$style}}">
        <ul class="list-inline marginbottom0">
          <li><a  href="{{route('homeGoodsListCategory', ['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id']])}}"><strong>全部分类</strong></a></li>
          @foreach($favorites as $f)
            <li><a href="{{route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$f['favorites_id']])}}" @if(!empty($_GET['fid']) && $_GET['fid'] == $f['favorites_id']) style="color:#ed2a7a;" @endif>{{$f['name']}}</a></li>
          @endforeach
        </ul>
      </div>
      <!-- 排序条件 -->
      <div class="row tiaojian bgwhiter font12">
        <ul class="list-inline marginbottom0">
          <?php 
              if (!empty($_GET['fid'])) {
                $fidnow = $_GET['fid'];
              } else {
                $fidnow = '';
              }
           ?>
          <li><a href="{{route('homeGoodsListCategory',  ['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$fidnow])}}"><strong>综合排序</strong></a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'zkFinalPriceWapDown') style="color:#ed2a7a;" @endif href="<?php $fid=''; if(!empty($_GET['fid'])) $fid=$_GET['fid']; echo route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$fid, 'order'=>'zkFinalPriceWapDown']); ?>">现价降序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'zkFinalPriceWapUp')   style="color:#ed2a7a;" @endif href="<?php $fid=''; if(!empty($_GET['fid'])) $fid=$_GET['fid']; echo route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$fid, 'order'=>'zkFinalPriceWapUp'  ]); ?>">现价升序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'volumeDown')          style="color:#ed2a7a;" @endif href="<?php $fid=''; if(!empty($_GET['fid'])) $fid=$_GET['fid']; echo route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$fid, 'order'=>'volumeDown']);          ?>">销量降序</a></li>
          <li><a @if(!empty($_GET['order']) && $_GET['order'] == 'volumeUp')            style="color:#ed2a7a;" @endif href="<?php $fid=''; if(!empty($_GET['fid'])) $fid=$_GET['fid']; echo route('homeGoodsListCategory',['isrecommend'=>$isred, 'categoryId'=>$categoryInfo['id'], 'fid'=>$fid, 'order'=>'volumeUp'  ]);          ?>">销量升序</a></li>
        </ul>
      </div>

      <!-- 商品列表 -->
      <div class="row goodslist bgwhiter8">
        @if( !count($favoritesItem) )
        <div class="row">
          <div class="col-xs-12 text-center ">
            <div class="col-xs-12 bgwhiter ">
              <h3>暂时没有发布相关商品信息</h3>
            </div>
          </div>          
        </div>
        @endif

        <div style="width:100%;height:20px; background-color:#fff;"></div>

        @foreach ($favoritesItem as $goods)
        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}" target="_blank"><img src="{{ $goods->pict_url }}" width="100%" id="picBox" class="picBox" ></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}" target="_blank" class="gfontb">{{ $goods->title }}</a>
          </div>
          
          <?php
              if ($goods->zk_final_price_wap == $goods->reserve_price) {
                  $goods->reserve_price = $goods->reserve_price + round($goods->reserve_price * 0.15, 1);
              }
              $goods->reserve_price = number_format($goods->reserve_price, 2);
          ?>

          <div class="col-md-6 col-xs-12 gquan">限时价:<span class="gfontr">{{ $goods->zk_final_price_wap }}</span></div>
          <div class="col-md-6 col-xs-12 gquan">一口价:<del>{{ $goods->reserve_price }}</del></div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-9 hidden-sm   hidden-xs gyouhuih">30天销量:{{ $goods->volume}}</div>

            <?php
                $goods->volume = number_format($goods->volume);
            ?>

            <div class="col-md-9 hidden-md hidden-lg gyouhuih">
              <span class="hidden-md hidden-lg gfontr gfont18">
                @if($goods->user_type == 0) <img src="/admin/style/img/taobaofavicon.ico"> @endif
                @if($goods->user_type == 1) <img src="/admin/style/img/tmallfavicon.ico">  @endif
              </span>
              月销量:{{ $goods->volume}}
            </div>
            <div class="col-md-3 text-center hidden-xs text-md-12 text-xs-12 text-right gyouhuic">
              <span class="gfontr gfont18">
                @if($goods->user_type == 0) <img src="/admin/style/img/taobaofavicon.ico"> @endif
                @if($goods->user_type == 1) <img src="/admin/style/img/tmallfavicon.ico">  @endif
              </span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}"     target="_blank" class="gfontb">立即<span class="hidden-lg hidden-md "><br></span>抢购</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}" target="_blank" class="gfontb">商品<span class="hidden-lg hidden-md "><br></span>详情</a>
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
              {!! $favoritesItem->appends($oldRequest)->render() !!}
          </nav>
      </div>
    </div><!-- container框 -->

    <!-- 底部 -->
    @include('home.layouts.GoodListFooter')
@endsection