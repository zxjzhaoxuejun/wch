<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/12/18
 * Time: 10:29
 */
namespace app\admin\controller;

use app\admin\common\Base;
use think\Db;

class Imocc extends Base{
    public function index()
    {
        $db=Db::name('imocc');
        $data=[];
        for ($i=0;$i<20;$i++){
            $data[]=[
                'username'=>"imocc_{$i}",
                'email'=>"imocc_{$i}@qq.com",
                'num'=>$i+100,
            ];
        }
        $res=$db->insertAll($data);

        /**
         * 备注信息
         * EQ =
         * NEQ <>
         * LT <
         * ELT <=
         * GT >
         * EGT >=
         * BETWEEN  BETWEEN * AND *
         * NOTBETWEEN  NOTBETWEEN * AND *
         * IN  IN(*,*)
         * NOTIN NOT IN (*,*)
         */

      $res=Db::table('imocc')
          ->where('id','GT',5)
          //设置获取的字段
          ->field("username,id")
          //设置排序desc倒序，asc顺序
          ->order("id desc")
//          设置取记录区域
//          ->limit(3)
//          分页获取记录
//          ->page(2,5)
//          分组获取记录
          ->group('num')
          ->select();

        dump($res);

    }
}