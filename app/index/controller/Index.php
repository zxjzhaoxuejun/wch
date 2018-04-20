<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/12
 * Time: 9:50
 */
namespace app\index\controller;
use app\admin\model\Nav;
use app\admin\model\MemberService;
use app\admin\model\Links;
use app\admin\model\Article;
use app\admin\model\MemberCompany;
use app\admin\model\Counselor;//专家顾问
use app\admin\model\System;
use app\admin\model\Banner;
use app\admin\model\AboutUs;
use app\admin\model\Leader;//协会领导
use app\admin\model\Partisan;//理事单位
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

        $memberCompany=MemberCompany::all(function ($query){//会员单位
            $query->limit(10)->order('create_time','desc');
        });

        $counselor=Counselor::all(function ($query){//专家顾问
            $query->limit(6)->order('create_time','desc');
        });

        $copy=System::get(1);//底部地址、联系电话、关键字

        $this->assign('pageTitle','首页');
        $this->assign('services',$services);
        $this->assign('navList',$navList);
        $this->assign('links',$links);
        $this->assign('yjNews',$yjData);
        $this->assign('xhNews',$xhData);
        $this->assign('news',$newsData);
        $this->assign('zcNews',$zcData);
        $this->assign('hotNews',$hotNews);
        $this->assign('memberCompany',$memberCompany);
        $this->assign('counselor',$counselor);
        $this->assign('copyRight',$copy);
        return $this->fetch('index');
    }

    /**
     * @param Request $request
     * @return mixed
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
            $yjData= Article::order('create_time','desc')->paginate(8);
            $this->assign('navTitle','资讯中心');
        }else{
            $yjData= Article::where('cate_id',$data['id'])->order('create_time','desc')->paginate(8);
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
     * 关于协会
     * '协会简介','组织架构','分支机构'
     */
    public function about_us(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $banner=Banner::get(function ($query){//banner图
            $query->where('type',1)->limit(1)->order('create_time','desc');
        });
        $leftNav=['协会简介','组织架构','协会领导','专家顾问','理事单位','分支机构'];//2,3,4
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
        }else if($data['id']==7){//分支机构
            $this->assign('navTitle','分支机构');
            $this->assign('con',$con['organization']);
        }else if($data['id']==6){//理事单位
            $yjData= Partisan::order('create_time','desc')->paginate(8);
            $this->assign('yjNews',$yjData);
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('list_2');
        }else if($data['id']==4){//协会领导
            $yjData= Leader::order('create_time','desc')->paginate(8);
            $this->assign('yjNews',$yjData);
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('list_2');
        }else if($data['id']==5){//'专家顾问'
            $yjData= Counselor::order('create_time','desc')->paginate(8);
            $this->assign('yjNews',$yjData);
            $this->assign('navTitle',$leftNav[$data['id']-2]);
            return $this->fetch('list_2');
        }



        return $this->fetch('list_1');
    }

    /**
     * @param Request $request
     * @return mixed
     * 详情页
     */
    public function details(Request $request)
    {
        $data=$request->param();
        $navList=(new Nav())->navOut();//导航
        $copy=System::get(1);//底部地址、联系电话、关键字
        $con= Article::get($data['id']);

        $actMode=New Article();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);

        $this->assign('pageTitle','详情页');
        $this->assign('navList',$navList);
        $this->assign('copyRight',$copy);
        $this->assign('con',$con);


        return $this->fetch('details');
    }

}