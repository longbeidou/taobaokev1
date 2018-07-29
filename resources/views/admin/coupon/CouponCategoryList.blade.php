{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>优惠券分类列表 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-12">
		@if (!empty(session('status')) && session('status') == 'addSuccess')
			<p style="color:green;">添加优惠券分类成功！</p>
		@endif
        @if (!empty(session('status')) && session('status') == 'changeOrderSuccess')
            <p style="color:green;">修改分类排序成功！</p>
        @endif
        @if (!empty(session('status')) && session('status') == 'delManySuccess')
            <p style="color:green;">删除商品分类成功！</p>
        @endif
        @if (!empty(session('status')) && session('status') == 'delOneSuccess')
            <p style="color:green;">删除商品分类成功！</p>
        @endif
        @if (!empty(session('status')) && session('status') == 'isShowSuccess')
            <p style="color:green;">设置分类显示成功！</p>
        @endif
        @if (!empty(session('status')) && session('status') == 'editSuccess')
            <p style="color:green;">编辑分类信息成功！</p>
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
            <div class="ibox-title">
                <h5>优惠券分类列表</h5>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" href="{{ route('couponCategoryAdd') }}">
                        <i class="fa fa-plus-circle"></i> 添加分类  | 
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i> 每页显示信息数
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('couponCategoryList',['pageNumber'=>10])}}">每页显示10条信息</a></li>
                        <li><a href="{{route('couponCategoryList',['pageNumber'=>15])}}">每页显示15条信息</a></li>
                        <li><a href="{{route('couponCategoryList',['pageNumber'=>20])}}">每页显示20条信息</a></li>
                        <li><a href="{{route('couponCategoryList',['pageNumber'=>25])}}">每页显示25条信息</a></li>
                        <li><a href="{{route('couponCategoryList',['pageNumber'=>30])}}">每页显示30条信息</a></li>
                    </ul>
                </div>
            </div>
            <form action="" method="post" id="couponList">
            	{{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>

                                <th></th>
                                <th>排序</th>
                                <th>分类名称</th>
                                <th class="text-center">商品总数</th>
                                <th>分类条件字符串</th>
                                <th class="text-center">是否显示</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                            <?php if(count($info)) { ?>
                        	@foreach($info as $category)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="categoryIdArray[]" value="{{ $category->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>
                                	<input type="number" min="0" max="99" step="1" name="order[{{ $category->id }}]" value="{{$category->order}}" />
                                </td>
                                <td><a href="{{ route('homeGoodsListCategory',['categoryId' => '1', 'cate'=>$category->self_where]) }}" target="_blank">{{ $category->category_name }}</a></td>
                                <td class="text-center" style="color:@if(!$count[$category->id]) red; @endif">{{ $count[$category->id] }}</td>
                                <td>{{ $category->self_where }}</td>
                                <td class="text-center">
                                    @if($category->is_show == "是") <span style="color:green;">是</span>  @endif
                                    @if($category->is_show == "否") <span style="color:red">否</span>  @endif
                                </td>
                                <td class="text-center">

                                    <a href="{{ route('couponCategoryEdit',['id'=>$category->id]) }}"><i class="fa fa-edit text-navy"  style="color:green;"></i> 编辑</a> |
                                    <a href="{{ route('couponCategoryDel') }}?id={{ $category->id }}"><i class="fa fa-close text-navy"  style="color:red;"></i> 删除</a> |
                                	@if($category->is_show == "是")
                                	<a href="{{ route('couponCategoryIsShow') }}?isshow=no&id={{ $category->id }}"><i class="fa fa-arrow-down text-navy"  style="color:red;"></i> 取消显示</a>
                                	@endif
                                	@if($category->is_show == "否")
                                	<a href="{{ route('couponCategoryIsShow') }}?isshow=yes&id={{ $category->id }}"><i class="fa fa-arrow-up text-navy"  style="color:green;"></i> 设置显示</a>
                                	@endif
                                </td>
                            </tr>
                            @endforeach 
                            <?php } ?>
                        </tbody>
                    </table>
                    @if(!count($info))
                    <h3>暂时没有商品分类</h3>
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
                    				<button type="submit" class="btn btn-xs btn-primary" onclick="submitChoice(2)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 修改排序</button>
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
			form.action = '{{ route('couponCategoryDelMany') }}';
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('couponCategoryChangeOrder') }}";
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