{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>选品库的宝贝信息入库 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
    <div class="col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>将淘宝的选品库宝贝信息导入数据库</h5>
            </div>
            <div class="ibox-content">

                @if (count($errors) > 0)
                    <ul class="text-left list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li class="text-left">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (!empty(session('updateByFavoritesIdsAllStatus')))
                <div class="form-group text-left">
                    <p style="color:green;">{{session('updateByFavoritesIdsAllStatus')}}</p>
                </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>产地</th>
                            <th>价格</th>
                            <th>店铺名</th>
                            <th>商品id</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($infoAllInsert as $info)
                        <tr>
                            <td>{{ $info['provcity'] }}</td>
                            <td>{{ $info['reserve_price'] }}</td>
                            <td>{{ $info['shop_title'] }}</td>
                            <td>{{ $info['num_iid'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a href="{{route('favoritesItemUpdateByFavoritesIdsAll')}}" class="btn btn-primary">全部入库</a>
                    </div>
                </div>

                <div class="row">
                    <p style="color:red;">
                        1.入库前请检查【全部入库】按钮的上方是否有选品库的宝贝信息，如果有可以选择全部入库，如果没有显示，请不要选择！<br />
                        2.入库需要一定的时间，需要等待几分钟，请不要刷新页面！
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>批量处理选品库宝贝信息的数据</h5>
            </div>
            <div class="ibox-content">
                <!-- 清空数据库的操作 -->
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除数据库的所有选品库的宝贝信息</h3>
                @if (!empty(session('confirmCodeStatus')))
                <div class="form-group text-center">
                    <p style="color:red;">{{session('confirmCodeStatus')}}</p>
                </div>
                @endif

                @if (!empty(session('deleteAllstatus')))
                <div class="form-group text-center">
                    <p style="color:green;">{{session('deleteAllstatus')}}</p>
                </div>
                @endif

                <form role="form" method="GET" action="{{route('favoritesItemDeleteAll')}}" class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">删除确认码：</label>
                        <input type="text" value="alldelte002" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="请输入左侧的删除确认码" id="" name="confirmCode" required class="form-control">
                    </div>
                    <button class="btn btn-warning" type="submit">全部删除</button>
                </form>
                <p style="text-indent:20px; color:red;">请慎重操作，此操作不可恢复...</p>
            </div>

            <div class="ibox-content">
                <!-- 清空数据库的操作 -->
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除特定日期的所有选品库的宝贝信息</h3>
                @if (!empty(session('status')) && session('status') == 'dateBeginEmpty')
                <div class="form-group text-center">
                    <p style="color:red;">起始日期不能为空！</p>
                </div>
                @endif

                @if (!empty(session('status')) && session('status') == 'dateEndEmpty')
                <div class="form-group text-center">
                    <p style="color:red;">截止日期不能为空！</p>
                </div>
                @endif

                @if (!empty(session('deleteFromDateStatus')))
                <div class="form-group text-center">
                    <p style="color:green;">{{session('deleteFromDateStatus')}}</p>
                </div>
                @endif

                <form role="form" method="GET" action="{{route('favoritesItemDeleteFromDate')}}" class="form-inline">
                    <div class="form-group">
                        <label for="date1" class="">起始日期：</label>
                        <input type="date" id="date1" name="datebegin" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date2" class="">截止日期：</label>
                        <input type="date" id="date2" name="dateend" required class="form-control">
                    </div>
                    <button class="btn btn-warning" type="submit">删除</button>
                </form>
                <span style="color:red;">请慎重操作，此操作不可恢复...</sapn><br />
                <span style="color:#333;">起始日期的时间按照 00:00:00 算。</sapn><br />
                <span style="color:#333;">截止日期的时间按照 23:59:59 算。</sapn><br />
            </div>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>选品库宝贝信息入库面板</h5>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i> 每页显示信息数
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('favoritesItemDashboard',['pageSize'=>50])}}" >每页显示50条信息</a></li>
                        <li><a href="{{route('favoritesItemDashboard',['pageSize'=>100])}}">每页显示100条信息</a></li>
                        <li><a href="{{route('favoritesItemDashboard',['pageSize'=>150])}}">每页显示150条信息</a></li>
                        <li><a href="{{route('favoritesItemDashboard',['pageSize'=>200])}}">每页显示200条信息</a></li>
                    </ul>
                </div>
            </div>
            <form action="" method="post" id="couponList">
            	{{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">

                    @if (!empty(session('updateByFavoritesIdStatus')))
                    <div class="form-group text-left">
                        <p style="color:green;">{{session('updateByFavoritesIdStatus')}}</p>
                    </div>
                    @endif

                    @if (!empty(session('updateByFavoritesIdsSomeStatus')))
                    <div class="form-group text-left">
                        <p style="color:green;">{{session('updateByFavoritesIdsSomeStatus')}}</p>
                    </div>
                    @endif

                    @if (!empty(session('list')))
                    <div class="form-group text-left">
                        <p style="color:green;">{{session('list')}}</p>
                    </div>
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
                                <th>选品库列表</th>
                                <th>本地宝贝数量</th>
                                <th>远程宝贝数量</th>
                                <th>今日更新宝贝数量</th>
                                <th>本地无效宝贝数量</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                        	@foreach($infoAll as $key=>$list)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="ids[]" value="{{$list['favorites_id']}}" style="width:20px; height:20px; " checked>
                                </td>
                                <td>{{ $list['favorites_title'] }}</td>
                                <td>{{ $list['local_total'] }}</td>
                                <td>{{ $list['total_results'] }}</td>
                                <td>{{ $list['update_total'] }}</td>
                                <td>{{ $list['status_off_total'] }}</td>
                                <td class="text-center">
                                    <a href="{{ route('favoritesItemDeleteByFavoritesId', ['favoritesId'=>$list['favorites_id']])}}" title="删除所有的宝贝信息"       style="color:red;"   ><i class="fa fa-trash" ></i> 清空</a> | 
                                    <a href="{{ route('favoritesItemUpdateByFavoritesId', ['favoritesId'=>$list['favorites_id'], 'category'=>$list['category']]) }}" title="更新所有的宝贝信息"       style="color:green;" ><i class="fa fa-clone" ></i> 更新入库</a> | 
                                    <a href="{{ route('favoritesItemDeleteStatusOffFavoriteId', ['favoritesId'=>$list['favorites_id']]) }}" title="删除无效的宝贝信息"       style="color:orange;"><i class="fa fa-close" ></i> 删除无效</a> |
                                    <a href="{{ route('favoritesItemDeleteUpdateNoTodayByFid', ['favoritesId'=>$list['favorites_id']]) }}" title="删除今日无更新的宝贝信息" style="color:blue;"  ><i class="fa fa-eraser"></i> 删除无更新</a>
                                </td>  
                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                <!--     if(!count($info))
                    <h3>暂时没有选品库列表信息</h3>
                    endif -->
                    <table class="table table-striped">
                    	<tbody>
                    		<tr class="info">
                    			<td>
                    				<span type="" class="btn btn-xs btn-info"    onclick="chk(1)">全选  </span>
                    				<span type="" class="btn btn-xs btn-primary" onclick="chk(2)">反选  </span>
                    				<span type="" class="btn btn-xs btn-success" onclick="chk(3)">全不选</span>
                    				<span>|</span>
                    				<button type="submit" class="btn btn-xs btn-info"    onclick="submitChoice(1)"><i class="fa fa-close     text-navy" style="color:#fff;"></i> 删除选中的宝贝信息</button>
                    				<button type="submit" class="btn btn-xs btn-primary" onclick="submitChoice(2)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 更新选中的宝贝信息</button>
                                    <button type="submit" class="btn btn-xs btn-warning" onclick="submitChoice(3)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 删除选中今日无更新的宝贝信息</button>
                                    <button type="submit" class="btn btn-xs btn-danger"  onclick="submitChoice(4)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 删除选中无效的宝贝信息</button>
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
			form.action = '{{ route('favoritesItemDeleteByFavoritesIds') }}';
			$("#couponList").attr('action', form.action);
			form.submit();
		}
		if (value == 2) {
			form.action = "{{ route('favoritesItemUpdateByFavoritesIdsSome') }}";
			$("#couponList").attr('action', form.action);
			form.submit();
		}
        if (value == 3) {
            form.action = "{{ route('favoritesItemDeleteUpdateNoToday') }}";
            $("#couponList").attr('action', form.action);
            form.submit();
        }
        if (value == 4) {
            form.action = "{{ route('favoritesItemDeleteStatusOff') }}";
            $("#couponList").attr('action', form.action);
            form.submit();
        }
	}

</script>
      <!-- 分页 -->
      <div class="row text-center">
          <nav aria-label="Page navigation">
              {!! $favoritesCategoryInfo->appends($oldRequest)->render() !!}
          </nav>
      </div>
            </div>
            </form>
        </div>
    </div>

</div>
@endsection