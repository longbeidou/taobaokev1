{{-- 继承整体框架 --}}
@extends('adminLayouts.form')

{{-- title --}}
@section('title')
<title>修改栏目 - {{ $title or config('website.name')}}</title>
@endsection


{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>修改栏目</h5>
                <div class="ibox-tools">
                    <a href="{{ route('adminCategoryIndex')}}" class="collapse-link">
                        <i class="fa fa-list-ul"></i> 商品栏目列表
                    </a>
                </div>
            </div>
            <div class="ibox-content">
            	@if(count($errors) > 0)
            		<ul>
            			@foreach($errors->all() as $error)
            			<li style="color:red;">{{ $error }}</li>
            			@endforeach
            		</ul>
            	@endif

            	@if(!empty(session('status')))
            		<ul>
            			<li style="color:green;">{{session('status')}}</li>
            		</ul>
            	@endif

                <form method="post" action="{{ route('adminCategoryUpdateAction') }}" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$categoryInfo->id}}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">栏目名称：</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" value="{{$categoryInfo->name}}" required class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">推广位ID：</label>
                        <div class="col-sm-10">
                            <input type="text" name="adzone_id" value="{{$categoryInfo->adzone_id}}" required class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">所属模型</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b" name="form">
                                <option @if($categoryInfo->form == "0") selected @endif value="0">------------未分配------------</option>
                                <option @if($categoryInfo->form == "1") selected @endif value="1">选品库列表</option>
                                <option @if($categoryInfo->form == "2") selected @endif value="2">定向招商的活动列表</option>
                                <option @if($categoryInfo->form == "3") selected @endif value="3">精选优质商品清单（Excel）</option>
                                <option @if($categoryInfo->form == "4") selected @endif value="4">官方精选热推清单（最高佣金50%）</option>
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">排序：</label>
                        <div class="col-sm-10">
                            <input type="number" min="0" max="999" step="1" name="order_self" value="{{$categoryInfo->order_self}}"  required class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">是否显示</label>

                        <div class="col-sm-10">
                            <div class="radio">
                                <label>
                                    <input type="radio" @if($categoryInfo->is_show == '是') checked @endif value="是" id="optionsRadios1" name="is_show">显示</label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" @if($categoryInfo->is_show == '否') checked @endif value="否" id="optionsRadios2" name="is_show">不显示</label>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary" type="submit">保 存</button>
                        </div>
                    </div>
                </form>

            </div><!-- ibox-content -->
        </div>
    </div>
</div>
@endsection