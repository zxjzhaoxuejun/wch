<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj3
 * Date: 2017/11/28
 * Time: 14:42
 */

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Province;
use think\Request;

class City extends Base{

    public function index()
    {
        $data=(new Province())->tree();
        $this->assign('data',$data);
        return $this->fetch('city_data');
    }

    public function create()
    {
        $data=(new Province())->tree_2();
        $this->assign('data',$data);
        return $this->fetch('city_add');
    }

    public function save(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $city=new Province();
            $res=$city->allowField(true)->save($data);
            if($res){
                $state=[
                    'msg'=>'新增成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'新增失败!',
                    'code'=>0
                ];
            }
        }else{
            $state=[
                'msg'=>'请求类型错误!',
                'code'=>0
            ];
        }
        return $state;
    }

    public function edit(Request $request)
    {
        $data=(new Province())->tree_2();
        $this->assign('data',$data);
        $info=Province::get($request->param('id'));
        $this->assign('info',$info);
        return $this->fetch('city/city_edit');
    }

    //    修改提交
    public function update(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            $nav=new Province();
            $res=$nav->allowField(true)->save($data,['id'=>$data['id']]);

            if($res){
                $state=[
                    'msg'=>'修改成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'修改失败!',
                    'code'=>0
                ];
            }
            return $state;
        }
    }

}