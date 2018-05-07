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
use app\admin\model\System;
use app\admin\model\Banner;//轮播图
use app\admin\model\AboutUs;
use app\admin\model\Leader;//协会领导
use app\admin\model\Partisan;//理事单位
use app\admin\model\Download;//下载专区
use app\admin\model\Schools;//双创学院
use think\Controller;
use think\Request;


class Index extends Controller {
    public function index()
    {

        $navList=(new Nav())->navOut();//导航
        $services=MemberService::get(1);//会员服务
        $links=Links::all(function ($query){
            $query->order('order','asc');
        });

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
            $query->limit(6)->order('create_time','desc');
        });

        $banner=Banner::all(function ($query){//轮播图
            $query->where('type',0)->limit(3)->order('create_time','desc');
        });

        $copy=System::get(1);//底部地址、联系电话、关键字

        $this->assign('pageTitle','深圳市互联网创业创新服务促进会');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',1)->limit(1)->order('create_time','desc');
        });
        $leftNav=['业界动态','协会动态','协会新闻','政策信息'];
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',5)->limit(1)->order('create_time','desc');
        });
        $leftNav=["双创培训"];
        if($data['id']==''){
            $yjData= Schools::order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle','双创学院');
        }else{
            $yjData= Schools::where('cate_id',$data['id'])->order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('navTitle',$leftNav[$data['id']]);
        }

        $this->assign('pageTitle','双创学院');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',4)->limit(1)->order('create_time','desc');
        });
        $leftNav=['协会简介','组织架构','协会领导','专家顾问','分支机构'];//2,3,4
        $con= AboutUs::get(1);

        $this->assign('pageTitle','关于协会');
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
            $yjData= Leader::order('id','asc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews',$yjData);
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('xh_mode');
        }else if($data['id']==5){//'专家顾问'
            $yjData= Counselor::order('create_time','desc')->paginate(8, false, [
                'query' => input('param.'),
            ]);
            $this->assign('yjNews',$yjData);
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',2)->limit(1)->order('create_time','desc');
        });
        $leftNav=['协会指南','会员申请'];//1，2


        $this->assign('pageTitle','加入协会');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',3)->limit(1)->order('create_time','desc');
        });
        $leftNav=['会员单位','理事单位'];//2,3

        $this->assign('pageTitle','会员展示');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Article::get($data['id']);

        $actMode=New Article();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);

        $this->assign('pageTitle','资讯详情页');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Partisan::get($data['id']);

        $this->assign('pageTitle','理事单位详情页');
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('company_details');
    }

    public function company_details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= MemberCompany::get($data['id']);

        $this->assign('pageTitle','会员单位详情页');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Schools::get($data['id']);

        $actMode=New Schools();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);

        $this->assign('pageTitle','双创学院详情页');
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
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con=MemberService::get(1);
        $this->assign('pageTitle','协会服务');
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);

        return $this->fetch('services');

    }

    /**
     * 下载页
     */
    public function download_page()
    {
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con=Download::all();
        $this->assign('pageTitle','下载专区');
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


}