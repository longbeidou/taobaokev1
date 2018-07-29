{{-- 继承整体框架 --}}
@extends('adminLayouts.form')

{{-- title --}}
@section('title')
<title>上传Excel优惠券文件 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-6">
		<div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>将Excel优惠券文件的信息导入数据库</h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" method="POST" action="{{ route('couponExceluploadaction') }}" enctype="multipart/form-data">

				    {!! csrf_field() !!}
				    @if (count($errors) > 0)
				        <ul class="text-left list-unstyled">
				            @foreach ($errors->all() as $error)
				                <li class="text-left">{{ $error }}</li>
				            @endforeach
				        </ul>
				    @endif

				    @if (!empty(session('status')) && session('status') == 'success')
				    <div class="form-group text-center">
				    	<p style="color:green;">上传文件成功！</p>
				    </div>
				    @endif

                    @if (!empty(session('status')) && session('status') == 'failed')
                    <div class="form-group text-center">
                        <p style="color:red;">上传文件失败！</p>
                    </div>
                    @endif

                    @if (!empty(session('status')) && session('status') == 'file')
                    <div class="form-group text-center">
                        <p style="color:red;">上传文件有误，请把Excel文件的首行进行修改，改成从1开始逐次增加到22！</p>
                    </div>
                    @endif

                    @if (!empty(session('status')) && session('status') == 'fileFormatDiff')
                    <div class="form-group text-center">
                        <p style="color:red;">上传文件的格式有误。请上传.xls后缀的Excel文件。</p>
                    </div>
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label">选择Excel文件：</label>

                        <div class="col-sm-8">
                            <input type="file" name="info" required class="form-control"> 
                            <span class="help-block m-b-none">请从本地选择优惠券的Excel文件...</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button class="btn btn-sm btn-deafault" type="submit">上 传</button>
                        </div>
                    </div>
                </form>
                <div>
                    <p style="color:red;">
                        1.上传文件前，请务必将优惠券Excel文件的第一行进行修改，改成从1开始，逐步加1，到22结束！<br />
                        2.上传文件需要一定的时间，需要等待几分钟，请不要刷新页面！
                    </p>
                </div>
            </div>
        </div>
	</div>

    <div class="col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>批量处理数据库的数据</h5>
            </div>
            <div class="ibox-content">
                <!-- 清空数据库的操作 -->
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除数据库所有优惠券信息</h3>
                @if (!empty(session('status')) && session('status') == 'confireCodeWrong')
                <div class="form-group text-center">
                    <p style="color:red;">删除确认码错误！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteAllSuccess')
                <div class="form-group text-center">
                    <p style="color:green;">成功删除所有优惠券！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteAllFaild')
                <div class="form-group text-center">
                    <p style="color:red;">未删除所有优惠券！</p>
                </div>
                @endif
                <form role="form" method="GET" action="{{route('couponDeleteAll')}}" class="form-inline">
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
                <h3 class="text-center"><i class="glyphicon glyphicon-hand-right"></i> 删除特定日期的所有优惠券信息</h3>
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
                    <p style="color:green;">成功删除对应日期的优惠券！</p>
                </div>
                @endif
                @if (!empty(session('status')) && session('status') == 'delteDateFailed')
                <div class="form-group text-center">
                    <p style="color:red;">未成功删除对应日期的优惠券！</p>
                </div>
                @endif
                <form role="form" method="GET" action="{{route('couponDeleteDate')}}" class="form-inline">
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
@endsection