<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/4/23
 * Time: 16:42
 */

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Datum as DatumModel;
use think\Request;


class Datum extends Base{

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=DatumModel::all();
        $this->assign('data',$data);
        return $this->fetch('list');
    }

    /**
     * 显示创建图片表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return $this->fetch('add');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
        if($request->isAjax(true)){
            $data=$request->param();
            $banner=new DatumModel();
            $res=$banner->allowField(true)->save($data);
            if ($res) {
                $state = [
                    'msg' => '新增成功!',
                    'code' => 1
                ];

            } else {
                $state = [
                    'msg' => '新增失败!',
                    'code' => 0
                ];
            }
            return $state;
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete(Request $request)
    {
        //
        $id=$request->param();

        $res=DatumModel::destroy($id['id'],true);
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


    public function upload(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/download');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='download/'.$path;

            return $filepath;
        }
    }
}