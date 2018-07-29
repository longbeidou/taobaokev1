{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>栏目对应的选品库列表 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>栏目</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <ul class="list-inline">
                    @if (!count($category))
                        <span style="color:red;">选品库列表没有分配栏目，请先分配栏目！</span>
                    @else
                        @if (count($category) > 1)
                        <li>
                            <a href="{{ route('favoritesList', ['categoryId'=>0]) }}" class="btn
                                                                                            @if($categoryId == 0)
                                                                                                btn-warning
                                                                                            @else
                                                                                                btn-primary
                                                                                            @endif
                                                                                            ">全部</a>
                        </li>
                        @endif
                        @foreach($category as $k=>$c)
                        <li>
                            <a href="{{ route('favoritesList', ['categoryId'=>$c['id']]) }}" class="btn 
                                                                                                    @if($categoryId == $c['id'] || (count($category) ==1))
                                                                                                        btn-warning
                                                                                                    @else
                                                                                                        btn-primary
                                                                                                    @endif
                                                                                                    ">{{ $c['name'] }}</a>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div><!-- ibox-content end -->
        </div><!-- ibox end -->
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                    @if(!$categoryId)
                        选品库全部的列表
                    @else
                        {{ $categoryName[$categoryId]}}的选品库列表
                    @endif
                </h5>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i> 每页显示信息数
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('favoritesList',['pageSize'=>10, 'categoryId'=>$categoryId])}}">每页显示10条信息</a></li>
                        <li><a href="{{route('favoritesList',['pageSize'=>15, 'categoryId'=>$categoryId])}}">每页显示15条信息</a></li>
                        <li><a href="{{route('favoritesList',['pageSize'=>20, 'categoryId'=>$categoryId])}}">每页显示20条信息</a></li>
                        <li><a href="{{route('favoritesList',['pageSize'=>25, 'categoryId'=>$categoryId])}}">每页显示25条信息</a></li>
                        <li><a href="{{route('favoritesList',['pageSize'=>30, 'categoryId'=>$categoryId])}}">每页显示30条信息</a></li>
                    </ul>
                </div>
            </div>
            <form action="" method="post" id="couponList">
            	{{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">
                    @if(!empty(session('listStatus')))
                    <p style="color:green;">{{ session('listStatus') }}</p>
                    @endif

                    @if(session('status') == 'delidsuccess')
                    <p style="color:green;">成功删除一条数据！</p>
                    @endif
                    @if(session('status') == 'delidfailed')
                    <p style="color:red;">删除信息失败！</p>
                    @endif

                    @if(session('status') == 'delidssuccess')
                    <p style="color:green;">批量删除数据成功！</p>
                    @endif
                    @if(session('status') == 'delidsfailed')
                    <p style="color:red;">批量删除信息失败！</p>
                    @endif

                    @if(session('status') == 'updateSuccess')
                    <p style="color:green;">批量更新数据成功！</p>
                    @endif
                    @if(session('status') == 'updateFailed')
                    <p style="color:red;">批量更新信息失败！</p>
                    @endif

                    @if(session('status') == 'updateByIdSuccess')
                    <p style="color:green;">编辑自定义名称成功！</p>
                    @endif
                    @if(session('status') == 'updateByIdFailed')
                    <p style="color:red;">编辑自定义名称失败！</p>
                    @endif

                    @if(session('status') == 'updateOrderSuccess')
                    <p style="color:green;">批量更新排序成功！</p>
                    @endif
                    @if(session('status') == 'updateFailed')
                    <p style="color:red;">批量更新排序失败！</p>
                    @endif

                    @if(count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $error)
                            <li style="color:red;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>

                                <th></th>
                                <th>
                                    排序
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'orderSelfasc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'orderSelfasc') red @endif"><i class="glyphicon glyphicon-arrow-up  "></i></a>
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'orderSelfdesc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'orderSelfdesc') red @endif"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                                <th>
                                    选品库类型
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'typeasc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'typeasc') red @endif"><i class="glyphicon glyphicon-arrow-up  "></i></a>
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'typedesc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'typedesc') red @endif"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                                <th>
                                    选品库id
                                </th>
                                <th>
                                    选品组名称
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'favoritestitleasc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'favoritestitleasc') red @endif"><i class="glyphicon glyphicon-arrow-up  "></i></a>
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'favoritestitledesc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'favoritestitledesc') red @endif"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                                <th>
                                    自定义名称
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'nameasc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'nameasc') red @endif"><i class="glyphicon glyphicon-arrow-up  "></i></a>
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'namedesc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'namedesc') red @endif"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                                <th class="text-center">
                                    商品总数
                                </th>
                                <th class="text-center">
                                    是否显示
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'isshowasc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'isshowasc') red @endif"><i class="glyphicon glyphicon-arrow-up  "></i></a>
                                    <a href="{{route('favoritesList',['pageSize'=>$pageSize, 'categoryId'=>$categoryId, 'order'=>'isshowdesc'])}}" style="color:@if(!empty($oldRequest['order']) && $oldRequest['order'] == 'isshowdesc') red @endif"><i class="glyphicon glyphicon-arrow-down"></i></a>
                                </th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                            <input type="hidden" name="from" value="20">
                        	@foreach($info as $key=>$list)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="ids[]" value="{{ $list->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>
                                    <input type="number" name="order[{{$list->id}}]" value="{{$list->order_self}}" min="0" max="999" step="1">
                                </td>
                                <td>
                                    @if($list->type == 1) <span style="color:green;">普通类型</span> @endif
                                    @if($list->type == 2) <span style="color:red;">高佣金类型</span> @endif
                                </td>
                                <td>{{ $list->favorites_id }}</td>
                                <td>{{ $list->favorites_title }}</td>
                                <td>{{$list->name}}</td>
                                <td class="text-center">{{ $goodsTotal[$list->favorites_id] }}</td>
                                <td class="text-center" style="color:@if($list->is_show=='是')green;@else red; @endif;">{{$list->is_show}}</td>
                                <td class="text-center">
                                    <a href="{{ route('favoritesEditId', ['id'=>$list->id]) }}" style="color:green;"><i class="fa fa-edit"></i> 编辑</a> |
                                    <a href="{{ route('favoritesDeleteId', ['id'=>$list->id]) }}" style="color:red;"><i class="fa fa-close"></i> 删除</a>                                
                                </td>
                            </tr>
                            @endforeach 

                        </tbody>
                    </table>
                    @if(!count($info))
                    <h3>暂时没有选品库列表信息</h3>
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
                    				<button type="submit" class="btn btn-xs btn-primary" onclick="submitChoice(2)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 批量修改排序</button>
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
			form.action = '{{ route('favoritesDeleteIds') }}';
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('favoritesUpdateOder') }}";
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