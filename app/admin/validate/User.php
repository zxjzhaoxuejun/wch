<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 下午 21:37
 */

namespace app\admin\validate;

use think\Validate;

class User extends Validate{

    protected $rule=[
        'name|用户名'=>'require|min:2|max:20',
        'password|密码'=>'require|min:6|max:24|confirm:repassword',
        'email|邮箱'=>'require',
    ];

    protected $message=[
        'name.require'=>'用户名不能为空！',
        'email.require'=>'邮箱不能为空！',
        'password.require'=>'密码不能为空！',
        'name.min'=>'用户名输入不能少于2位！',
        'name.max'=>'用户名输入不能超过20位！',
        'password.min'=>'密码输入不能少于6位！',
        'password.max'=>'密码输入不能超过24位！',
        'password.confirm'=>'密码与确认密码不一致！',

    ];

}