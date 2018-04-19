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
use app\admin\model\Counselor;
use app\admin\model\System;
use think\Controller;


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

}