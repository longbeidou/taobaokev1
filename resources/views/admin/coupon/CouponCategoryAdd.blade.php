{{-- 继承整体框架 --}}
@extends('adminLayouts.table')

{{-- title --}}
@section('title')
<title>增加优惠券分类 - {{ $title or config('website.name')}}</title>
@endsection

{{--网页的主体内容--}}
@section('content')
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>增加优惠券分类</h5>
                <div class="ibox-tools">
                    <a href="{{ route('couponCategoryList') }}" class="collapse-link">
                        <i class="fa fa-list-ul"></i> 分类列表
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
            	<form action="{{ route('couponCategoryAddaction') }}" method="POST">
            		{{csrf_field()}}
                <div class="form-inline">
                    <div class="form-group">
                        <label for="a1" class="">分类名称：</label>
                        <input type="text" placeholder="分类名称" id="a1" name="name" required class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="a1" class="">排&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;序：</label>
                        <input type="number" min="0" max="99" step="1"  id="a1" name="order" value="0" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="a1" class="">是否显示：</label>
                        <label class="checkbox-inline">
                            <input type="radio" value="是" name="isShow" id="inlineCheckbox1">显示
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" value="否" name="isShow" checked id="inlineCheckbox1">不显示
                        </label>
                    </div>
                </div>

            	<div class="hr-line-dashed"></div>
            	<h3>第一组商品</h3>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub[0][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub[0][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub[1][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub[1][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub[2][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub[2][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub[3][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub[3][q]" class="form-control">
                    </div>
                </div>

                <div class="hr-line-dashed"></div>

            	<h3>第二组商品</h3>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub2[0][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub2[0][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub2[1][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub2[1][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub2[2][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub2[2][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
                        <label for="exampleInputEmail2" class="">选择字段：</label>
                        <select class="form-control" name="sub2[3][cate]">
                            <option value="goods_name">商品名称</option>
                            <option value="cate">商品一级类目</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="a1" class="">填写关键词：</label>
                        <input type="text" placeholder="关键字" id="a1" name="sub2[3][q]" class="form-control">
                    </div>
                </div>
                <div class="form-inline" style="margin:10px; 0px;"></div>
                <div class="form-inline">
                    <div class="form-group">
		                <div class="col-sm-12 col-sm-offset-8">
		                	<input class="btn btn-info" type="submit" value="提  交">
		                </div>
                    </div>
                </div>
                <div class="info">
                	<p><strong>“关键字”的填写说明：</strong><br />
                		“%关键词%”表示关键词可以出现在任何位置；<br />
                		“关键词%”表示以”关键词“开头的句子；<br />
                		“%关键词”表示以”关键词“结尾的句子</p>
                </div>
            	</form>
            </div><!-- ibox-content -->
        </div>
    </div>
</div>
@endsection