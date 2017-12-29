<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Admin;

class Login extends Controller
{
    public function login(){
        if(request()->Ispost()){
            $res = (new Admin())->login(input('post.')); //执行这个模型 这个.表示传入全部post数据 post.name就只传入name
            if($res['valid']){ //登录成功
                $this -> success($res['msg'],'admin/entry/index');exit;
            }else{
                $this -> error($res['msg']);exit;
            }
        }
        //然后加载
        return $this -> fetch(); //返回本模块下，view视图的login下的login.html 
    }
    public function login1(){
        return $this->fetch();
    }
}