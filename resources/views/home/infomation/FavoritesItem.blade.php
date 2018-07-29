<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>{{ $favoritesItem->title }} - {{config('website.name')}}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style type="text/css">
        body{background-color: #f6f6f6; padding: 0px 0px; margin: 0px 0px;}
        .left{padding-right: 0px;}
        .right{padding-left: 0px;}
        .borderRadius0{border-radius: 0px;}
        .logo{width:48px;}
        body,button, input, select, textarea, h1 ,h2, h3, h4, h5, h6 { font-family: Microsoft YaHei,'宋体' , Tahoma, Helvetica, Arial, "\5b8b\4f53", sans-serif;}
        body{margin: 0px auto;}
        body, .fixtbottom{max-width: 600px;}

        .container-fluid{margin: 0px; padding: 0px;}
        .row{margin-right: 0px; margin-left: 0px; padding: 0px;}
        .bgwhite{background-color: #fff;}
        .margintop{margin-top: 10px;}
        .paddingtop{padding-top: 3px;}
        .paddingbottom{padding-bottom: 50px;}
        .coupontitle{font-size: 16px;}
        .qhby{color: #ed2a7a; font-size: 14px;}
        .pricenow{color: #ed2a7a; font-size: 18px; font-weight: 800;}
        .coupontitlemin{font-size: 12px; padding-top: 3px; display: inline-block; height: 35px; overflow: hidden;}
        .coupontitlemin a{color: #333;}
        .pricemin{font-size: 14px;}
        .pricenowmin{font-size: 16px; color: #ed2a7a;}
        .couponyouhuimin{font-size: 12px; padding: 2px 0px; margin: 0px; background-color: #ff9d08; color: #fff;}
        .coupontakeaway {font-size: 12px; padding: 2px 0px; margin: 0px; background-color: #ed2a7a;}
        .coupontakeaway a{color: #fff;}
        .col-xs-6{padding: 0px 3px; margin-top: 5px;}
        td{font-size: 12px;}

        .fixtbottom{color: #fff; font-size: 14px; background-color: #f6f6f6; position: fixed; bottom: 0px; width: 100%;}
        .fixtbottom a{font-size: 14px;}
        .fixtbottom button{color: #fff; border:none; margin: 0px; padding: 0px;}
        .bottom1{padding: 0px; padding:10px 0px;}
        .bottom1 a{color: #777; padding: 10px 0px;}
        .bottom2{padding: 0px; padding:10px 0px;}
        .bottom2 button{background-color: #f6f6f6; color: #777;}
        .bottom3{padding: 0px; padding:10px 0px; background-color: #ed2a7a; margin: 0px;}
        .bottom3 button{background-color: #ed2a7a;}

        .headimg{position: relative;}
        .top{position: absolute; width: 100%; top: 0px; left: 0px; padding: 5px 5px;}
        .top a{font-size: 30px; color: rgba(0,0,0,0.5)}

        .methodone{background-color: #ec7938; padding:5px 15px;}
        .methodtwo{padding:5px 15px; border-left:1px dashed #ed2a7a; border-right:1px dashed #ed2a7a; border-bottom:1px dashed #ed2a7a;}
        .twocode{border:2px dashed #ec7938; padding: 10px 0px; margin: 10px 0px;}
    </style>

    <!-- 倒计时 -->
    <?php
        $d = date('d')+1;
    ?>
    <script language="javascript" type="text/javascript"> 
    var interval = 1000; 
    function ShowCountDown(year,month,day,divname) 
    { 
    var now = new Date(); 
    var endDate = new Date(year, month-1, day); 
    var leftTime=endDate.getTime()-now.getTime(); 
    var leftsecond = parseInt(leftTime/1000); 
    //var day1=parseInt(leftsecond/(24*60*60*6)); 
    var day1=Math.floor(leftsecond/(60*60*24)); 
    var hour=Math.floor((leftsecond-day1*24*60*60)/3600); 
    var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60); 
    var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60); 
    var cc = document.getElementById(divname); 
    // cc.innerHTML = "脚本之家提示距离"+year+"年"+month+"月"+day+"日还有："+day1+"天"+hour+"小时"+minute+"分"+second+"秒";
    cc.innerHTML = "剩余时间："+day1+"天"+hour+"小时"+minute+"分"+second+"秒"; 
    } 
    window.setInterval(function(){ShowCountDown(2017,8,{{ $d }},'couponendtime');}, interval); 
    // window.setInterval(function(){ShowCountDown({{$favoritesItem->date[0]}},{{$favoritesItem->date[1]}},{{$favoritesItem->date[2]}},'couponendtime');}, interval); 
    </script> 

  </head>
  <body>
    <div class="container-fluid">
        <div class="row bgwhite headimg">
            <img src="{{ $favoritesItem->pict_url }}" width="100%">
            <!-- 头部的导航 -->
            <div class="row top">
                <a href="/"><i class="glyphicon glyphicon-home"></i></a>
            </div>
        </div>
        <div class="row bgwhite paddingtop">
            <strong class="coupontitle">{{ $favoritesItem->title }}</strong>
        </div>
        <div class="row bgwhite">
            <span class="qhby">限时特价</span>
            <span class="pricenow">{{ $favoritesItem->zk_final_price_wap }}</span>
            <span style="float:right;" id="couponendtime"></span>
        </div>
        <div class="row bgwhite">
            <table class="table table-condensed " style="margin-bottom:0px;">
                <?php
                    // 计算优惠占比
                    $rate = ($favoritesItem->reserve_price - $favoritesItem->zk_final_price_wap) / $favoritesItem->reserve_price * 100;
                    $rate = round($rate, 2);

                    //　优惠额度
                    $cha = $favoritesItem->reserve_price - $favoritesItem->zk_final_price_wap;
                ?>
                <tbody>
                    <tr>
                        <td>限时特价:{{ $favoritesItem->zk_final_price_wap }}</td>
                        <td>原价:<del>{{ $favoritesItem->reserve_price }}</del></td>
                        <td>优惠占比:{{ $rate }}%</td>
                    </tr>
                    <tr>
                        <td>30天销量:{{ $favoritesItem->volume }}</td>
                        <td>来源: 
                            @if($favoritesItem->user_type == '1')
                            <img src="/admin/style/img/tmallfavicon.ico">
                            @else
                            <img src="/admin/style/img/taobaofavicon.ico">
                            @endif
                        </td>                         
                        <td>优惠金额:￥{{ $cha }}</td>
                    </tr>
                    <tr style="height:1px;">
                        <td style="height:1px; line-height:1px;"></td>
                        <td style="height:1px; line-height:1px;"></td>
                        <td style="height:1px; line-height:1px;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row bgwhite">
            <span class="text-left" style="color:#ed2a7a; font-size:14px;">【淘口令购买】长框内>全选>复制</span>
            <div id="tkl" class="text-center" style="margin:5px 10px; border:1px dashed red; padding:5px 0px; color:red; font-size:14px;">
                淘口令：{{ $favoritesItem->tao_kou_ling }}
            </div>
            <div class="text-center" style="margin-bottom:10px; margin-top:10px;">
                <!-- <button class="btn btn-danger btn-sm" style="border-radius: 50px;">一键复制淘口令</button> -->
            </div>
            <p style="font-size:14px;">长按虚线框，成功手动复制淘口令后，请打开【手机淘宝】购买！<br />
                <!-- 注：若复制失败，请手动选择淘口令复制！<br /> -->
                <span style="color:red;"> 温馨提示：手机无【手机淘宝】者，可选择领券方式购买哦~~~</span>               
            </p>
        </div>
<!--         <div class="row bgwhite margintop">
            <h5>
                商品图文详情<span style="font-size:10px; color:blue;">(点击展开）</span>
                <span style="float:right;"><i class="glyphicon glyphicon-chevron-right" ></i></span>                
            </h5>
        </div> -->
        <div class="row text-center" style="margin:8px 0px;">
            <span><i class="glyphicon glyphicon-heart" style="color:#ed2a7a;"></i> 猜你喜欢</span>
        </div>

        <div class="row bgwhite paddingtop paddingbottom">
            @foreach($recommendInfo as $key=>$goods)
            <div class="col-xs-6">
                <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}">
                    <img src="{{ $goods->pict_url }}" width="100%" @if($key == 0) id="picBox" @endif class="picBox">
                </a>
                <span class="coupontitlemin">
                    <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}">{{ $goods->title }}</a>
                </span>
                <div class="row">
                    <span class="pricenowmin">￥{{ $goods->zk_final_price_wap }}</span>
                    <del class="pricemin">￥{{ $goods->reserve_price }}</del>
                </div>
                <div class="row">
                    <div class="col-xs-7 text-center couponyouhuimin text-nowrap">限时抢购</div>
                    <div class="col-xs-5 text-center coupontakeaway text-nowrap">
                        <a href="{{ route('urlFavoritesItemInfo',['id'=>$goods->id]) }}">马上购买</a>
                    </div>
                </div>
            </div>
                @if($key%2 == 1)
                <div class="clearfix visible-xs-block"></div>
                @endif
            @endforeach
        </div>

        <!-- 底部固定的导航 -->
        <div class="row fixtbottom">
            <div class="col-xs-3 text-center bottom1">
                <a href="/"><i class="glyphicon glyphicon-home"></i> 首页</a>
            </div>
            <div class="col-xs-3 text-center bottom2">
                <button type="button" class="" data-toggle="modal" data-target=".bs-example-modal-lg">
                    <i class="glyphicon glyphicon-user"></i> 客服
                </button>
            </div>
            <div class="col-xs-6 text-center bottom3">
                <button type="button" class="" data-toggle="modal" data-target=".bs-example-modal-sm">
                    <i class="glyphicon glyphicon-gift"></i> 领券购买
                </button>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <h4 class="modal-title" id="myLargeModalLabel">客服微信</h4>
                </div>
                <div class="modal-body">
                  <div class="text-center">
                        <img src="http://www.longbeidou.com/zb_users/upload/2017/03/201703161489634461104536.jpg">
                  </div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>

          <div class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;; padding-right: 17px;">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">

                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <h4 class="modal-title" id="mySmallModalLabel">领券购买</h4>
                </div>
                <div class="modal-body">
                    <div class="methodone">
                        <h5 style="font-size:14px; font-weight:800; color:#fff;">方法一：</h5>
                        <p style="color:#fff;">
                            请点击右上角 <img src="/home/style/img/wechatpoint.png" style="height:22px;"><br>
                            并选择<em style="color:yellow;">在浏览器中打开</em><br />
                            就可以到淘宝下单啦！
                        </p>
                    </div>
                    <div class="methodtwo" style="font-size:10px;">
                        <h5 style="font-size:14px; font-weight:800;">方法二：</h5>
                        <div class="text-center">长按虚线框复制下方淘口令，打开手机淘宝购买</div>
                        <div class="twocode text-center" style=" font-size:14px; color:red;">淘口令：{{ $favoritesItem->tao_kou_ling }}</div>
                        <div class="text-center" style="margin-bottom:10px; margin-top:10px;">
                            <!-- <button class="btn btn-danger btn-sm" style="border-radius: 50px; ">一键复制淘口令</button> -->
                        </div>
                        <div class="text-center" style="font-size:12px; color:blue;">
                            点击复制后，请打开【手机淘宝】购买！<br>
                            注：若复制失败，请手动选择淘口令复制！
                        </div>
                    </div>
                </div>

              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>

    </div>

      <script type="text/javascript" src="/home/style/js/jquery-3.2.1.min.js"></script>
      <!-- 使得图片的长度和宽度相等的操作 -->
      <script type="text/javascript">          
          var w=$('#picBox').width();
          $('.picBox').attr('height',w*1);
          $('.picBox').attr('width',w);
      </script>
      
      <div style="display:none;">{!! config('website.analysisJs') !!}</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>