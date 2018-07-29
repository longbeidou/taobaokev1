{{--继承整体框架--}}
@extends('admin.layouts.DashboardMaster')

{{--head部分--}}
@section('seohead')
    <title>{{$title or '我爱你一万年网站后台'}}</title>
    <meta name="description" value="{{$description or '我爱你一万年网站后台'}}" />
@endsection