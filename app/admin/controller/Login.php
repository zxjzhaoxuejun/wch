<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\TbAdmin;
use think\Controller;
use think\Request;
use app\admin\model\SystemLog;
class Login extends Controller
{
    /**
     * 显示登录界面
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        if(session('user_id')){
            $this->error('已登录，请不要重复登录！','Index/index');
            exit;
        }
        return $this->fetch('login');
    }

    /**
     * 验证用户信息
     */
    public function check()
    {
        //
        $data=input('post.');
        if (!captcha_check($data['code'])){
            $this->error('验证码输入错误！');
            exit;
        }

        $user=new TbAdmin();
        $res=$user->where('username',$data['username'])->find();

        if($res){
            if($res['password']==md5($data['password'])){
                session('data',$res);//保存登录的用户信息
                session('user_id',$res['password']);

//                更新数据表中的登录次数和最后登录时间
                $res->setInc('login_count');
                $res->save(['last_time'=>time()]);

                $user_1=session('data');
                $log=[
                    'type'=>'管理员模块',
                    'content'=>'管理员登录成功!',
                    'editor'=>$user_1['username'],
                ];
                $logmodel=new SystemLog();
                $logmodel->allowField(true)->save($log);

                $this->success('恭喜登录成功！','Index/index');

            }else{
                $this->error('密码输入不正确!');
            }
        }else{
            $this->error('账号输入不正确!');
            exit;
        }

    }

    /**
     * 退出登录
     */
    public function logout()
    {
        //
        session(null);
        $this->success('退出成功！正在返回...','Login/index');
    }


}
