<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\UserChangePasswordRequest;

use App\User;
use Auth;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * 修改用户的密码
     */
    public function changePassword(Request $request)
    {

        //  判断密码是否修改成功
        if (!empty($request->status) && $request->status == 'success') {
            $status = 'success';
        } else {
            $status = 'fail';
        }

        return view('auth.userChangePassword',['user'=>$request->user(),
                                                'status'=>$status]);
    }

    /**
     * 修改用户的密码
     *
     */
    public function changePasswordAction(UserChangePasswordRequest $request)
    {
        //  验证两次密码是否一致
        if ($request->password_new === $request->password_confirm) {
            $post = User::find(1);

            // 验证输入的密码是否一致
            if (Hash::check($request->password_original, $post->password)) {
                // 更新数据库的数据
                $post->password = bcrypt($request->password_new);
                $post->save();

                return back()->with('status', 'success');
            } else {

                return back()->with('status', 'failed');
            }            
        } else {

            return back()->with('status', 'diff');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
