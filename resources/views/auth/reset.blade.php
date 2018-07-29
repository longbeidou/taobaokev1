{{--继承整体框架--}}
@extends('auth.layouts.Master')

{{--网页的title--}}
@section('title')
    <title>找回密码 - {{ $title or config('website.name')}}</title>
@endsection

{{--继承整体框架--}}
@section('content')
    <h3>{{ config('website.name') }}！</h3>
    <p>请重设密码</p>
    <form method="POST" class="m-t" role="form" action="{{ route('pwdresetaction') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">
        @if (count($errors) > 0)
            <ul class="text-left list-unstyled">
                @foreach ($errors->all() as $error)
                    <li class="text-left">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="form-group">
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="请输入邮箱">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="请输入密码">
        </div>
        <div class="form-group">
            <input type="password" name="password_confirmation" class="form-control" placeholder="请再次输入密码">
        </div>
        <button type="submit" class="btn btn-primary block full-width m-b">重置密码</button>

        <p class="text-muted text-center"><small>密码找到了？</small><a href="{{ route('login') }}">点此登录</a>
        </p>

    </form>
@endsection