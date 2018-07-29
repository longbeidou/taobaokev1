<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * 显示登录页面
     *
     */
    public function getLogin()
    {
        return view('admin.login.login');
    }

    /**
     * 处理登录的请求
     *
     */
    public function postLogin(Request $request)
    {
        //
    }

    /**
     * 处理登出的操作
     *
     */
    public function getLogout()
    {
        //
    }

    /**
     * 显示注册页面
     *
     */
    public function getRegister()
    {
        return view('admin.login.create');
    }

    /**
     * 处理注册的请求
     *
     */
    public function postRegister(Request $request)
    {
        //
    }
}
