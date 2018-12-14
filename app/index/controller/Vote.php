<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/10
 * Time: 8:55
 */
namespace app\index\controller;

use app\admin\model\Datum;
use think\Controller;
use think\Request;
use app\admin\model\Vote as VoteModel;
use app\admin\model\Shortlist;//入围品牌
use app\admin\model\Intro;//简介
use app\admin\model\Partners;//合作机构
use app\admin\model\Selection;//评选动态
use app\admin\model\Professor;//专家评委
use app\admin\model\Contact;//联系我们
use think\Session;

class Vote extends Controller{

    public function poll(Request $request)
    {

        $data=$request->param();
        $svote = new VoteModel();
        $p=new Shortlist();
//        $res = $svote->where('data'==Session::get('name'))->find();

        if(Session::get('nameInfo')){//不能投票
            $state=[
                'msg'=>'已投票，请不要重复投！',
                'code'=>0
            ];
        }else{//可以投票
                $con=Shortlist::get($data['id']);
                $s=$p->allowField(true)->save(['poll'=>$con['poll']+1],['id'=>$data['id']]);
                if($s){
                    $state=[
                        'msg'=>'投票成功！',
                        'code'=>1
                    ];
                    Session::set('nameInfo','think');
                }else{
                    $state=[
                        'msg'=>'投票失败！',
                        'code'=>0
                    ];
                }
            }
        return $state;
    }

    


    public function index()
    {
        //专题页面渲染
        $pollSon= Shortlist::all(function ($query){//入围人物
            $query->where('type',1)->limit(6)->order('poll','desc');
        });

        $pollCom= Shortlist::all(function ($query){//入围企业
            $query->where('type',2)->limit(6)->order('poll','desc');
        });

        $pollPro= Shortlist::all(function ($query){//入围产品
            $query->where('type',3)->limit(6)->order('poll','desc');
        });

        //评审专家
        $pros=Professor::all(function ($query){
            $query->limit(4)->order('create_time','desc');
        });

        //评选动态
        $art=Selection::all(function ($query){
            $query->limit(6)->order('create_time','desc');
        });

        //合作机构
        $parts=Partners::all(function ($query){
            $query->limit(8)->order('create_time','desc');
        });

        $con= Intro::get(1);//奖项简介

        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);

        $this->assign('project1',$pollSon);
        $this->assign('project2',$pollCom);
        $this->assign('project3',$pollPro);
        $this->assign('specialist',$pros);
        $this->assign('art',$art);
        $this->assign('con',$con);
        $this->assign('parts',$parts);


        return $this->fetch('aipiaward/vote');
    }

    public function poll_list(Request $request)
    {
//        入围品牌列表
        $id=$request->param('id');
        $p=new Shortlist();
        $data=$p->where('type',$id)->order('poll','desc')->paginate(12);

        //获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('data',$data);
        $this->assign('page', $page);
        if($id==1){
            $this->assign('pageTitle', '入围人物');
        }else if($id==2){
            $this->assign('pageTitle', '入围企业');
        }else{
            $this->assign('pageTitle', '入围产品');
        }

        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);

        return $this->fetch('aipiaward/p-list');
    }

//        评选动态列表
    public function art_list()
    {
        $p=new Selection();
        $data=$p->paginate(12);

        //获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('data',$data);
        $this->assign('page', $page);
        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);

        return $this->fetch('aipiaward/a-list');
    }

//        评审专家列表
    public function specialist()
    {

        $p=new Professor();
        $data=$p->paginate(12);

        //获取分页显示
        $page = $data->render();
        // 模板变量赋值
        $this->assign('data',$data);
        $this->assign('page', $page);
        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);

        return $this->fetch('aipiaward/z-list');
    }

    /**
     * @param Request $request
     * @return mixed
     * 评审专家详情页
     */
    public function professor_des(Request $request)
    {
        $data=$request->param();
        $con= Professor::get($data['id']);
        $this->assign('con',$con);
        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);
        return $this->fetch('aipiaward/z_details');
    }
//奖项简介
    public function intro_des(Request $request)
    {
        $data=$request->param();
        $con= Intro::get($data['id']);
        $arr=['','艾丕奖简介','活动背景','奖项介绍','区域设置','评选标准','奖项设置','时间安排'];
        $con['title']=$arr[$data['id']];
        $this->assign('con',$con);
        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);
        return $this->fetch('aipiaward/j_details');
    }

    /**
     * @param Request $request
     * @return mixed
     * 资讯详情页
     */
    public function details(Request $request)
    {
        $data=$request->param();

        $con= Selection::get($data['id']);
        //联系我们
        $us=Contact::get(1);
        $this->assign('us',$us);

        $actMode=New Selection();
        $nextInfo=$actMode->where('id','>',$data['id'])->limit(1)->find();
        $prevInfo=$actMode->where('id','<',$data['id'])->limit(1)->find();
        $actMode->allowField(true)->save(['art_view'=>$con['art_view']+1],['id'=>$data['id']]);
        $this->assign('pageTitle',$con['art_title']);
        $this->assign('con',$con);
        $this->assign('nextInfo',$nextInfo);
        $this->assign('prevInfo',$prevInfo);


        return $this->fetch('aipiaward/details');
    }

    /**
     * @return mixed
     * 资料下载
     */
    public function downLoad()
    {
        $data=Datum::all();
        //联系我们
        $isEmpty='1';
        if(empty($data)){
           $isEmpty='0';
        }
        $this->assign('isVal',$isEmpty);
        $us=Contact::get(1);
        $this->assign('us',$us);
        $this->assign('data',$data);
        return $this->fetch('aipiaward/datum_down');
    }

}