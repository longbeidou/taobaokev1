{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>栏目列表 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>栏目列表</h5>
                <div class="ibox-tools">
                    <a class="dropdown-toggle"  href="{{route('adminCategoryAdd')}}">
                        <i class="fa fa-plus-circle"></i> 添加栏目  | 
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i> 每页显示信息数
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('adminCategoryIndex',['pageSize'=>10])}}">每页显示10条信息</a></li>
                        <li><a href="{{route('adminCategoryIndex',['pageSize'=>15])}}">每页显示15条信息</a></li>
                        <li><a href="{{route('adminCategoryIndex',['pageSize'=>20])}}">每页显示20条信息</a></li>
                        <li><a href="{{route('adminCategoryIndex',['pageSize'=>25])}}">每页显示25条信息</a></li>
                        <li><a href="{{route('adminCategoryIndex',['pageSize'=>30])}}">每页显示30条信息</a></li>
                    </ul>
                </div>
            </div>
            <form action="" method="post" id="couponList">
            	{{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">
                    @if(!empty(session('statusSuccess')))
                    <p style="color:green;">{{session('statusSuccess')}}</p>
                    @endif

                    @if(!empty(session('statusFaild')))
                    <p style="color:red;">{{session('statusFaild')}}</p>
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
                                <th>排序</th>
                                <th>栏目名称</th>
                                <th>推广位ID</th>
                                <th>所属模型</th>
                                <th class="text-center">是否显示</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                            <input type="hidden" name="from" value="99">
                        	@foreach($info as $category)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="ids[]" value="{{ $category->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>
                                    <input type="number" min='0' max="999" step="1" name="order_self[{{$category->id}}]" value="{{$category->order_self}}">
                                </td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->adzone_id }}</td>
                                <td>
                                    @if ($category->form == '0') <span style="color:red;">未分配</span> @endif
                                    @if ($category->form == '1') 选品库列表 @endif
                                    @if ($category->form == '2') 定向招商的活动列表 @endif
                                    @if ($category->form == '3') 精选优质商品清单（Excel） @endif
                                    @if ($category->form == '4') 官方精选热推清单（最高佣金50%） @endif
                                </td>
                                <td class="text-center" @if($category->is_show == '否') style="color:red;" @else style="color:green;" @endif>{{ $category->is_show }}</td>
                                <td class="text-center">
                                    <a href="{{ route('adminCategoryUpdate', ['id'=>$category->id]) }}" style="color:green;"><i class="fa fa-edit"></i> 编辑</a> |
                                    <a href="{{ route('adminCategoryDeleteById', ['id'=>$category->id]) }}" style="color:red;"><i class="fa fa-close"></i> 删除</a>                                
                                </td>
                            </tr>
                            @endforeach 

                        </tbody>
                    </table>
                    @if(!count($info))
                    <h3>暂时没有栏目信息</h3>
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
			form.action = "{{ route('adminCategoryDeleteByIds') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('adminCategoryUpdateOrderselfs') }}";
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