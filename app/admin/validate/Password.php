<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/19 0019
 * Time: 下午 13:55
 */
namespace app\admin\validate;

use think\Validate;

class Password extends Validate{
    protected $rule=[
        'oldpassword|旧密码'=>'require|min:6|max:24',
        'newpassword|密码'=>'require|min:6|max:24|confirm:repassword',
    ];

    protected $message=[
        'oldpassword.require'=>'旧密码输入不能为空！',
        'newpassword.require'=>'密码不能为空！',
        'oldpassword.min'=>'旧密码输入不能少于6位！',
        'oldpassword.max'=>'旧密码输入不能超过24位！',
        'newpassword.min'=>'密码输入不能少于6位！',
        'newpassword.max'=>'密码输入不能超过24位！',
        'newpassword.confirm'=>'密码与确认密码不一致！',
    ];

}