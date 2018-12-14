<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/6/5
 * Time: 15:38
 */
namespace app\index\controller;
use think\Controller;
use app\admin\model\Article;
class Mobileport extends Controller{
    public function news()
    {
        $yjData= Article::all(function ($query){//业界动态
            $query->where('cate_id',2)->limit(6)->order('create_time','desc');
        });
        $newList = json($yjData,true);
        return $newList;
    }
}