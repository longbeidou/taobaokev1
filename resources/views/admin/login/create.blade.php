










{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
<style type="text/css">
	input, textarea, select, .uneditable-input {
	  border: 1px solid #bbb;
	  width: 100%;
	  margin-bottom: 15px;
	}

	input {
	  height: auto !important;
	}

	.panel {
	  margin-top: 50px;
	}
</style>
<div class="col-md-offset-2 col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h5>注册</h5>
    </div>
    <div class="panel-body">
      <form method="POST" action="">
          <div class="form-group">
            <label for="name">名称：</label>
            <input type="text" name="name" class="form-control" value="">
          </div>

          <div class="form-group">
            <label for="email">邮箱：</label>
            <input type="text" name="email" class="form-control" value="">
          </div>

          <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" name="password" class="form-control" value="">
          </div>

          <div class="form-group">
            <label for="password_confirmation">确认密码：</label>
            <input type="password" name="password_confirmation" class="form-control" value="">
          </div>

          <button type="submit" class="btn btn-primary">注册</button>
      </form>
    </div>
  </div>
</div>
@endsection