<?php
namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\SystemLog;
use app\admin\model\TbAdmin;
use app\admin\model\Article;//资讯
use app\admin\model\Banner;//轮播图
use app\admin\model\MemberCompany;//会员单位
use app\admin\model\MemberSign;//个人会员
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

        $tableInfo=array(
            'art_today'=>Article::where($this->find_createtime(1))->count(),
            'art_week'=>Article::where($this->find_createtime(2))->count(),
            'art_total'=>Article::count(),
            'art_month'=>Article::where($this->find_createtime(3))->count(),
            'banner_today'=>Banner::where($this->find_createtime(1))->count(),
            'banner_week'=>Banner::where($this->find_createtime(2))->count(),
            'banner_total'=>Banner::count(),
            'banner_month'=>Banner::where($this->find_createtime(3))->count(),
            'company_today'=>MemberCompany::where($this->find_createtime(1))->count(),
            'company_week'=>MemberCompany::where($this->find_createtime(2))->count(),
            'company_total'=>MemberCompany::count(),
            'company_month'=>MemberCompany::where($this->find_createtime(3))->count(),
            'sign_today'=>MemberSign::where($this->find_createtime(1))->count(),
            'sign_week'=>MemberSign::where($this->find_createtime(2))->count(),
            'sign_total'=>MemberSign::count(),
            'sign_month'=>MemberSign::where($this->find_createtime(3))->count(),
        );
        $this->assign('tableInfo',$tableInfo);
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



    /*
    *按今天，本周，本月，本季度，本年，全部查询预约单数据
    * $day 代表查询条件1天，2一周，3月，4季度，5年； $cid 代表 公司id
    *返回array $data 查询条件 数组
     *
    */
    public function find_createtime($day){
        //查询当天数据
        if($day==1){
            $today=strtotime(date('Y-m-d 00:00:00'));
//            $data['cid']=$cid;
            $data['create_time'] = array('egt',$today);
            return $data;
        //查询本周数据
        }else if($day==2){
            $arr=array();
            $arr=getdate();
            $num=$arr['wday'];
            $start=time()-($num-1)*24*60*60;
            $end=time()+(7-$num)*24*60*60;
//            $data['cid']=$cid;
            $data['create_time'] = array('between',array($start,$end));
            return $data;
        //查询本月数据
        }else if($day==3){
            $start=strtotime(date('Y-m-01 00:00:00'));
            $end = strtotime(date('Y-m-d H:i:s'));
//            $data['cid']=$cid;
            $data['create_time'] = array('between',array($start,$end));
            return $data;
        //查询本季度数据
        }else if($day==4){
            $month=date('m');
            if($month==1 || $month==2 ||$month==3){
                $start=strtotime(date('Y-01-01 00:00:00'));
                $end=strtotime(date("Y-03-31 23:59:59"));
            }elseif($month==4 || $month==5 ||$month==6){
                $start=strtotime(date('Y-04-01 00:00:00'));
                $end=strtotime(date("Y-06-30 23:59:59"));
            }elseif($month==7 || $month==8 ||$month==9){
                $start=strtotime(date('Y-07-01 00:00:00'));
                $end=strtotime(date("Y-09-30 23:59:59"));
            }else{
                $start=strtotime(date('Y-10-01 00:00:00'));
                $end=strtotime(date("Y-12-31 23:59:59"));
            }
//            $data['cid']=$cid;
            $data['create_time'] = array('between',array($start,$end));
            return $data;
            //查询本年度数据
        }else if($day==5){
            $year=strtotime(date('Y-01-01 00:00:00'));
//            $data['cid']=$cid;
            $data['create_time'] = array('egt',$year);
            return $data;
        //全部数据
        }else{
//            $data['cid']=$cid;
//              return $data;
        }
    }



}
