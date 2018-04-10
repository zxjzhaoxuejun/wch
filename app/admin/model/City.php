<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/28
 * Time: 14:51
 */
namespace app\admin\model;

use think\Model;

class City extends Model{

    //三级分类
    public function tree()
    {
        $city=$this::all(function ($query){
            $query->order('order','asc');
        });
        return $data=$this->getTree($city,'id','pid','name',0,3);
    }

//    两级分类
    public function tree_2()
    {
        $city=$this::all(function ($query){
            $query->order('order','asc');
        });
        return $data=$this->getTree($city,'id','pid','name',0,2);
    }

//    修改类型层次输出type->2表示分两层，3表示分3层
    public function getTree($data,$filed_id='id',$filed_pid='pid',$filed_name,$pid=0,$type=3)
    {
        $arr=array();
        foreach ($data as $k=>$v) {
            if($v->$filed_pid==$pid){
                $data[$k]['_'.$filed_name]=$data[$k][$filed_name];
                $arr[]=$data[$k];
                foreach ($data as $m=>$n){//市
                    if($n->$filed_pid==$v->$filed_id){
                        $data[$m]['_'.$filed_name]=' ├─ '.$data[$m][$filed_name];
                        $arr[]=$data[$m];
                        if($type==3){
                            foreach ($data as $q=>$h){//区
                                if($h->$filed_pid==$n->$filed_id){
                                    $data[$q]['_'.$filed_name]=' ├── '.$data[$q][$filed_name];
                                    $arr[]=$data[$q];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $arr;
    }

}