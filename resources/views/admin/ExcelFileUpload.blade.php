{{--继承整体框架--}}
@extends('home.layouts.Master')

{{--head部分--}}
@section('headself'){{--head可以增加js、css等文件的地方--}}

@endsection

{{--body部分的内容--}}
@section('content')
<form action="/exceluploadaction" method="post" enctype="multipart/form-data">
	{{ csrf_field() }}
	<input name="name" type="text" />
	<input name="info" type="file" />
	<input type="submit" value="提交" />
</form>
@endsection