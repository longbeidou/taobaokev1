{{-- 继承整体框架 --}}
@extends('adminLayouts.form')

{{-- title --}}
@section('title')
<title>编辑选品库列表信息 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-6 col-sm-offset-3">
		<div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>编辑选品库列表信息</h5>
            </div>
            <div class="ibox-content">

                <form class="form-horizontal" action="{{route('favoritesUpdateById')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$info['id']}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">排序：</label>

                        <div class="col-sm-8">
                            <input type="number" name="order_self" value="@if(!empty($info['order_self'])){{$info['order_self']}}@endif" min="0" max="999" step="1">
                            <p>数值越小越靠前</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">是否显示：</label>

                        <div class="col-sm-8">
                            <div class="radio">
                                <label>
                                    <input type="radio" @if($info['is_show'] == '是') checked @endif value="是" id="optionsRadios1" name="is_show">显示</label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" @if($info['is_show'] == '否') checked @endif value="否" id="optionsRadios2" name="is_show">不显示</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">自定义名称：</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="name" value="{{$info['name']}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button class="btn btn-sm btn-white" type="submit">修 改</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
	</div>

</div>
@endsection