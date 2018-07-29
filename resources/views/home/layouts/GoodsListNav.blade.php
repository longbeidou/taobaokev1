    <div class="container-fluid topnav">
      <div class="container">
        <!-- 导航部分 -->
        <div class="row">
          <nav class="navbar navbar-inverse topnav2">
            <div class="container-fluid goodslist-fluid-add">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <!-- PC端显示 -->
                <a class="navbar-brand hidden-xs hidden-sm" style="color:#fff;" href="/">
                  {!!config('website.navtext')!!}
                </a>
                <!-- 移动端显示 -->
                <a class="navbar-brand hidden-lg hidden-md" style="color:#fff;" href="/">
                  <img src="{{config('website.navimg')}}" height="100%">
                </a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                  <li class="navahover">
                    <a style="color:#fff;" href="/"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 搜索优惠券
                    </a>
                  </li>

                  <?php
                      if (!empty($_GET['isrecommend']) && $_GET['isrecommend'] === 'yes') {
                         $activenav = false;
                      } else {
                         $activenav = true;
                      }
                  ?>

                  @foreach($categoryAllInfo as $category)
                  <li class="@if($categoryInfo['id'] == $category['id'] && $activenav) activenav @endif navahover">
                    <a style="color:#fff;" href="{{ route('homeGoodsListCategory', ['categoryId'=>$category['id']]) }}" target="_blank">
                      <span class="glyphicon glyphicon-gift" aria-hidden="true"></span> {{ $category['name'] }}
                    </a>
                  </li>
                  @endforeach

              <li class="@if(!empty($_GET['isrecommend']) && $_GET['isrecommend'] === 'yes') activenav @endif  navahover">
                <a style="color:#fff;" href="{{ route('homeGoodsListCategory', ['categoryId'=>1, 'isrecommend'=>'yes']) }}" target="_blank">
                  <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 双11特别推荐
                </a>
              </li>

                  <!--li class="@if(!empty($_GET['isrecommend']) && $_GET['isrecommend'] === 'yes') activenav @endif navahover">
                    <a style="color:#fff;" href="{{route('homeGoodsListCategory')}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 每日推荐商品 <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                      @foreach($categoryAllInfo as $category)
                      <li><a href="{{ route('homeGoodsListCategory', ['categoryId'=>$category['id'], 'isrecommend'=>'yes']) }}" target="_blank" >&nbsp;&nbsp;<i class="glyphicon glyphicon-hand-right"></i> {{ $category['name'] }}</a></li>
                      @endforeach
                    </ul>
                  </li-->

                </ul>
              </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
          </nav>
        </div>
      </div>
    </div>
