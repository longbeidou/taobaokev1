    <!-- Fixed navbar -->
    <div class="container">
      <nav class="navbar navbar-default navbar-fixed-top chaxunnav-bg">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
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
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="activenav navahover">               
                <a style="color:#fff;" href="/"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 搜索优惠券</a>
              </li>
              @foreach($categoryAllInfo as $category)
              <li class="navahover">
                <a style="color:#fff;" href="{{ route('homeGoodsListCategory', ['categoryId'=>$category['id']]) }}" target="_blank">
                  <span class="glyphicon glyphicon-gift" aria-hidden="true"></span> {{ $category['name'] }}
                </a>
              </li>
              @endforeach

              <li class="navahover">
                <a style="color:#fff;" href="{{ route('homeGoodsListCategory', ['categoryId'=>1, 'isrecommend'=>'yes']) }}" target="_blank">
                  <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 双11特别推荐
                </a>
              </li>

              <!--li class="navahover">
                <a style="color:#fff;" href="{{route('homeGoodsListCategory')}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                  <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> 每日推荐商品 <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  @foreach($categoryAllInfo as $category)
                  <li><a href="{{ route('homeGoodsListCategory', ['categoryId'=>$category['id'], 'isrecommend'=>'yes']) }}" target="_blank" >&nbsp;&nbsp;<i class="glyphicon glyphicon-hand-right"></i> {{ $category['name'] }}</a></li>
                  @endforeach
                </ul>
              </li-->
              
              <!-- <li><a href="./">Fixed top <span class="sr-only">(current)</span></a></li> -->
            </ul>
          </div>
        </div>
      </nav>
    </div>
