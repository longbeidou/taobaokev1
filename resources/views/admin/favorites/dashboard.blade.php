{{-- 继承整体框架 --}}
@extends('adminLayouts.form')

{{-- title --}}
@section('title')
<title>选品库列表控制面板 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-6">
		<div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>将淘宝的选品库列表信息导入数据库</h5>
            </div>
            <div class="ibox-content">

			    @if (count($errors) > 0)
			        <ul class="text-left list-unstyled">
			            @foreach ($errors->all() as $error)
			                <li class="text-left">{{ $error }}</li>
			            @endforeach
			        </ul>
			    @endif

			    @if (!empty(session('insertAllStatus')) && session('insertAllStatus') == 'success')
			    <div class="form-group text-center">
			    	<p style="color:green;">批量入库成功！</p>
			    </div>
			    @endif

                @if (!empty(session('insertAllStatus')) && session('insertAllStatus') == 'faild')
                <div class="form-group text-center">
                    <p style="color:red;">批量入库失败失败！</p>
                </div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>编号</th>
                            <th>选品库类型</th>
                            <th>选品库id</th>
                            <th>选品组名称</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $code = 1; ?>
                        @foreach($favoritesInfo as $favorites)
                        <tr>
                            <td>
                                <?php 
                                    if($pageno == 1) {
                                        echo $code;
                                        $code++;
                                    } else {
                                        $n = ($pageno-1)*$pageSize + $code;
                                        $code++;
                                        echo $n;
                                    }
                                ?>
                            </td>
                            <td>
                                @if($favorites->type == 1)
                                  普通类型({{ $favorites->type}})
                                @else
                                  高佣金类型（{{ $favorites->type}}）
                                @endif
                            </td>
                            <td>{{ $favorites->favorites_id }}</td>
                            <td>{{ $favorites->favorites_title }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-center">
                                <?php for($i=1; $i<($num/$pageSize+1); $i++){ ?> 
                                <a class="btn @if((!empty($pageno) && $pageno==$i) || (empty($pageno) && $i == 1)) 
                                                btn-default 
                                              @endif" href="{{ route('favoritesDashboard', ['pageno'=>$i]) }}">{{$i}}</a>
                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <a href="{{route('favoritesInsertAll')}}" class="btn btn-primary">全部入库</a>
                    </div>
                </div>

                <div class="row">
                    <p style="color:red;">
                        1.入库前请检查【全部入库】按钮的上方是否有选品库的列表，如果有可以选择全部入库，如果没有显示，请不要选择！<br />
                        2.入库需要一定的时间，需要等待几分钟，请不要刷新页面！
                    </p>
                </div>
            </div>
        </div>
	</div>

    <div class="col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>批量处理选品库数据库的数据</h5>
            </div>
            <div class="ibox-content">
                <!-- 清空数据库的操作 -->
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除数据库的所有选品库列表信息</h3>
                @if (!empty(session('status')) && session('status') == 'confireCodeWrong')
                <div class="form-group text-center">
                    <p style="color:red;">删除确认码错误！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteAllSuccess')
                <div class="form-group text-center">
                    <p style="color:green;">成功删除所有选品库列表！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteAllFaild')
                <div class="form-group text-center">
                    <p style="color:red;">未删除所有选品库列表！</p>
                </div>
                @endif
                <form role="form" method="GET" action="{{route('favoritesDeleteAll')}}" class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">删除确认码：</label>
                        <input type="text" value="alldelte001" class="form-control">
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
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除特定日期的所有选品库列表信息</h3>
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
                @if (!empty(session('status')) && session('status') == 'delteDateSuccess')
                <div class="form-group text-center">
                    <p style="color:green;">成功删除对应日期的选品库列表！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteDateFailed')
                <div class="form-group text-center">
                    <p style="color:red;">未成功删除对应日期的选品库列表！</p>
                </div>
                @endif
                <form role="form" method="GET" action="{{route('favoritesDeleteManyDate')}}" class="form-inline">
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

<!-- 数据库的选品库列表信息 -->
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>选品库列表</h5>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i> 每页显示信息数
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{route('favoritesDashboard',['pageSize2'=>20])}}">每页显示20条信息</a></li>
                        <li><a href="{{route('favoritesDashboard',['pageSize2'=>40])}}">每页显示40条信息</a></li>
                        <li><a href="{{route('favoritesDashboard',['pageSize2'=>60])}}">每页显示60条信息</a></li>
                        <li><a href="{{route('favoritesDashboard',['pageSize2'=>80])}}">每页显示80条信息</a></li>
                        <li><a href="{{route('favoritesDashboard',['pageSize2'=>100])}}">每页显示100条信息</a></li>
                    </ul>
                </div>
            </div>
            <form action="" method="post" id="couponList">
                {{ csrf_field() }}
            <div class="ibox-content" style="display: block;">
                <div class="table-responsive">
                    @if(!empty(session('actionStatus')))
                    <p style="color:green;">{{session('actionStatus')}}</p>
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
                                <th>选品库列表类型</th>
                                <th>选品库id</th>
                                <th>选品组名称</th>
                                <th>包含的商品数</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody id="chk">
                            <input type="hidden" name="from" value="99">
                            @foreach($info2 as $category)
                            <tr>
                                <td>
                                   <input type="checkbox"   name="ids[]" value="{{ $category->id }}" style="width:20px; height:20px; " checked>
                                </td>
                                @if($category->type == '1') <td style="color:green;">普通类型</td> @endif
                                @if($category->type == '2') <td style="color:red;">高佣金类型</td> @endif
                                <td>{{ $category->favorites_id }}</td>
                                <td>{{ $category->favorites_title }}</td>
                                <td>{{ $count[$category->favorites_id]}}</td>
                                <td>{{ $category->created_at }}</td>
                                <td>{{ $category->updated_at }}</td>
                                <td class="text-center">
                                    <a href="{{ route('favoritesDeleteId', ['id'=>$category->id]) }}" style="color:red;"><i class="fa fa-close"></i> 删除</a>                                
                                </td>
                            </tr>
                            @endforeach 

                        </tbody>
                    </table>
                    @if(!count($info2))
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
                                    <!-- <button type="submit" class="btn btn-xs btn-primary" onclick="submitChoice(2)"><i class="fa fa-hand-o-up text-navy" style="color:#fff;"></i> 批量修改排序</button> -->
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
              {!! $info2->appends($oldRequest)->render() !!}
          </nav>
      </div>
            </div>
            </form>
        </div>
    </div>

</div>
@endsection