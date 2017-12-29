<?php
namespace app\index\controller;

use think\Controller;
use think\Request; //1、Request的使用需要use
use think\Db; //连接数据库需要的use
use think\Session;

//use ../css/bootstrap.min.css;

class Index2 extends Controller{
    public function index(){
       throw new Exception('请求不被允许',405);
}
}
?>