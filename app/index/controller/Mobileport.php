<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/6/5
 * Time: 15:38
 * 请求接口
 */
namespace app\index\controller;
use think\Controller;
use app\admin\model\Article;
use app\admin\model\MemberService;//协会服务
use app\admin\model\Nation;//国家
use think\Request;

class Mobileport extends Controller{

    public function index(){
        return $this->fetch('fifa/index');
    }

    public function page2(){
        return $this->fetch('fifa/page2');
    }

    public function page3(){
        return $this->fetch('fifa/page3');
    }

    public function nation(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $data=$request->param();
       $m=(new Nation())->ranking();
       $list =json_encode($m,JSON_UNESCAPED_UNICODE);

       return $list;
    }

    public function hits(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT');
        $data=$request->param();
        dump($data['nation']);
        $m=new Nation();
        $v=$m->where('nation','=',$data['nation'])->find();
        $m->allowField(true)->save(['number'=>$v['number']+1],['nation'=>$data['nation']]);
    }

    /**
     *
     */
    public function news(Request $request)
    {
        $data=$request->param();
        $id=$data['id'];
        if($id==2){
            $yjData= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
                $query->where('cate_id',2)->order('create_time','desc');
            });
        }else if($id==3){
            $yjData= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
                $query->where('cate_id',3)->order('create_time','desc');
            });
        }else if($id==4){
            $yjData= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
                $query->where('cate_id',4)->order('create_time','desc');
            });
        }else if($id==5){
            $yjData= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
                $query->where('cate_id',5)->order('create_time','desc');
            });
        }else{
            $yjData= Article::all(function ($query){//2业界动态,3.协会动态，4.会员资讯，5.政策信息
                $query->order('create_time','desc');
            });
        }

        $newList =json_encode($yjData,JSON_UNESCAPED_UNICODE);
        return $newList;
    }
    public function hot_news()
    {
        $yjData= Article::all(function ($query){//热门资讯
            $query->where('art_statue=2 or art_statue=5')->limit(5)->order('create_time','desc');
        });
        $newList =json_encode($yjData,JSON_UNESCAPED_UNICODE);
        return $newList;
    }

    public function services()
    {
        $services=MemberService::get(1);//会员服务
        $servicesInfo = json_encode($services,JSON_UNESCAPED_UNICODE);
        return $servicesInfo;
    }

}