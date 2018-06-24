<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        // 邮箱为key 随机字符串为token 存入缓存
        // 用户点击链接的时候 取key 验证token 正确则激活
        $email = $request->get('email');
        $token = $request->get('token');

        if(!$email || !$token){
            throw new InvalidRequestException('验证链接不正确');
        }

        $cache_key = 'email_verification_'.$email;

        if(Cache::get($cache_key) !== $token){
            throw new InvalidRequestException('验证链接有误');
        }

        if(!$user = User::whereEmail($email)->first()){
            throw new InvalidRequestException('用户不存在!');
        }

        //验证通过
        $user->email_verified = true;
        $user->save();

        //删除缓存中的key
        Cache::forget($cache_key);

        return view('pages.success' , ['msg'=>'邮箱验证成功!']);
    }

    //重新发激活邮件
    public function send(Request $request)
    {
        $user = $request->user();

        if($user->email_verified){
            throw new InvalidRequestException('你已经验证过邮箱了');
        }

        $user->notify(new EmailVerificationNotification());

        return view('pages.success',['msg'=>'邮件发送成功!']);
    }
}
