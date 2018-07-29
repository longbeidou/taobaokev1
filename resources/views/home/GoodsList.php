{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('seohead')
    <title>{{$title or '我爱你一万年'}}</title>
    <meta name="description" value="{{$description or '我爱你一万年'}}" />
@endsection
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
    <!-- 顶部的导航 -->
    <div class="container-fluid topcontainer hidden-xs">
      <div class="container">
        <!-- top部分 -->
        <div class="row">
          <div class="col-md-3 col-sm-4">
            <img src="/home/style/img/logolist.png">
          </div>

          <div class="col-md-6 col-sm-8">
            <div class="row text-center">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                <form class="form-horizontal" action="/chaxuninfo" method="get" target="_blank">
                  <div class="form-group">
                    <div class="col-sm-10 left">
                      <input type="text" name="q" class="form-control borderRadius0" placeholder="请输入要搜索的商品">
                    </div>
                    <div class="col-sm-2 right">
                      <button type="submit" class="btn btn-info borderRadius0">搜索商品</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="row text-center">
              <ul class="list-inline">
                <li><strong>热门搜索:</strong></li>
                <li><a href="" class="ared" target="_blank">男装</a></li>
                <li><a href="" class="ared" target="_blank">女装</a></li>
                <li><a href="" class="ared" target="_blank">零食</a></li>
                <li><a href="" class="ared" target="_blank">厨具</a></li>
              </ul>
            </div>
          </div>

          <div class="col-md-3 text-center hidden-sm">
            <div class="row">
              <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top01.png"></div>
              <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top02.png"></div>
              <div class="col-md-4 text-center fonttopimg"><img src="/home/style/img/top03.png"></div>
            </div>
            <div class="row">
              <div class="col-md-4 text-center fonttop">100%人工审核</div>
              <div class="col-md-4 text-center fonttop">实时维护排查</div>
              <div class="col-md-4 text-center fonttop">全天持续上新</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 导航 -->
    @include('home.layouts.GoodsListNav')

    <div class="container">
      <!-- 分类 -->
      <div class="row fenlei bgwhiter font14">
        <ul class="list-inline marginbottom0">
          <li><a href=""><strong>全部分类</strong></a></li>
          <li><a href="">女装</a></li>
          <li><a href="">男装</a></li>
          <li><a href="">鞋包配饰</a></li>
          <li><a href="">化妆品</a></li>
          <li><a href="">母婴</a></li>
          <li><a href="">美食</a></li>
          <li><a href="">内衣</a></li>
          <li><a href="">数码家电</a></li>
          <li><a href="">居家</a></li>
          <li><a href="">文体用品</a></li>
          <li><a href="">宠物</a></li>
          <li><a href="">成人</a></li>
        </ul>
      </div>
      <!-- 排序条件 -->
      <div class="row tiaojian bgwhiter font12">
        <ul class="list-inline marginbottom0">
          <li><a href=""><strong>综合排序</strong></a></li>
          <li><a href="">最新</a></li>
          <li><a href="">销量排序</a></li>
          <li><a href="">价格排序</a></li>
          <li><a href="">剩余时间</a></li>
        </ul>
      </div>
<!--       <div class="row tiaojian2 bgwhiter font12">
        <div class="col-xs-1" style="padding-left:0px;">
          <strong>其他条件：</strong>
        </div>        
        <form class="" action="" method="get">
          <div class="col-xs-1 paddinglr text-right">价格区间：</div>
          <div class="col-xs-1 ">
            <input type="text" class="form-control input-sm" id="price" name="minprice" />
          </div>
          <div class="col-xs-1 ">
            <input type="text" class="form-control input-sm" id="price" name="maxprice" />
          </div>
          <div class="col-xs-1 paddinglr text-right">销量区间：</div>
          <div class="col-xs-1 ">
            <input type="text" class="form-control input-sm" id="price" name="minprice" />
          </div>
          <div class="col-xs-1 ">
            <input type="text" class="form-control input-sm" id="price" name="maxprice" />
          </div>            
        </form>
      </div> -->

      <!-- 商品列表 -->
      <div class="row goodslist bgwhiter8">

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-6 goodsdiv bgwhiter">
          <div class="col-xs-12 gimg">
            <a href="" target="_blank"><img src="http://img.alicdn.com/bao/uploaded/i2/TB1cEfnRpXXXXXjaXXXXXXXXXXX_!!0-item_pic.jpg_400x400" width="100%"></a>            
          </div>
          <div class="col-xs-12 borderb gtitle">
            <a href="" target="_blank" class="gfontb">运动套装男夏季短袖运动衣五分短裤休闲运动装跑步运动服装两件套套套套套套</a>
          </div>
          <div class="col-md-5 col-xs-12 gquan">券后<span class="gfontr">39.00元</span></div>
          <div class="col-md-7 col-xs-12 text-right hidden-xs gxiaoliang">月销量<span class="gfontr">128000</span>件</div>
          <div class="col-xs-12 borderb gyouhui">
            <div class="col-md-4 text-right hidden-sm hidden-xs gyouhuih"><strong>优惠额度：</strong></div>
            <div class="col-md-8 text-md-12 text-xs-12 text-center gyouhuic">
              <span class="gfontr gfont18">满30元减10元</span>
            </div>            
          </div>
          <div class="col-xs-6 text-center borderb gbuy">
            <a href="" target="_blank" class="gfontb">领取优惠</a>
          </div>
          <div class="col-xs-6 text-center borderb ginfo">
            <a href="" target="_blank" class="gfontb">商品详情</a>
          </div>
        </div>


   

      </div><!-- 商品列表 -->
      <!-- 分页 -->
      <div class="row text-center">
          <nav aria-label="Page navigation">
              <ul class="pagination">
                  <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                  <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li><a href="#">5</a></li>
                  <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
             </ul>
          </nav>
      </div>
    </div><!-- container框 -->

    <!-- 底部 -->
    <div class="container-fluid bgwhiter gfooter">
      <div class="row text-center">
        Copyright&copy;2017&nbsp;www.52010000.cn版权所有！
      </div>
    </div>
@endsection