{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>优惠券商品列表 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>搜索面板</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <form action="{{ route('couponlist') }}" method="GET">
            <div class="ibox-content">
				<div class="row">
                    <div class="col-sm-4 ">
                        <strong>平台:</strong>
                        <div data-toggle="buttons" class="btn-group"> 
                            <label class="btn btn-sm btn-white <?php if((!empty($oldRequest['flat']) && $oldRequest['flat'] != '1' && $oldRequest['flat'] != '2') || empty($oldRequest['flat'])) echo 'active'; ?>">
                                <input type="radio" id="option1" name="flat" value="0">全部</label>
                            <label class="btn btn-sm btn-white <?php if(!empty($oldRequest['flat']) && $oldRequest['flat'] == '1') echo 'active'; ?> ">
                                <input type="radio" id="option2" name="flat" <?php if(!empty($oldRequest['flat']) && $oldRequest['flat'] == '1') echo 'checked'; ?> value="1">淘宝</label>
                            <label class="btn btn-sm btn-white <?php if(!empty($oldRequest['flat']) && $oldRequest['flat'] == '2') echo 'active'; ?>">
                                <input type="radio" id="option3" <?php if(!empty($oldRequest['flat']) && $oldRequest['flat'] == '2') echo 'checked'; ?> name="flat" value="2">天猫</label>
                        </div>
                    </div>
                    <div class="col-sm-4 ">
                        <strong>是否推荐:</strong>
                        <div data-toggle="buttons" class="btn-group">
                            <label class="btn btn-sm btn-white <?php if((!empty($oldRequest['isRecommend']) && $oldRequest['isRecommend'] != '1' && $oldRequest['isRecommend'] != '2') || empty($oldRequest['isRecommend'])) echo 'active'; ?>">
                                <input type="radio" id="option1"  name="isRecommend" value="0">全部</label>
                            <label class="btn btn-sm btn-white <?php if(!empty($oldRequest['isRecommend']) && $oldRequest['isRecommend'] == '1') echo 'active'; ?> ">
                                <input type="radio" id="option2" <?php if(!empty($oldRequest['isRecommend']) && $oldRequest['isRecommend'] == '1') echo 'checked'; ?> name="isRecommend" value="1">是</label>
                            <label class="btn btn-sm btn-white <?php if(!empty($oldRequest['isRecommend']) && $oldRequest['isRecommend'] == '2') echo 'active'; ?>">
                                <input type="radio" id="option3" <?php if(!empty($oldRequest['isRecommend']) && $oldRequest['isRecommend'] == '2') echo 'checked'; ?> name="isRecommend" value="2">否</label>
                        </div>
                    </div>
                    <div class="col-sm-4 ">
                        <strong>每页显示</strong>
                        <div data-toggle="buttons" class="btn-group">
                                <select class="form-control m-b btn-sm" name="pageNumber">
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '10') echo 'selected'; ?> value="10">10</option>
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '20') echo 'selected'; ?> value="20">20</option>
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '30') echo 'selected'; ?> value="30">30</option>
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '40') echo 'selected'; ?> value="40">40</option>
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '50') echo 'selected'; ?> value="50">50</option>
                                    <option <?php if(!empty($oldRequest['pageNumber']) && $oldRequest['pageNumber'] == '100') echo 'selected'; ?> value="100">100</option>
                                </select>
                        </div>
                        <strong>条信息</strong>
                    </div>

                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>原价:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="10000" step="0.01" placeholder="最小值" name="priceOriginMin" value="<?php if(!empty($oldRequest['priceOriginMin'])) echo $oldRequest['priceOriginMin']; ?>" class="form-control input-sm">
                            </div>
                            <div class="form-group"> 
                                <input type="number" min="0.0" max="10000" step="0.01" placeholder="最大值" name="priceOriginMax" value="<?php if(!empty($oldRequest['priceOriginMax'])) echo $oldRequest['priceOriginMax']; ?>" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>现价:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="10000" step="0.01" placeholder="最小值" name="priceNowMin" value="<?php if(!empty($oldRequest['priceNowMin'])) echo $oldRequest['priceNowMin']; ?>" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="10000" step="0.01" placeholder="最大值" name="priceNowMax" value="<?php if(!empty($oldRequest['priceNowMax'])) echo $oldRequest['priceNowMax']; ?>" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>佣金:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="999" step="0.01" placeholder="最小值" name="moneyMin" value="<?php if(!empty($oldRequest['moneyMin'])) echo $oldRequest['moneyMin']; ?>" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="999" step="0.01" placeholder="最大值" name="moneyMax" value="<?php if(!empty($oldRequest['moneyMax'])) echo $oldRequest['moneyMax']; ?>" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>销量:</strong>
                            <div class="form-group">
                                <input type="number" min="0" max="999999" step="1" placeholder="最小值" name="salesMin" value="<?php if(!empty($oldRequest['salesMin'])) echo $oldRequest['salesMin']; ?>" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0" max="999999" step="1" placeholder="最大值" name="salesMax" value="<?php if(!empty($oldRequest['salesMax'])) echo $oldRequest['salesMax']; ?>" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                    <div class="col-sm-4">
                        <div class="form-inline">
                            <strong>优惠比:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="1" step="0.01" placeholder="最小值" name="rateSalesMin" value="<?php if(!empty($oldRequest['rateSalesMin'])) echo $oldRequest['rateSalesMin']; ?>" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="1" step="0.01" placeholder="最大值" name="rateSalesMax" value="<?php if(!empty($oldRequest['rateSalesMax'])) echo $oldRequest['rateSalesMax']; ?>" class="form-control input-sm">
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-6"></div> -->
                    <div class="col-sm-4">
                    	<div class="form-inline">
                            <div class="form-group">
                            	<strong>商品:</strong>
                                <input type="text" placeholder="请输入商品信息" class="input-sm form-control" name="q" value="<?php if(!empty($oldRequest['q'])) echo $oldRequest['q']; ?>"> 
                                <button type="submit" class="btn btn-sm btn-primary" style="float:right;"> 搜 索</button>
                            </div>
                    	</div>
                    </div>
                </div>
            </div>
        	</form>

        </div>
    </div>
