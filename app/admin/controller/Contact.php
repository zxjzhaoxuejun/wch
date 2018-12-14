<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/11
 * Time: 13:21
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Contact as ContactModel;
use think\Request;

class Contact extends Base{
    public function index()
    {
        $data=ContactModel::get(1);
        $this->assign('data',$data);
        return $this->fetch('show');
    }

    public function save(Request $request)
    {
        $data=$request->param();
        $m=new ContactModel();
        $i=$m->count();
        if($i){
            $res=$m->allowField(true)->save($data,['id'=>1]);
        }else{
            $res=$m->allowField(true)->save($data);
        }

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