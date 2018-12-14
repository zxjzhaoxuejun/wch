<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/7/13
 * Time: 10:57
 */
namespace app\index\controller;
use think\Controller;
use app\admin\model\React as ReactModel;
use think\Request;

class React extends Controller{
    /**
     * @return string
     * 数据展示
     */
    public function show()
    {
        header('Access-Control-Allow-Origin: *');
        $data=ReactModel::all();

        $json =json_encode($data,JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * 添加数据
     * @param Request $request
     */
    public function create(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
        $data=$request->param();
        $r=new ReactModel();
        $res=$r->allowField(true)->save($data);

        if($res){
            $back=$r->get($r->id);
            $state=[
                'msg'=>'提交成功!',
                'code'=>1,
                'data'=>$back
            ];
        }else{
            $state=[
                'msg'=>'提交失败!',
                'code'=>0
            ];
        }
        return json_encode($state,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * 修改数据
     */
    public function update(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
        $data=$request->param();
        $r=new ReactModel();
        $res=$r->allowField(true)->save($data,['id'=>$data['id']]);
        if($res){
            $state=[
                'msg'=>'提交成功!',
                'code'=>1,
                'data'=>$r->get($r->id)
            ];
        }else{
            $state=[
                'msg'=>'提交失败!',
                'code'=>0
            ];
        }
        return json_encode($state,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return array
     * 删除数据
     */
    public function delete(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
        $data=$request->param();
//        $res=ReactModel::destroy($data['id'],true);
        $r=new ReactModel();
        $res=$r->where('id',$data['id'])->delete();
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
        return json_encode($state,JSON_UNESCAPED_UNICODE);
    }
}