{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('seohead')
    <title>{{$pageInfo['title'] or '首页'}} - {{config('website.name')}}</title>
    <meta name="description" value="{{$pageInfo['description'] or config('website.name')}}" />
@endsection
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
    {{--nav导航--}}
    @include('home.layouts.ChaXunNav')
    
    <div style="display:block; width:100%; height:80px;"></div>

    <!--main-->
    <div class="jumbotron jumbotronbg">
      <div class="container">

        <div class="row">
          <div class="col-xs-12">            
            <h2 class="text-center">
              <img src="/home/style/img/logo.png" class="logo" />
              淘宝天猫优惠券查询
            </h2>
            <form class="form-horizontal" action="/chaxuninfo" method="get">
              <div class="form-group">
                <div class="col-md-3 col-sm-3"></div>
                <div class="col-md-5 col-sm-5 col-xs-9  left">
                  <input type="text" name="q" class="form-control form-control-add borderRadius0" placeholder="请输入要搜索的商品">
                </div>
                <div class="col-md-1 col-sm-1 col-xs-3 right">
                  <button type="submit" class="btn btn-red borderRadius0">搜索商品</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="row text-center">
          <ul class="list-inline">
            <li><strong>热门搜索:</strong></li>
            <li><a href="/chaxuninfo?q=男装" class="ared" target="_blank">男装</a></li>
            <li><a href="/chaxuninfo?q=女装" class="ared" target="_blank">女装</a></li>
            <li><a href="/chaxuninfo?q=零食" class="ared" target="_blank">零食</a></li>
            <li><a href="/chaxuninfo?q=丝袜" class="ared" target="_blank">丝袜</a></li>
            <li><a href="/chaxuninfo?q=男鞋" class="ared" target="_blank">男鞋</a></li>
            <li><a href="/chaxuninfo?q=女鞋" class="ared" target="_blank">女鞋</a></li>
          </ul>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-3"></div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <strong>优惠券使用说明</strong><br>
                <span>多个关键词有空格隔开，例如：T恤 白色</span><br>
                <span>优惠券的有效期一般比较短，请及时查询，即时使用。</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
@endsection