<?php
/**
 * Created by PhpStorm.
 * User: zhaoxj
 * Date: 2018/9/25
 * Time: 15:57
 */
namespace app\index\controller;
use app\admin\model\Books;
use app\admin\model\Comments;
use app\admin\model\Intro;
use app\admin\model\UserInfo;
use think\Controller;
use think\Request;
use app\index\controller\Ip;

class Mpvue extends Controller{
    /**
     * @param Request $request
     * @return \think\response\Json
     * 1.用户信息保存
     */
    public function user(Request $request)
    {
        header('Access-Control-Allow-Origin: *');

        $data=$request->param();
        $u=new UserInfo();
        $h=$u->where('appid',$data['appid'])->find();
        if(!$h){
            $res=$u->allowField(true)->save($data);
            if($res){
                $state=[
                    'code'=>0,
                    'msg'=>'成功',
                    'title'=>'用户信息保存'
                ];
            }else{
                $state=[
                    'code'=>-1,
                    'msg'=>'失败',
                    'title'=>'用户信息保存'
                ];
            }
        }

        return json($state);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * 2.分页查询图书 页码 page 每页显示记录数 size
     * 图书列表 查找用户的数据，参数 appid
     */
    public function booklist(Request $request)
    {
        header('Access-Control-Allow-Origin: *');

        $data=$request->param();
        $books=new Books();
        if(isset($data['appid'])){
            $res=$books->join('user_info','user_info.appid=books.appid')->where('books.appid',$data['appid'])->order('id','desc')->select();
        }else{
            if($data['page']==0){
                $data['page']=1;
            }
            if($data['size']==0){
                $data['size']==10;
            }
            $res=$books->join('user_info','user_info.appid=books.appid')->limit(($data['page']-1)*$data['size'],$data['size'])->order('id','desc')->select();
        }

        return json(['code'=>0,'msg'=>'成功','data'=>$res]);

    }

    /**
     * @param Request $request
     * 3.添加图书
     * 参数 图书编号 isnb,用户 appid
     */
    public function addbook(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $data=$request->param();
        if(!isset($data['appid'])||!isset($data['isnb'])){
            return json([
                'code'=>-1,
                'msg'=>'参数错误',
                'title'=>'添加图书']);
        }
        $book=new Books();
        $h=$book->where('isnb',$data['isnb'])->find();
        if($h){
            return json(['code'=>-1,'msg'=>'图书已存在']);
        }
        $url = 'https://api.douban.com/v2/book/isbn/'.$data['isnb'];//外部图书库
        $opts = ['http'=>['method'=>"GET",'timeout'=>30]];
        $context = stream_context_create($opts);
        $html =json_decode(file_get_contents($url, false, $context),true);

        $html['rate']=$html['rating']['average'];

        $addBook=[//存入数据库的字段
            'rate'=>$html['rating']['average'],
            'title'=>$html['title'],
            'image'=>$html['image'],
            'price'=>$html['price'],
            'alt'=>$html['alt'],
            'publisher'=>$html['publisher'],
            'summary'=>$html['summary'],
            'isnb'=>$data['isnb'],
            'tags'=>'',
            'author'=>'',
            'appid'=>$data['appid'],
        ];
        $newArr=array();
        foreach ($html['tags'] as $i=>$k){
            $newArr[$i]=$k['name'].' '.$k['count'];
        }
        $addBook['author']=join(',',$html['author']);
        $addBook['tags']=join(',',$newArr);

        $res=$book->allowField(true)->save($addBook);
        if($res){
            $state=[
                'code'=>0,
                'msg'=>'成功',
                'title'=>'添加图书'
            ];
        }else{
            $state=[
                'code'=>-1,
                'msg'=>'失败',
                'title'=>'添加图书'
            ];
        }
        return json($state);
    }

    /**
     * @param Request $request
     * 4.图书详情
     * 参数 图书id
     */
    public function bookdetail(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
       $bookId=$request->param('id');
       $book=new Books();
       if(!isset($bookId)){
           return json(['code'=>-1,'msg'=>'参数错误']);
       }
       $res=$book->join('user_info','books.appid=user_info.appid')->where('books.id',$bookId)->find();
       $book->where('id',$bookId)->setInc('count',1);
       return json(['code'=>0,'msg'=>'成功','data'=>$res]);

    }

    /**
     * 5.热门图书，按照浏览量
     */
    public function bookhots()
    {
        header('Access-Control-Allow-Origin: *');
        $book=new Books();
        $res=$book->order('count','desc')->field('id,count,image,title')->limit(9)->select();

        return json(['code'=>0,'msg'=>'成功','data'=>$res]);

    }

    /**
     * @param Request $request
     * 6.评论列表
     * 参数 所有评论 bookid, 我的评论 appid
     */
    public function commentlist(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $data=$request->param();
        $comm=new Comments();
        if(isset($data['bookid'])){
            $res=$comm->join('user_info','comments.appid=user_info.appid')->where('comments.bookid',$data['bookid'])->order('cid','desc')->select();
        }else if(isset($data['appid'])){
            $res=$comm->join('user_info','comments.appid=user_info.appid')->where('comments.appid',$data['appid'])->order('cid','desc')->select();
        }else{
            return json(['code'=>-1,'msg'=>'参数错误']);
        }

        return json(['code'=>0,'msg'=>'成功','data'=>$res]);

    }

    /**
     * @param Request $request
     * 7.添加评论
     */
    public function addcomment(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $data=$request->param();
        $comm=new Comments();
        if (isset($data['appid'])){
            $res=$comm->allowField(true)->save($data);
            if($res){
                return json(['code'=>0,'msg'=>'添加成功','title'=>'添加评论']);
            }else{
                return json(['code'=>-1,'msg'=>'添加失败','title'=>'添加评论']);
            }
        }else{
            return json(['code'=>-1,'msg'=>'参数错误']);
        }
    }
}