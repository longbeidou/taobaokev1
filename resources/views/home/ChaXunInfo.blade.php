{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('seohead')
    <title>{{$headinfo['title'] or '查询结果'}} - {{config('website.name')}}</title>
    <meta name="description" value="{{$headinfo['description'] or config('website.name')}}" />
@endsection
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
    {{--nav导航--}}
    @include('home.layouts.ChaXunNav')

    <div class="container mainmargin">
      <!-- 搜索框 -->
      <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
          <form class="form-horizontal" action="/chaxuninfo" method="get">
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-9  left">
                <input type="text" class="form-control form-control-add borderRadius0" name="q" value="{{$q}}" placeholder="请输入要搜索的商品">
              </div>
              {{csrf_field()}}
              <div class="col-md-1 col-sm-1 col-xs-3 right">
                <button type="submit" class="btn btn-red borderRadius0">搜索商品</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- 搜索列表头部 -->
      <div class="sousuohead">
        <div class="row text-center">
          <div class="col-sm-2 col-xs-6">
            <strong>商品主图</strong>
          </div>
          <div class= "col-sm-3 hidden-xs">
            <strong>商品名称</strong>
          </div>
          <div class= "col-sm-1 hidden-xs">
            <strong>商品价格</strong>
          </div>
          <div class= "col-sm-2 hidden-xs">
            <strong>优惠额度</strong>
          </div>
          <div class= "col-sm-2 hidden-xs">
            <strong>结束时间</strong>
          </div>
          <div class= "col-sm-2 col-xs-6">
            <strong>优惠券详情</strong>
          </div>
        </div>
      </div>

      <div class="sousuobody">
        @if( !count($info) )
        <div class="row rowmargin">
          <div class="col-xs-12 text-center">
            <h3>暂时没有发布相关商品信息</h3>
          </div>          
        </div>
        @endif

        @foreach ($info as $value)
        <div class="row chaxunrowmargin chaxunrowpadding chaxunrowbox">
          <div class="col-sm-2 col-xs-6 text-center">
            <img src="{{$value->image}}" width="100%">
          </div>
          <div class="col-sm-3 col-xs-6">
            <a href="{{ route('urlCouponInfo',['id'=>$value->id]) }}" target="_blank">{{$value->goods_name}}</a>
          </div>
          <div class="col-sm-1 col-xs-6 text-center">
            {{$value->price}}
          </div>
          <div class="col-sm-2 col-xs-6 text-center">
            {{$value->yhq_info}}
          </div>
          <div class="col-sm-2 hidden-xs text-center">
            {{$value->yhq_end}}
          </div>
          <div class="col-sm-2 col-xs-6 text-center">
            <a href="{{ route('urlCouponInfo',['id'=>$value->id]) }}" class="btn btn-red btn-sm" target="_blank">领取优惠券</a>
          </div>
        </div>
        @endforeach       

      </div>

      <!-- 搜索的分页 -->      
      <div class="sousuopage text-center">
        <nav aria-label="Page navigation">
          {!! $info->appends(['q'=>$q])->render() !!}
            <!-- <ul class="pagination">
              <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
              <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
           </ul> -->
        </nav>    
      </div>

      <!-- 版权声明 -->
      <div class="row text-center copy">
        Copyright&copy;{{date('Y')}}&nbsp;{{config('website.domain')}}版权所有！
      </div>

    </div>
@endsection