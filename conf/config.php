<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/16
 * Time: 15:33
 */
use think\Env;

return [
    'app_status'    =>  Env::get('status','dev'),
    'auto_bind_module'   => true,
    'url_route_on'      =>  true,
    'url_route_must'    =>false,
    // 入口自动绑定模块
    'auto_bind_module'       => true,
    // 应用调试模式
    'app_debug'              =>true,
    '__JS__'    => '/static/js',
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        //// 验证码字体大小(px)
        'fontSize' => 18,
        // 是否画混淆曲线
        'useCurve' => false,
        // 验证码图片高度
        'imageH'   => 40,
        // 验证码图片宽度
        'imageW'   => 155,
        // 验证码位数
        'length'   => 5,
        // 验证成功后是否重置
         'reset'    => true
    ],
];