<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/12
 * Time: 9:50
 */
namespace app\index\controller;
use app\admin\model\Nav;
use app\admin\model\MemberService;//协会服务
use app\admin\model\Links;
use app\admin\model\Article;
use app\admin\model\MemberCompany;//会员单位
use app\admin\model\MemberSign;//个人会员
use app\admin\model\Counselor;//专家顾问
use app\admin\model\Recruitment;
use app\admin\model\System;
use app\admin\model\Banner;//轮播图
use app\admin\model\AboutUs;
use app\admin\model\Leader;//协会领导
use app\admin\model\Partisan;//理事单位
use app\admin\model\Download;//下载专区
use app\admin\model\Schools;//双创学院
use app\admin\model\MemberReg;//注册
use app\admin\controller\Mail;//发布邮件

use app\admin\model\Writing;
use think\Controller;
use app\admin\model\Big;
use app\admin\model\Speech;
use app\admin\model\Honored;
use app\admin\model\Review;
use app\admin\model\Enroll;

use think\Request;
use think\Session;


class Index extends Controller {


    /**
     * 协会活动
     */
    public function special()
    {

        $hotNews=Big::all();
        $copy=System::get(1);//底部地址、联系电话、关键字
        $yjNews= Article::all(function ($query){//焦点新闻
            $query->where('cate_id',6)->limit(9)->order('create_time','desc');
        });
        $sd= Speech::all(function ($query){//主旨
            $query->limit(3)->order('id','desc');
        });

        $honors= Honored::all(function ($query){//嘉宾
            $query->order('id','desc');
        });
        $reviews= Review::all(function ($query){//评审专家
            $query->limit(9)->order('id','desc');
        });
        $this->assign('pageTitle','深港科创大会活动专题页');
        $this->assign('copyRight',$copy);
        $this->assign('honors',$honors);
        $this->assign('reviews',$reviews);
        $this->assign('sd',$sd);
        $this->assign('yjNews',$yjNews);
        $this->assign('hotNews',$hotNews);
        return $this->fetch('activity');
    }

    public function review()
    {
        $reviews= Review::order('id','desc')->paginate(8);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','评审专家');
        $this->assign('copyRight',$copy);
        $this->assign('reviews',$reviews);
        return $this->fetch('review');
    }


    public function index()
    {


        $navList=(new Nav())->navOut();//导航
        $services=MemberService::get(1);//会员服务
        $links=Links::all(function ($query){
            $query->order('order','asc');
        });
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $yjData= Article::all(function ($query){//业界动态
            $query->where('cate_id',2)->limit(6)->order('create_time','desc');
        });

        $xhData= Article::all(function ($query){//协会活动
            $query->where('cate_id',3)->limit(6)->order('create_time','desc');
        });

        $newsData= Article::all(function ($query){//协会新闻
            $query->where('cate_id',4)->limit(6)->order('create_time','desc');
        });

        $zcData= Article::all(function ($query){//政策信息
            $query->where('cate_id',5)->limit(6)->order('create_time','desc');
        });

        $hotNews=Article::all(function ($query){//热门新闻
            $query->where('art_statue=2 or art_statue=5')->limit(4)->order('create_time','desc');
        });

        $schoolNews=Schools::all(function ($query){//双创学院
            $query->where('art_statue=2 or art_statue=5')->limit(3)->order('create_time','desc');
        });

        $memberCompany=MemberCompany::all(function ($query){//会员单位
            $query->where('show_val',1)->limit(10)->order('create_time','desc');
        });

        $counselor=Counselor::all(function ($query){//专家顾问
            $query->limit(8)->order('create_time','desc');
        });

        $banner=Banner::all(function ($query){//轮播图
            $query->where('type',0)->limit(3)->order('create_time','desc');
        });

        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('navTitle','首页');
        $this->assign('pageTitle','深圳市互联网创业创新服务促进会');
        $this->assign('pageId',0);
        $this->assign('services',$services);
        $this->assign('navList',$navList);
        $this->assign('links',$links);
        $this->assign('yjNews',$yjData);
        $this->assign('xhNews',$xhData);
        $this->assign('news',$newsData);
        $this->assign('zcNews',$zcData);
        $this->assign('hotNews',$hotNews);
        $this->assign('schoolNews',$schoolNews);
        $this->assign('memberCompany',$memberCompany);
        $this->assign('counselor',$counselor);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        return $this->fetch('index');
    }

