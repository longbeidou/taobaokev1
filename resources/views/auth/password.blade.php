{{--继承整体框架--}}
@extends('auth.layouts.Master')

{{--网页的title--}}
@section('title')
    <title>{{ $title or config('website.name')}}</title>
@endsection

{{--网页的主体内容--}}
@section('content')
    <h3>{{ config('website.name') }}</h3>
    <p>请输入要找回密码的邮箱</p>
    <form method="POST"  class="m-t" role="form" action="{{ route('pwdemailaction') }}">
        {!! csrf_field() !!}

        @if (count($errors) > 0)
            <ul class="text-left list-unstyled">
                @foreach ($errors->all() as $error)
                <!-- <div class="form-group"> -->
                    <li class="text-left">{{ $error }}</li>
                <!-- </div> -->
                @endforeach
            </ul>
        @endif

        <div class="form-group">
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="请输入要找回密码的邮箱">
        </div>

        <button type="submit" class="btn btn-primary block full-width m-b">发送重置密码邮件</button>

        <p class="text-muted text-center"><small>已经有账户了？</small><a href="{{ route('login') }}">点此登录</a>
        </p>

    </form>
@endsection