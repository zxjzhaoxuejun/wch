<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/5/24
 * Time: 9:04
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Recruitment as RecruitmentModel;
use think\Request;

class Recruitment extends Base{
    public function index()
    {
        $data=RecruitmentModel::get(1);
        $this->assign('info',$data);
        return $this->fetch('edit');
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        //
        if ($request->isAjax(true)){
            $data=$request->param();
            $info=new RecruitmentModel();
            if($data['id']){
                $res=$info->allowField(true)->save($data,['id'=>$data['id']]);
            }else{
                $res=$info->allowField(true)->save($data);
            }

            if($res){
                $state=[
                    'msg'=>'保存成功!',
                    'code'=>1
                ];
            }else{
                $state=[
                    'msg'=>'保存失败!',
                    'code'=>0
                ];
            }
        }
        return $state;


    }
}