    /**
     * @param Request $request
     * @return mixed
     * 资讯中心
     * 列表页
     */
    public function new_list(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',1)->limit(1)->order('create_time','desc');
        });
        $leftNav=['业界动态','协会动态','会员资讯','政策信息','焦点新闻'];
        if($data['id']==''){
            $yjData= Article::order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle','资讯中心');
        }else{
            $yjData= Article::where('cate_id',$data['id'])->order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle',$leftNav[$data['id']-2]);
        }

        $this->assign('pageTitle','资讯中心');
        $this->assign('pageId',5);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('yjNews',$yjData);
        $this->assign('leftNav',$leftNav);
        $this->assign('navId',$data['id']-2);

        return $this->fetch('list');
    }

    /**
     * @param Request $request
     * @return mixed
     * 双创学院
     * 列表页
     */
    public function school_list(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',5)->limit(1)->order('create_time','desc');
        });
        $leftNav=["双创培训"];
        if($data['id']==''){
            $yjData= Schools::order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle','网创学院');
        }else{
            $yjData= Schools::where('cate_id',$data['id'])->order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle',$leftNav[$data['id']]);
        }

        $this->assign('pageTitle','网创学院');
        $this->assign('pageId',6);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('yjNews',$yjData);
        $this->assign('leftNav',$leftNav);
        $this->assign('navId',$data['id']);

        return $this->fetch('school');
    }

    /**
     * @param Request $request
     * 关于协会
     * '协会简介','组织架构','分支机构'
     */
    public function about_us(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',4)->limit(1)->order('create_time','desc');
        });
        $leftNav=['协会简介','组织架构','协会领导','网创智库','分支机构'];//2,3,4
        $con= AboutUs::get(1);

        $this->assign('pageTitle','关于协会');
        $this->assign('pageId',1);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('leftNav',$leftNav);
        $this->assign('navId',$data['id']-2);

        if($data['id']==2){//协会简介
            $this->assign('navTitle','协会简介');
            $this->assign('con',$con['abstract']);
        }else if($data['id']==3){//组织架构
            $this->assign('navTitle','组织架构');
            $this->assign('con',$con['framework']);
        }else if($data['id']==6){//分支机构
            $this->assign('navTitle','分支机构');
            $this->assign('con',$con['organization']);
        }else if($data['id']==4){//协会领导
            $yjData= Leader::order('id','asc')->paginate(6, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews',$yjData);
            $this->assign('titleName','职务');
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('xh_mode');
        }else if($data['id']==5){//'专家顾问'
            $yjData= Counselor::order('create_time','desc')->paginate(6, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews',$yjData);
            $this->assign('titleName','领域');
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('xh_mode');
        }



        return $this->fetch('list_1');
    }

    /**
     * @param Request $request
     * 加入协会、协会指南、会员申请
     */
    public function join_us(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',2)->limit(1)->order('create_time','desc');
        });
        $leftNav=['协会指南','会员申请'];//1，2


        $this->assign('pageTitle','加入协会');
        $this->assign('pageId',3);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('leftNav',$leftNav);
        $this->assign('navId',$data['id']-2);


        if($data['id']==2){//协会指南
            $con= AboutUs::get(1);
            $this->assign('navTitle','协会指南');
            $this->assign('con',$con['initiation']);
            return $this->fetch('initiation');
        }else{//会员申请
//            $con= AboutUs::get(1);
            $this->assign('navTitle','会员申请');
//            $this->assign('con',$con['initiation']);
            return $this->fetch('register');
        }

    }

    /**
     * @param Request $request
     * 会员单位、理事单位
     */
    public function member_show(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',3)->limit(1)->order('create_time','desc');
        });
        $leftNav=['会员单位','理事单位'];//2,3

        $this->assign('pageTitle','会员展示');
        $this->assign('pageId',4);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('banner',$banner);
        $this->assign('leftNav',$leftNav);
        $this->assign('navId',$data['id']-2);

        if($data['id']==2) {//会员单位
            $yjData = MemberCompany::where('show_val',1)->order('create_time', 'desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews', $yjData);
            $this->assign('navTitle', $leftNav[$data['id'] - 2]);
            return $this->fetch('member_company');
        }else{
            $yjData = Partisan::where('show_val',1)->order('create_time', 'desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews', $yjData);
            $this->assign('navTitle', $leftNav[$data['id'] - 2]);
            return $this->fetch('member_partisan');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 资讯详情页
     */
    public function details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
//        $this->assign('info',Session::get('userData'));
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Article::get($data['id']);

        $actMode=New Article();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);
        $this->assign('pageTitle',$con['art_title']);
        $this->assign('pageId',5);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('details');
    }


    /**
     * @param Request $request
     * @return mixed
     * 会员单位情页、理事单位
     */
    public function partisan_details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Partisan::get($data['id']);

        $this->assign('pageTitle','理事单位详情页');
        $this->assign('pageId',4);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('company_details');
    }

    public function company_details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= MemberCompany::get($data['id']);

        $this->assign('pageTitle','会员单位详情页');
        $this->assign('pageId',4);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('company_details');
    }

    /**
     * @param Request $request
     * @return mixed
     * 双创详情页
     */
    public function school_details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Schools::get($data['id']);

        $actMode=New Schools();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);

        $this->assign('pageTitle',$con['art_title']);
        $this->assign('pageId',6);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('details');
    }

    /**
     * 人才招聘
     */
    public function recruitment()
    {

        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Recruitment::get(1);
        $actMode=New Recruitment();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>1]);
        $this->assign('pageTitle',$con['art_title']);
        $this->assign('pageId',3);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('details');
    }

    /**
     * 协会服务
     */
    public function service_page()
    {
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con=MemberService::get(1);
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',6)->limit(1)->order('create_time','desc');
        });
        $this->assign('pageTitle','协会服务');
        $this->assign('pageId',2);
        $this->assign('navList',$navList);
        $this->assign('banner',$banner);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);

        return $this->fetch('services');

    }

    /**
     * 测试
     */
    public function test_page()
    {

        return $this->fetch('phone');

    }


    /**
     * 下载页
     */
    public function download_page()
    {
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',7)->limit(1)->order('create_time','desc');
        });
        $con=Download::all();
        $this->assign('pageTitle','下载专区');
        $this->assign('pageId',3);
        $this->assign('banner',$banner);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);

        return $this->fetch('down_page');

    }

    /**
     * @param Request $request
     * 保存会员申请
     */
    public function save(Request $request)
    {
        $data=$request->param();
        if($data['type']==1){//会员单位
            $company = new MemberCompany();
            $res = $company->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '提交成功!',
                    'code' => 1
                ];

            } else {
                $state = [
                    'msg' => '提交失败!',
                    'code' => 0
                ];
            }
            return $state;
        }else{//理事单位/副会长单位
            $sign=new Partisan();
            $res = $sign->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '提交成功!',
                    'code' => 1
                ];

            } else {
                $state = [
                    'msg' => '提交失败!',
                    'code' => 0
                ];
            }
            return $state;
        }
    }

    // 文件下载
    public function download(Request $request){
        $data=$request->param();
        $list=Download::get($data['id']);

        $hz=explode('.',$list['url']);
        $file_name =$list['name'].'.'.$hz[1];     //下载文件名
        $file_root="static/".$list['url']; //下载文件存放目录

        //检查文件是否存在
        if (! file_exists ( $file_root )) {
            echo "文件找不到";
            exit ();
        } else {
            //打开文件
            $file = fopen ( $file_root, "r" );
            //输入文件标签
            Header ( "Content-type: application/octet-stream" );
            Header ( "Accept-Ranges: bytes" );
            Header ( "Accept-Length: " . filesize ( $file_root ) );
            Header ( "Content-Disposition: attachment; filename=" . $file_name );
            //输出文件内容
            //读取文件内容并直接输出到浏览器
            echo fread ( $file, filesize ( $file_root ) );
            fclose ( $file );
            exit ();
        }
    }

