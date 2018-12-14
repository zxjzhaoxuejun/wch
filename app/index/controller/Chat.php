<?php
/**
 * Created by PhpStorm.
 * User: 赵学军
 * Date: 2018-06-23
 * Time: 14:32
 小程序
 */
namespace app\index\controller;
use think\Controller;
use app\admin\model\Article;
use app\admin\model\MemberService;//协会服务
use app\admin\model\MemberCompany;//会员单位
use app\admin\model\Counselor;//专家顾问
use app\admin\model\System;
use app\admin\model\Banner;//轮播图
use app\admin\model\AboutUs;
use app\admin\model\Leader;//协会领导
use app\admin\model\Partisan;//理事单位
use app\admin\model\Download;//下载专区
use app\admin\model\Schools;//双创学院
use think\Db;
use think\Request;

class Chat extends Controller{
    /**
     * @return string
     * 1.首页接口
     */
    public function index_all()
    {
        header('Access-Control-Allow-Origin: *');
        $hots= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
            $query->where('art_statue',5)->order('create_time','desc')->limit(3);
        });//焦点新闻
        $com=MemberCompany::all(function ($query){
            $query->order('create_time','desc')->limit(6);
        });//会员单位
        $cou=Counselor::all(function ($query){
            $query->order('create_time','desc')->limit(6);
        });//网创智库
        $sch=Schools::all(function ($query){
            $query->order('create_time','desc')->limit(2);
        });//网创学院
        $art=Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
            $query->where('art_statue=2 or art_statue=5')->order('create_time','desc')->limit(3);
        });//热门资讯
        $data=array();
        $data['company']=$com;
        $data['counselor']=$cou;
        $data['schools']=$sch;
        $data['article']=$art;
        $data['hots']=$hots;
        $json =json_encode($data,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return mixed
     * 2.小程序登录获取openID
     */
    public function login(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $data=$request->param();
        //声明code ,用前端传过来的code
        $code=$data['code'];
        //公众号小程序appid
        $appId='wx967a1ca2a1831045';
        //公众号秘钥
        $appSecret='3246a2f00afadac102f9cc1bafdbd31c';
        //api
        $api="https://api.weixin.qq.com/sns/jscode2session?appid={$appId}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code";

        //获取发送
        function httpGet($url){
            $curl=curl_init();
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl,CURLOPT_TIMEOUT,500);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,true);
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,true);
            curl_setopt($curl,CURLOPT_URL,$url);
            $res=curl_exec($curl);
            curl_close($curl);

            return $res;
        }
        $str=httpGet($api);
        dump($str);
    }

    /**
     * @return string
     * 3.协会服务接口
     */
    public function service()
    {
        header('Access-Control-Allow-Origin: *');
        $s=MemberService::all();
        $json =json_encode($s,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return string
     * 4.资讯中心列表接口
     */
    public function news(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $data=$request->param();
        $id=$data['id'];
        //每次显示记录数
        $size=10;
        //确定页数p参数
        if($data['page']){
            $p = $data['page']?$data['page']:1;
        }else{
            $p =1;
        }
        //数据指针
        $offset = ($p-1)*$size;
        $art=Db::query("select id,art_title,create_time,abstract,art_thumb,art_view from article WHERE cate_id=$id ORDER BY create_time DESC LIMIT $offset,$size");
        $json =json_encode($art,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     *5.资讯详情页
     */
    public function details(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $data=$request->param();
        $id=$data['id'];
        $info=Db::query("select *from article WHERE id=$id");
        $view=$info[0]['art_view']+1;
        Db::query("UPDATE article SET art_view=$view WHERE id=$id");
        $json=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     *6.网创学院详情页
     */
    public function s_details(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $data=$request->param();
        $id=$data['id'];
        $info=Db::query("select *from schools WHERE id=$id");
        $view=$info[0]['art_view']+1;
        Db::query("UPDATE schools SET art_view=$view WHERE id=$id");
        $json=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     *7.网创学院列表
     */
    public function schools()
    {
        header('Access-Control-Allow-Origin:*');
        $info=Db::query("select * from schools ORDER BY create_time DESC");
        $json=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return string
     * 8.网创智库、协会领导列表
     */
    public function personList(Request $request)
    {
        $data=$request->param();
        if($data['id']==1){//网创智库
            $info=Counselor::all(function ($query){
                $query->order('create_time','desc');
            });
        }else if($data['id']==2){//协会领导页
            $info=Leader::all(function ($query){
                $query->order('id','asc');
            });
        }

        $json=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return string
     * 9.会员单位、理事单位列表
     */
    public function members(Request $request)
    {
        $data=$request->param();
        $id=$data['id'];
        //每次显示记录数
        $size=10;
        //确定页数p参数
        if($data['page']){
            $p = $data['page']?$data['page']:1;
        }else{
            $p =1;
        }
        //数据指针
        $offset = ($p-1)*$size;
        if($id==1){//会员单位
            $rss="select * from member_company WHERE show_val=1 ORDER BY create_time DESC LIMIT $offset,$size";
        }else if($id==2){//理事单位
            $rss="select * from partisan WHERE show_val=1 ORDER BY create_time DESC LIMIT $offset,$size";
        }
        $info=Db::query($rss);
        $json=json_encode($info,JSON_UNESCAPED_UNICODE);

        return $json;
    }

    /**
     * @param Request $request
     * @return string
     * 10.会员单位、理事单位详情页
     */
    public function memberDetails(Request $request)
    {
        $data=$request->param();
        $id=$data['id'];
        $type=$data['type'];
        if($type==1){//会员单位
            $rss=MemberCompany::get($id);
        }else if($type==2){//理事单位
            $rss=Partisan::get($id);
        }
        $json=json_encode($rss,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return string
     * 11.协会介绍，组织架构，入会指南，分支机构
     */
    public function about(Request $request)
    {
        $data=$request->param();
        $id=$data['id'];
        if($id==1){//协会介绍
            $rss="select abstract from about_us";
        }else if($id==2){//组织架构
            $rss="select framework from about_us";
        }else if($id==3){//分支机构
            $rss="select organization from about_us";
        }else if($id==4){//入会指南
            $rss="select initiation from about_us";
        }
        $info=Db::query($rss);
        $json=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * @param Request $request
     * @return array
     * 申请入会
     */
    public function register(Request $request)
    {
        $data=$request->param();
        $data['type']=intval($data['type'])+1;
        if($data['type']==1){//会员单位
            $sys=new MemberCompany();
            $res=$sys->allowField(true)->save($data);
        }else{//理事单位、副会长单位，联席会长单位
            $sys=new Partisan();
            $res=$sys->allowField(true)->save($data);
        }
        if($res){
            $state=[
                'msg'=>'提交成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'提交失败!',
                'code'=>0
            ];
        }
        return json_encode($state,JSON_UNESCAPED_UNICODE);
    }


}