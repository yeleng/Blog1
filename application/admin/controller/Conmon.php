<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Conmon extends Controller
{
    public function __construct (Request $request = null )
    {
        parent::__construct($request);
        //执行登录界面
    } 
    public function index()
    {
    
    }
}