//    会员注册
    public function reg()
    {
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','会员注册');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        return $this->fetch('member_reg');
    }

    /**
     * @return mixed
     * 注册类型选择
     */
    public function reg_type(Request $request)
    {
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $user=$request->param('user');
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','类型选择');
        $this->assign('pageId',7);
        $this->assign('user',$user);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        return $this->fetch('member_type');
    }

    /**
     * 用户注册信息提交
     */
    public function check_reg()
    {
        //
        $data=input('post.');
        if (!captcha_check($data['code'])){
            $this->error('验证码输入错误！','','code');
            exit;
        }

        $reg=new MemberReg();
        $res=$reg->where('email',$data['email'])->find();

        if(!$res){
            $str='123456789';
            $randStr = str_shuffle($str);//打乱字符串
            $rands= substr($randStr,0,6);//substr(string,start,length);返回字符串的一部分
            $data['email_code']=$rands;
            $res=$reg->allowField(true)->save($data);
            if($res){
                $m=new Mail();
                $m->email($data);
                Session::set('userData',$data['email']);//保存登录的用户信息
                $this->success('恭喜您注册成功！','Index/reg_type',$data['email']);
            }else{
                $this->error('注册失败!');
            }
        }else{
            $this->error('邮箱已注册，请直接登录!','','email');
            exit;
        }

    }

    /**
     * 用户类型选择信息提交
     */
    public function check_type(Request $request)
    {
        //
        $data=$request->param();
        $reg=new MemberReg();
        $t=$reg->where('email',$data['u'])->find();
        if($t['type']!=null){
            $this->success('已选择类型,请不要重复选择!','index/audit',$t['type']);
        }else{
            $reg->allowField(true)->save($data,['email'=>$data['u']]);
            $this->success('类型选择成功！','index/audit',$data['type']);
        }
    }

    /**
     * 入驻审核
     */
    public function audit(Request $request)
    {
        $t=$request->param();
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $reg=new MemberReg();
        $data=$reg->where('email',$t['u'])->find();
        $this->assign('info',$data);
        if($data['status']!='未认证'){
            $stu=1;
        }else{
            $stu=0;
        }
        $this->assign('pageTitle','入驻审核');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('user',$t);
        $this->assign('status',$stu);
        return $this->fetch('member_type_'.$t['type']);
    }

    /**
     * 入驻审核信息提交
     */
    public function audit_save(Request $request)
    {
        if($request->isAjax(true)){
            $t=$request->param();
            $t['status']=2;
            $reg=new MemberReg();
            $res=$reg->allowField(true)->save($t,['email'=>$t['email']]);
            if($res){
                $this->success('审核提交成功！','','审核认证');
            }else{
                $this->error('审核提交失败!');
            }
        }

    }

    /**
     * 认证流程
     */
    public function process(Request $request)
    {
        $type=$request->param('type');
        $uInfo=Session::get('userData');
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $reg=new MemberReg();
        $data=$reg->where('email',$uInfo)->find();
        $this->assign('info',$data);
        $this->assign('pageTitle','认证流程');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('type',$type);
        return $this->fetch('process');
    }

    /**
     * 登录页面
     */
    public function login()
    {
        $navList=(new Nav())->navOut();//导航
        $this->assign('info',Session::get('userData'));
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','会员登录');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        return $this->fetch('member_login');
    }
    /**
     * @param Request $request
     * 会员登录
     */
    public function check_user(Request $request)
    {
        //
        if(!is_null(Session::get('userData'))){
            $this->error('已登录，请勿重复登录!','','email');
            exit;
        }
        $data=$request->param();
        $user=new MemberReg();
        $res=$user->where('email',$data['email'])->find();

        if($res){
            if($res['password']==$data['password']){
                Session::set('userData',$res['email']);//保存登录的用户信息
//                session('userId',$res['password']);

//                更新数据表中的登录次数和最后登录时间
                $res->setInc('login_count');
                $res->save(['login_time'=>time()]);
                $this->success('恭喜登录成功！','Index/index');

            }else{
                $this->error('密码输入不正确!','','password');
            }
        }else{
            $this->error('账号输入不正确!','','email');
            exit;
        }
    }

    /**
     * 退出登录
     */

    public function login_close()
    {
        Session::set('userData',null);
        $navList=(new Nav())->navOut();//导航
        $this->assign('info',Session::get('userData'));
        $copy=System::get(1);//底部地址、联系电话、关键字
        $this->assign('pageTitle','会员登录');
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        Session::set('userData',null);
        return $this->fetch('index/member_login');
    }
    
    /**
     * 网创发布导航
     * 游客身份
     * 登录用户
     */
    public function wc_issue()
    {
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();

        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',7)->limit(1)->order('create_time','desc');
        });
        $w=new Writing();
        $list=$w->order('create_time','desc')->paginate(8);
        $count=$w->count();
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pageTitle','网创发布');
        $this->assign('pageId',7);
        $this->assign('banner',$banner);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('navTitle', '全部文章');

        if(!$uInfo){
        return $this->fetch('issue_page');
        }else{
            $leftNav=['基本信息','我的发布','密码修改'];//2,3,4
            $this->assign('leftNav',$leftNav);
            $this->assign('navId',0);
            $this->assign('navTitle','基本信息');
            return $this->fetch('user/user_base');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * 发布文章详情页展示
     */
    public function fb_details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $uInfo=Session::get('userData');
        $m=new MemberReg();
        $t=$m->where('email',$uInfo)->find();
        $this->assign('info',$t);
        $copy=System::get(1);//底部地址、联系电话、关键字
        $w=New Writing();
        $con=$w->where('id',$data['id'])->find();
        $con->setInc('art_view');
        $this->assign('pageTitle',$con['art_title']);
        $this->assign('pageId',7);
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);
        return $this->fetch('user/details');
    }




}