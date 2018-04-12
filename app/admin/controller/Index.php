<?php
namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use app\admin\model\TbAdmin;
use think\Cache;
use think\Log;

class Index extends Base
{
    public function index()
    {
        $this->isLogin();
        $data=session('data');
        $this->assign('data',$data);
        $logdata=SystemLog::all(function ($query){
            $query->where('statue',0);
        });
        $num=null;
        if(count($logdata)!=0){
            $num=count($logdata);
        }
        $this->assign('num',$num);
        return $this->fetch();
    }

    /**
     * 清除模版缓存 不删除cache目录
     */
    public function clear_sys_cache() {
        Cache::clear();
        Log::clear();
        array_map('unlink', glob(TEMP_PATH . '/*.php'));
        rmdir(TEMP_PATH);

        $state = [
            'msg' => '清除成功',
            'code' => 1
        ];
        return $state;

//        $this->success( '清除成功', 'index/index' );
    }

//    欢迎页面
    public function welcome()
    {
        $data=session('data');

        $info = array(
            'php_os'=>PHP_OS,//操作系统
            'uname'=>php_uname(),//获取系统类型及版本号
            'language'=>$_SERVER['HTTP_ACCEPT_LANGUAGE'],//获取服务器语言
            'http'=>$_SERVER['HTTP_USER_AGENT'],//访问用户的浏览器信息
            'software'=>$_SERVER["SERVER_SOFTWARE"],//运行环境
            'php_method'=>php_sapi_name(),//php运行方式
            'think'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',//ThinkPHP版本
            'upload_max'=>ini_get('upload_max_filesize'),//上传附件限制
            'max_execution_time'=>ini_get('max_execution_time').'秒',//执行时间限制
            'time'=>date("Y年n月j日 H:i:s"),//服务器时间
            'time1'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),//北京时间
            'y_name'=>$_SERVER['SERVER_NAME'],//服务器域名
            'ip'=>gethostbyname($_SERVER['SERVER_NAME']),//IP
            'space'=>round((disk_free_space(".")/(1024*1024)),2).'M',//剩余空间
//            'cpu'=>$_SERVER['PROCESSOR_IDENTIFIER'],//获取服务器CPU数量
            //'root'=>$_SERVER['SystemRoot'],//获取服务器系统目录
        );
        $this->assign('info',$info);
        $this->assign('data',$data);
        return $this->fetch();
    }

//    查看管理员基本信息展示页
    public function info()
    {
        $this->assign('data',session('data'));
        return $this->fetch('admin/admin_info');
    }

//    修改管理员密码展示页

    public function changepass()
    {
        $data=session('data');
        $id=$data['id'];
        $newData=TbAdmin::get($id);
        $this->assign('data',$newData);
        return $this->fetch('admin/admin_password_edit');
    }

}
