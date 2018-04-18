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
            $query->where('cate_id=2')->order('create_time','desc');
        });

        $xhData= Article::all(function ($query){//协会活动
            $query->where('cate_id=3')->order('create_time','desc');
        });

        $newsData= Article::all(function ($query){//协会新闻
            $query->where('cate_id=4')->order('create_time','desc');
        });

        $zcData= Article::all(function ($query){//政策信息
            $query->where('cate_id=5')->order('create_time','desc');
        });

        $this->assign('services',$services);
        $this->assign('navList',$navList);
        $this->assign('links',$links);
        $this->assign('yjNews',$yjData);
        $this->assign('xhNews',$xhData);
        $this->assign('news',$newsData);
        $this->assign('zcNews',$zcData);
        return $this->fetch('index');
    }

}