<?php

namespace app\admin\controller;

use app\admin\common\Base;

use app\admin\model\SystemLog;
use think\Request;

use app\admin\model\System as SystemModel;
class System extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $data=SystemModel::get(1);
        $this->assign('data',$data);
        return $this->fetch('system_base');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
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
            $sys=new SystemModel();
            $res=$sys->allowField(true)->save($data,['id'=>1]);
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
            return $state;
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

//  屏蔽词
    public function shielding()
    {
        $data=SystemModel::get(1);
        $this->assign('data',$data);
        return $this->fetch('system_shielding');
    }

//    系统消息
    public function log()
    {
        $log=SystemLog::all();
        $this->assign('log',$log);
        $count=SystemLog::count();
        $this->assign('count',$count);
        return $this->fetch('system_log');
    }

//    删除系统消息
    public function deletelog(Request $request)
    {
        $id=$request->param();
        $res=SystemLog::destroy($id['id'],true);

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
//    修改信息阅读状态
    public function statue(Request $request)
    {
        if($request->isAjax(true)){
            $data=$request->param();
            if($data['statue']!="已读"){
                $log=new SystemLog();
                $res=$log->allowField(true)->save(['statue'=>1],['id'=>$data['id']]);
                if($res){
                    $state=[
                        'msg'=>'',
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
    }

    //上传图片
    public function upload(Request $request)
    {
        $file=$request->file('file');
        if($file->isValid()){//检验上传文件是否有效
            $info=$file->rule('date')->move('static/uploads');//移动文件路径并重新命名
            $path=$info->getSaveName();//获取保存后文件的名字和位置
            $filepath='uploads/'.$path;

            return $filepath;
        }
    }

}
