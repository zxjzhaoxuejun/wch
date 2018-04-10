<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/20
 * Time: 14:40
 */
namespace app\admin\model;

use think\Model;

class Category extends Model{
    protected $autoWriteTimestamp=true;

    public function tree()
    {
        $categorys=$this::all(function ($query){
            $query->order('cate_order','asc');
        });
        return $data=$this->getTree($categorys,'cate_id','cate_pid','cate_name',0);
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

}