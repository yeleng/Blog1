<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Admin;

class Entry extends Controller
{
    public function __construct (Request $request = null )
    {
        parent::__construct($request);
        //执行登录界面
        if(!session('username')){ //在对后台的操作中,如果没有登录就跳回登录界面
            $this -> redirect('admin/login/login');
        }
    }

    public function index()
    {    // 加载默认文件,也就是初始化的页面
    
        return $this -> redirect('entry/article_list');
    }

    public function pass()
    {
        if(request()->isPost())
        {
            $res = (new Admin())->pass(input('post.'));
            if($res['valid']){
                $this -> success($res['msg'],'entry/pass');exit;
            }else{
                $this -> error($res['msg']);
            }
        }
        return $this -> fetch('pass');
    }

    public function info()
    {
        if(request()->isPost())
        {
            $res = (new Admin())->info(input('post.'));
            if($res['valid']){
                $this -> success($res['msg'],'entry/info');exit;
            }else{
                $this -> error($res['msg']);
            }
        }
        return $this -> fetch('info');
    }
    public function modify_power(){
        return $this -> fetch();
    }
    public function article_list()
    {
        $list = (new Admin())->get_list();
        $this -> assign('list',$list);
        return $this -> fetch();
    }
    public function article_add(){
        if(request()->isPost())
        {
            $username=Request::instance()->session('username');
            $res = (new Admin())->article_add(input('post.'),$username);
            if($res['valid']){
                $this -> success($res['msg'],'entry/article_list');exit;
            }else{
                $this -> error($res['msg']);
            }
        }
        return $this -> fetch();
    }
    public function article_deleted(){
        $list = (new Admin())->get_list2();
        $this -> assign('list',$list);
        return $this -> fetch();
    }
    
    public function article_delete(Request $request){
        if(request()->isPost()){
            dump('success');
            return 0;
        }
        $res = (new Admin())->article_delete($request->param('id'));
        if($res['valid']){
            $this -> success($res['msg'],'entry/article_list');exit;
        }else{
            $this -> error($res['msg']);
        }
    }

    public function article_push(Request $request){
        $res = (new Admin())->article_push($request->param('id'));
        if($res['valid']){
            $this -> success($res['msg'],'entry/article_list');exit;
        }else{
            $this -> error($res['msg']);
        }
    }
    public function article_modify(Request $request){
        if(request()->isPost())
        {
        $res = (new Admin())->article_modify2(input('post.'));
        if($res['valid']){
            $this -> success($res['msg'],'entry/article_list');exit;
        }else{
            $this -> error($res['msg']);
        }
        }else{
        $res = (new Admin())->article_modify($request->param('id'));
        $this -> assign('res',$res);
        }
        return $this->fetch();
    }
    public function inPage(){
       return  $this -> error('这个功能还未开发');
    }
}
