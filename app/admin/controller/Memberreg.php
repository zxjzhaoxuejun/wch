<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/10/18
 * Time: 17:05
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\MemberReg as RegModel;
use think\Request;

class Memberreg extends Base{
    /**
     * 会员列表
     */
    public function index()
    {
        $data=RegModel::paginate(20);
        $page=$data->render();
        $count=RegModel::count();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $this->assign('count',$count);
        return $this->fetch('memberreg/list');
    }

    /**
     * 删除指定资源
     *
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        $id=$request->param();
        $res=RegModel::destroy($id['rid'],false);
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