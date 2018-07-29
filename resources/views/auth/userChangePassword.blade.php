{{-- 继承整体框架 --}}
@extends('adminLayouts.form')

{{-- title --}}
@section('title')
<title>重置密码 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>密码重置</h5>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" method="POST" action="{{ route('changepwdaction') }}">

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
				    	<p>密码修改成功！</p>
				    </div>
				    @endif

                    @if (!empty(session('status')) && session('status') == 'failed')
                    <div class="form-group text-center">
                        <p>输入的原始密码错误，请重新输入！</p>
                    </div>
                    @endif

                    @if (!empty(session('status')) && session('status') == 'diff')
                    <div class="form-group text-center">
                        <p>两次输入的密码不一致！</p>
                    </div>
                    @endif

				    <input type="hidden" name="id" value="{{ $user->id }}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">登录邮箱：</label>

                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">原始密码：</label>

                        <div class="col-sm-8">
                            <input type="password" name="password_original" placeholder="请输入原始密码" required class="form-control"> 
                            <span class="help-block m-b-none">请输入您现在的密码...</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">新密码：</label>

                        <div class="col-sm-8">
                            <input type="password" name="password_new" placeholder="请输入新密码" required class="form-control">
                            <span class="help-block m-b-none">密码由至少六位组成...</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">确认密码：</label>

                        <div class="col-sm-8">
                            <input type="password" name="password_confirm" placeholder="再次输入密码" required class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button class="btn btn-sm btn-deafault" type="submit">修 改</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>
@endsection