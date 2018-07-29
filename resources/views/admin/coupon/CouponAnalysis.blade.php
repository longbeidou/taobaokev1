{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>优惠券商品分析 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>优惠券商品分析</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
    <!--             <a class="dropdown-toggle" data-toggle="dropdown" href="table_basic.html#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="table_basic.html#">选项1</a>
                    </li>
                    <li><a href="table_basic.html#">选项2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a> -->
            </div>
        </div>
        <div class="ibox-content">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>一级类目</th>
                        <th class="text-right">商品种类数</th>
                        <th class="text-right">种类数占比</th>
                        <th class="text-right">最低卖价</th>
                        <th class="text-right">最高卖价</th>
                        <th class="text-right">平均卖价</th>
                        <th class="text-right">最低销量</th>
                        <th class="text-right">最高销量</th>
                        <th class="text-right">平均销量</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couponAnalysis as $key=>$coupon)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $coupon['cate'] }}</td>
                        <td class="text-right">{{ $coupon['goodsSum'] }}</td>
                        <td class="text-right">{{ round($coupon['goodsRate'], 2) }}%</td>
                        <td class="text-right">￥{{ $coupon['minPriceNow'] }}</td>
                        <td class="text-right">￥{{ $coupon['maxPriceNow'] }}</td>
                        <td class="text-right">￥{{ round($coupon['avgPriceNow'], 2) }}</td>
                        <td class="text-right">{{ $coupon['minSales'] }}</td>
                        <td class="text-right">{{ number_format($coupon['maxsales']) }}</td>
                        <td class="text-right">{{ number_format(round($coupon['avgsales'], 0)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection