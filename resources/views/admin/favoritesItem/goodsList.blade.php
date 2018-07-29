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
            <form action="{{ route('favoritesItemGoodsList') }}" method="GET" >
            <div class="ibox-content">
				<div class="row">
                    <div class="col-sm-4 ">

                        <strong>栏目:</strong>
                        <div data-toggle="buttons" class="btn-group">
                                <select class="form-control m-b btn-sm" name="categoryId">
                                    <option @if($oldRequest['categoryId'] == 0) selected @endif value="0">全部栏目</option>
                                    @foreach($categoryInfo as $category)
                                    <option @if($category['id'] == $oldRequest['categoryId']) selected @endif value="{{$category['id']}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <strong>选品库列表:</strong>
                        <div data-toggle="buttons" class="btn-group">
                                <select class="form-control m-b btn-sm" name="favoritesId">
                                    <option @if($oldRequest['favoritesId'] == 0) selected @endif value="0">全部列表</option>
                                    @foreach($favoritesInfo as $favorites)
                                    <option @if($favorites['favorites_id'] == $oldRequest['favoritesId']) selected @endif value="{{$favorites['favorites_id']}}">{{$favorites['name']}}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                    <div class="col-sm-4 ">
                        <strong>每页显示</strong>
                        <div data-toggle="buttons" class="btn-group">
                                <select class="form-control m-b btn-sm" name="pageSize">
                                    <option @if($oldRequest['pageSize'] == 10)  selected @endif value="10">10</option>
                                    <option @if($oldRequest['pageSize'] == 20)  selected @endif value="20">20</option>
                                    <option @if($oldRequest['pageSize'] == 30)  selected @endif value="30">30</option>
                                    <option @if($oldRequest['pageSize'] == 40)  selected @endif value="40">40</option>
                                    <option @if($oldRequest['pageSize'] == 50)  selected @endif value="50">50</option>
                                    <option @if($oldRequest['pageSize'] == 100) selected @endif value="100">100</option>
                                </select>
                        </div>
                        <strong>条信息</strong>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-inline">
                            <strong>无线售价:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最小值" name="zkFinalPriceWapMin" value="{{$oldRequest['zkFinalPriceWapMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最大值" name="zkFinalPriceWapMax" value="{{$oldRequest['zkFinalPriceWapMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 ">
                        <strong>平台:</strong>
                        <div data-toggle="buttons" class="btn-group"> 
                            <label class="btn btn-sm btn-white   @if($oldRequest['userType'] == 'default') active  @endif">
                                <input type="radio" id="option1" @if($oldRequest['userType'] == 'default') checked @endif name="userType" value="default">全部</label>
                            <label class="btn btn-sm btn-white   @if($oldRequest['userType'] == 1) active  @endif">
                                <input type="radio" id="option1" @if($oldRequest['userType'] == 1) checked @endif name="userType" value="1">淘宝</label>
                            <label class="btn btn-sm btn-white   @if($oldRequest['userType'] == 2) active  @endif">
                                <input type="radio" id="option3" @if($oldRequest['userType'] == 2) checked @endif name="userType" value="2">天猫</label>
                        </div>
                    </div>
                    <div class="col-sm-4 ">
                        <strong>是否推荐:</strong>
                        <div data-toggle="buttons" class="btn-group">
                            <label class="btn btn-sm btn-white   @if($oldRequest['isRecommend'] === 'default') active @endif">
                                <input type="radio" id="option1" @if($oldRequest['isRecommend'] === 'default') checked @endif name="isRecommend" value="default">全部</label>
                            <label class="btn btn-sm btn-white   @if($oldRequest['isRecommend'] === '是') active @endif">
                                <input type="radio" id="option2" @if($oldRequest['isRecommend'] === '是') checked @endif name="isRecommend" value="是">是</label>
                            <label class="btn btn-sm btn-white   @if($oldRequest['isRecommend'] === '否') active @endif">
                                <input type="radio" id="option3" @if($oldRequest['isRecommend'] === '否') checked @endif name="isRecommend" value="否">否</label>
                        </div>
                    </div>

                    <div style="clear:both;"></div>

                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>折扣价格:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最小值" name="zkFinalPriceMin" value="{{$oldRequest['zkFinalPriceMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最大值" name="zkFinalPriceMax" value="{{$oldRequest['zkFinalPriceMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                    <div class="col-sm-4">
                        <div class="form-inline">
                            <strong>佣金:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最小值" name="moneyMin" value="{{$oldRequest['moneyMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最大值" name="moneyMax" value="{{$oldRequest['moneyMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>收入比例:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100" step="0.0001" placeholder="最小值" name="tkRateMin" value="{{$oldRequest['tkRateMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100" step="0.0001" placeholder="最大值" name="tkRateMax" value="{{$oldRequest['tkRateMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                	</div>

                	<div class="col-sm-4">
                    	<div class="form-inline">
                    		<strong>一口价格:</strong>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最小值" name="reservePriceMin" value="{{$oldRequest['reservePriceMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0.0" max="100000" step="0.01" placeholder="最大值" name="reservePriceMax" value="{{$oldRequest['reservePriceMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                	</div>
                    <div class="col-sm-4">
                        <div class="form-inline">
                            <strong>销量:</strong>
                            <div class="form-group">
                                <input type="number" min="0" max="999999999" step="1" placeholder="最小值" name="volumeMin" value="{{$oldRequest['volumeMin'] or ''}}" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <input type="number" min="0" max="999999999" step="1" placeholder="最大值" name="volumeMax" value="{{$oldRequest['volumeMax'] or ''}}" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                    	<div class="form-inline">
                            <div class="form-group">
                            	<strong>商品:</strong>
                                <input type="text" placeholder="请输入商品信息" class="input-sm form-control" name="q" value="{{$oldRequest['q'] or ''}}"> 
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
		@if (!empty(session('status')))
			<p style="color:green;">{{ session('status') }}</p>
		@endif

        @if(count($errors) > 0)
            <ul>
                @foreach($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
                @endforeach
            </ul>
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
                                <th class="text-center">商品图</th>
                                <th class="text-center" style="width:40%">商品名</th>
                                <th class="text-center">一口价格<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'reservePriceAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=reservePriceAsc  " ><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'reservePriceDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=reservePriceDesc " ><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">折扣价格<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'zkFinalPriceAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=zkFinalPriceAsc  " ><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'zkFinalPriceDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=zkFinalPriceDesc " ><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">无线售价<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'zkFinalPriceWapAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=zkFinalPriceWapAsc "><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'zkFinalPriceWapDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=zkFinalPriceWapDesc"><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">收入比例<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'tkRateAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=tkRateAsc  "><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'tkRateDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=tkRateDesc "><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">佣金<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'moneyAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=moneyAsc  "><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'moneyDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=moneyDesc "><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">月销量<br />
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'volumeAsc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=volumeAsc  "><i class="glyphicon glyphicon-arrow-up  "></i></a> 
                                    <a @if(!empty($oldRequest['orderud']) && $oldRequest['orderud'] == 'volumeDesc' ) style="color:red;" @endif href="{{ route('favoritesItemGoodsList', $oldRequest) }}&orderud=volumeDesc "><i class="glyphicon glyphicon-arrow-down"></i></a> 
                                </th>
                                <th class="text-center">平台</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                        	@foreach($goodsInfo as $goods)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="goodsIdArray[]" value="{{ $goods->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>
                                	<a href="{{ $goods->item_url}}" target="_blank"><img src="{{ $goods->pict_url }}" width="70px"></a>
                                </td>
                                <td>
                                	<a href="{{ $goods->item_url}}" target="_blank">{{ $goods->title }}</a><br />
                                    <ul class="list-inline">
                                        <li>店铺名:{{ $goods->shop_title }}</li>
                                        <li>卖家昵称:{{ $goods->nick }}</li>
                                        <li>所在地:{{ $goods->provcity }}</li>
                                        <li>一级类目:{{ $goods->category}}</li>
                                        <li>
                                            宝贝状态:@if($goods->status == 0) <span style="color:red;">失效</span> @endif
                                                     @if($goods->status == 1) <span>有效</span> @endif
                                        </li>
                                        <li>淘口令:{{ $goods->tao_kou_ling }}</li>
                                    </ul>
                                </td>
                                <td class="text-center">{{ $goods->reserve_price }}</td>
                                <td class="text-center">{{ $goods->zk_final_price }}</td>
                                <td class="text-center">{{ $goods->zk_final_price_wap }}</td>
                                <td class="text-center">{{ $goods->tk_rate }}%</td>
                                <td class="text-center">{{ $goods->money }}</td>
                                <td class="text-center">{{ $goods->volume }}</td>
                                <td class="text-center">
                                    @if($goods->user_type == "1") <img src="/admin/style/img/tmallfavicon.ico">  @endif
                                    @if($goods->user_type == "0") <img src="/admin/style/img/taobaofavicon.ico"> @endif
                                </td>
                                <td class="text-center">
                                	<a href="{{ route('favoritesItemDeleteByItemId', ['id'=>$goods->id]) }}"><i class="fa fa-close text-navy" title="删除选品库商品" style="color:red;"></i></a> |
                                	@if($goods->is_recommend == '否')
                                	<a href="{{ route('favoritesItemRecommendItemByItemId', ['id'=>$goods->id]) }}"><i class="fa fa-hand-o-up text-navy" title="推荐" style="color:green;"></i></a>
                                	@endif
                                	@if($goods->is_recommend == '是')
                                	<a href="{{ route('favoritesItemNotRecommendItemByItemId', ['id'=>$goods->id]) }}"><i class="fa fa-hand-o-down text-navy" title="取消推荐" style="color:red;"></i></a>
                                	@endif
                                </td>
                            </tr>
                            @endforeach 

                        </tbody>
                    </table>
                    @if(!count($goodsInfo))
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
			form.action = '{{ route('favoritesItemDeleteByItemIds') }}';
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('favoritesItemRecommendItemByItemIds') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 3) {
			form.action = "{{ route('favoritesItemNotRecommendItemByItemIds') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
	}

</script>
      <!-- 分页 -->
      <div class="row text-center">
          <nav aria-label="Page navigation">
            {!! $goodsInfo->appends($oldRequest)->render() !!}
          </nav>
      </div>
            </div>
            </form>
        </div>
    </div>

</div>
@endsection