</div>

<div class="row">
	<div class="col-sm-12">
		@if (!empty(session('deleteStatus')) && session('deleteStatus') == 'success')
			<p style="color:green;">删除商品优惠券成功！</p>
		@endif
		@if (!empty(session('deleteStatus')) && session('deleteStatus') == 'failed')
			<p style="color:red;">删除商品优惠券失败！</p>
		@endif
		@if (!empty(session('recommendStatus')) && session('recommendStatus') == 'success')
			<p style="color:green;">推荐商品优惠券成功！</p>
		@endif
		@if (!empty(session('recommendStatus')) && session('recommendStatus') == 'failed')
			<p style="color:red;">推荐商品优惠券失败！</p>
		@endif
		@if (!empty(session('cancelRecommendStatus')) && session('cancelRecommendStatus') == 'success')
			<p style="color:green;">取消推荐商品优惠券成功！</p>
		@endif
		@if (!empty(session('cancelRecommendStatus')) && session('cancelRecommendStatus') == 'failed')
			<p style="color:red;">取消推荐商品优惠券失败！</p>
		@endif
	</div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <form action="" method="post" id="couponList">
            	{{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>

                                <th></th>
                                <th>商品图</th>
                                <th style="width:20%">商品名</th>
                                <th >商品一级类目</th>
                                <th >优惠条件</th>
                                <th class="text-center">原价<br />
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=priceoriginup"  ><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'priceoriginup') style="color:red" @endif class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=priceorigindown"><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'priceorigindown') style="color:red" @endif class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">现价<br />
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=pricenowup"  ><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'pricenowup') style="color:red" @endif class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=pricenowdown"><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'pricenowdown') style="color:red" @endif class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">优惠比<br />
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=rateSalesUp"  ><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'rateSalesUp') style="color:red" @endif class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=rateSalesDown"><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'rateSalesDown') style="color:red" @endif class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">佣金<br />
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=moneyup"  ><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'moneyup') style="color:red" @endif class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=moneydown"><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'moneydown') style="color:red" @endif class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">月销量<br />
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=salesup"  ><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'salesup') style="color:red" @endif class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a href="{{ route('couponlist') }}?{{ $urlParameter }}couponorder=salesdown"><i @if(!empty($oldRequest['couponorder']) && $oldRequest['couponorder'] == 'salesdown') style="color:red" @endif class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">平台</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                        	@foreach($info as $goods)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="goodsIdArray[]" value="{{ $goods->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>
                                	<a href="{{ $goods->info_link }}" target="_blank"><img src="{{ $goods->image }}" width="70px"></a>
                                </td>
                                <td>
                                	<a href="{{ $goods->info_link }}" target="_blank">{{ $goods->goods_name }}</a>
                                </td>
                                <td>{{ $goods->cate }}</td>
                                <td>{{ $goods->yhq_info }}</td>
                                <td class="text-center">{{ $goods->price }}</td>
                                <td class="text-center">{{ $goods->price_now }}</td>
                                <td class="text-center">{{ $goods->rate_sales*100 }}%</td>
                                <td class="text-center">{{ $goods->money }}</td>
                                <td class="text-center">{{ $goods->sales }}</td>
                                <td class="text-center">
                                    @if($goods->flat == "天猫") <img src="/admin/style/img/tmallfavicon.ico"> @endif
                                    @if($goods->flat == "淘宝") <img src="/admin/style/img/taobaofavicon.ico"> @endif
                                </td>
                                <td class="text-center">
                                	<a href="{{ route('couponDeleteOne') }}?id={{ $goods->id }}"><i class="fa fa-close text-navy" title="删除优惠券" style="color:red;"></i></a> |
                                	@if($goods->is_recommend == '否')
                                	<a href="{{ route('couponRecommendOne') }}?id={{ $goods->id }}"><i class="fa fa-hand-o-up text-navy" title="推荐" style="color:green;"></i></a>
                                	@endif
                                	@if($goods->is_recommend == '是')
                                	<a href="{{ route('couponCancelRecommendOne') }}?id={{ $goods->id }}"><i class="fa fa-hand-o-down text-navy" title="取消推荐" style="color:red;"></i></a>
                                	@endif
                                </td>
                            </tr>
                            @endforeach 

                        </tbody>
                    </table>
                    @if(!count($info))
                    <h3>暂时没有查询到对应的商品</h3>
                    @endif
                    <table class="table table-striped">
                    	<tbody>
                    		<tr class="info">
                    			<td>
                    				<span type="" class="btn btn-xs btn-info"    onclick="chk(1)">全选  </span>
                    				<span type="" class="btn btn-xs btn-primary" onclick="chk(2)">反选  </span>
                    				<span type="" class="btn btn-xs btn-success" onclick="chk(3)">全不选</span>
                    				<span>|</span>
                    				<button type="submit" class="btn btn-xs btn-info"    onclick="submitChoice(1)"><i class="fa fa-close text-navy" style="color:#fff;"></i> 删除选中</button>
                    				<button type="submit" class="btn btn-xs btn-primary" onclick="submitChoice(2)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 推荐选中</button>
                    				<button type="submit" class="btn btn-xs btn-success" onclick="submitChoice(3)"><i class="fa fa-hand-o-down text-navy" style="color:#fff;"></i> 取消推荐</button>
                    			</td>
                    		</tr>
                    	</tbody>
                    </table>
                </div>
        	</form>
<!-- 实现全选、反选、全不选 -->
<script type="text/javascript">
	function chk(value) {
		var chktotal = $("#chk");

		if (value == 1) { //全选
			$("input:checkbox").each(function () {  
			this.checked = true;  
			}) 
		}
		if (value == 2) { //反选
          $("input:checkbox").each(function () {  
            this.checked = !this.checked;  
         }) 
		}
		if (value == 3) { //全不选
			$("input:checkbox").removeAttr("checked"); 
		}
	}

</script>
<!-- 确定提交地址的js -->
<script type="text/javascript">
	function submitChoice(value) {
		var form = $("#couponList");

		if (value == 1) {
			form.action = '{{ route('couponDeleteMany') }}';
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('couponRecommendMany') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 3) {
			form.action = "{{ route('couponCancelRecommendMany') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
	}

</script>
      <!-- 分页 -->
      <div class="row text-center">
          <nav aria-label="Page navigation">
              {!! $info->appends($oldRequest)->render() !!}
          </nav>
      </div>
            </div>
            </form>
        </div>
    </div>

</div>
@endsection