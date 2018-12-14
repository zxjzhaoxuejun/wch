<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/6/13
 * Time: 13:26
 */
namespace app\admin\model;
use think\Model;

class Nation extends Model{
    public function ranking()
    {
        $arr=array();
        $m=new Nation();
        $data=$m->order('number','desc')->select();
        foreach ($data as $v=>$k){
            $data[$v]['ranking']=$v+1;
            $arr[]=$data[$v];
        }

        return $arr;

    }
}