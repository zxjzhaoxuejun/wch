<?php

namespace app\admin\controller;

use app\admin\common\Base;

use think\Request;
use app\admin\model\Characters as CharactersModel;


class Characters extends Base
{
    /**
     * 显示列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data= CharactersModel::all();
        $this->assign('data',$data);

        return $this->fetch('list');
    }


     /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $id=$request->param();

        $res=CharactersModel::destroy($id['id'],true);

        if($res){
            $state=[
                'msg'=>'删除成功!',
                'code'=>1
            ];
        }else{
            $state=[
                'msg'=>'删除失败!',
                'code'=>0
            ];
        }
        return $state;
    }
}
