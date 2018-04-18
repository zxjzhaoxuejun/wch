<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/27
 * Time: 13:23
 */

namespace app\admin\model;

use think\Model;

class Nav extends Model{
    protected $autoWriteTimestamp=true;

    public function tree()
    {
        $navs=$this::all(function ($query){
            $query->order('nav_order','asc');
        });
        return $data=$this->getTree($navs,'nav_id','nav_pid','nav_name',0);
    }

//    修改类型层次输出
    public function getTree($data,$filed_id='id',$filed_pid='pid',$filed_name,$pid=0)
    {
        $arr=array();
        foreach ($data as $k=>$v) {
            if($v->$filed_pid==$pid){
                $data[$k]['_'.$filed_name]=$data[$k][$filed_name];
                $arr[]=$data[$k];
                foreach ($data as $m=>$n){
                    if($n->$filed_pid==$v->$filed_id){
                        $data[$m]['_'.$filed_name]=' ├─ '.$data[$m][$filed_name];
                        $arr[]=$data[$m];
                    }
                }
            }
        }

        return $arr;
    }

//    导航输出
    public function navOut()
    {
        $navData=$this::all(function ($query){
            $query->order('nav_order','asc');
        });
        return $data=$this->getNav($navData,'nav_id','nav_pid','nav_name',0);
    }

    public function getNav($data,$filed_id='id',$filed_pid='pid',$filed_name,$pid=0)
    {
        $arr=array();
        foreach ($data as $k=>$v) {
            if($v->$filed_pid==$pid){
                $arr[]=$data[$k];
                $b=array();
                foreach ($data as $m=>$n){
                    if($n->$filed_pid==$v->$filed_id){
                        $b[]=$data[$m];
                        $data[$k]['list']=$b;
//                        $arr[]=$data[$k];
                    }
                }
            }
        }

        return $arr;
    }


}