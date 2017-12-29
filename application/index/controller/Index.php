<?php
namespace app\index\controller;

use think\Controller;
use think\Request; //1、Request的使用需要use
use think\Db; //连接数据库需要的use
use think\Session;
use app\common\model\Admin;
class Index extends Controller{
    public $text1,$text2,$text3,$topic1,$topic2,$topic3,$username,$static,$static2;
    
    public function up($user){

        $db = Db::name('first');
        
        $result = Db::table('first')->select();      //table->select()为查找全部内
        $this -> assign('text',$result);
        $username=Request::instance()->session('username');
        if($username == NULL){
        $username = '游客';
        $static = '<a  class="readmore2" href='.url('admin/entry/').'>登录</a>';   //正确a的替换方式
        }else{
        $static = '<a  class="readmore2" href='.url('admin/entry/index').'>个人中心</a> <a  class="readmore2" href='.url('index/bad').'>注销</a>';   
        }
        $this -> assign('username',$username);
        $this -> assign('static',$static);
        $db = Db::name('main_info');
                $result = Db::table('main_info')
                ->where([
                    'author' => $user
                ])
                ->order('Time desc')
                ->limit(5)
                ->select();      //table->select()为查找全部内//参数为统计数组的大小,如果是0就算全部维度，如果是1只统计高维度
                for($id=0;$id<count($result,0);$id++){
                    $result[$id]['year'] =   date('Y',strtotime($result[$id]['time']));         // time('Y',datestrto($result[$id]['time']));
                    $result[$id]['month'] =   date('m',strtotime($result[$id]['time']));
                    $result[$id]['day'] =  date('d',strtotime($result[$id]['time']));
                }
                $this -> assign('main_info',$result);
    }

    public function index(Request $request){
        $content = Db::table('main_info')
        ->order('Time desc')  //逆序输出
        ->limit(5)
        ->select();      //table->select()为查找全部内//参数为统计数组的大小,如果是0就算全部维度，如果是1只统计高维度
        for($i=0;$i<5;$i++){
            $content[$i]['content'] =mb_substr($content[$i]['content'],0,135,"UTF8");
        }
        $res = $username=Request::instance()->session('username');
        if(!$res){
        $static = '<a href='.url('admin/login/login').'><span>登录</span><span class="en">Login</span></a></nav>'; 
        }else{
        $static = '<a href='.url('admin/entry/article_list').'><span>个人中心</span><span class="en">Entry</span></a></nav>'; 
        }
        $this -> assign('static',$static);
        $this -> assign('content',$content);
        return $this -> fetch('first');
    }
    public function geren(){
        $this -> up(request()->param('username'));
        return $this -> fetch('geren');
    }
    public function manage(){
        return $this -> fetch();
    }

    public function login1(Request $request){
    $this -> up();
    return view('admin/entry/login');
       
    }
    public function aboutme(){
        $res = $username=Request::instance()->session('username');
        if(!$res){
        $static = '<a href='.url('admin/login/login').'><span>登录</span><span class="en">Login</span></a></nav>'; 
        }else{
        $static = '<a href='.url('admin/entry/article_list').'><span>个人中心</span><span class="en">Entry</span></a></nav>'; 
        }
        $this -> assign('static',$static);
        return $this -> fetch();
    }
    public function suggest(){
        $res = $username=Request::instance()->session('username');
        if(!$res){
        $static = '<a href='.url('admin/login/login').'><span>登录</span><span class="en">Login</span></a></nav>'; 
        }else{
        $static = '<a href='.url('admin/entry/article_list').'><span>个人中心</span><span class="en">Entry</span></a></nav>'; 
        }
        $this -> assign('static',$static);
        $list = (new Admin())->get_suggest();
        $this -> assign('list',$list);
        return $this -> fetch();
    }
    public function add_suggest()
    {
        if(request()->Ispost()){
            $res = (new Admin())->add_suggest(input('post.')); //执行这个模型 这个.表示传入全部post数据 post.name就只传入name
            if($res['valid']){ //添加成功
                $this -> success($res['msg'],'index/suggest');exit;
            }else{
                $this -> error($res['msg']);exit;
            }
        }
    }
    public function create(){
        
    return view('create');
       
    }
    public function bad(Request $request){
        Session::delete('username');
        return $this->redirect('geren',['username' => requset()->param('username')]);
    }

    public function check(Request $request){
        $db = Db::name('zhuche'); //连接zhuche这个表
        $name=$request -> param('name');
        $username=$request -> param('username');
        $password=$request -> param('password');
        $email=$request -> param('email');
        //dump($request -> param('optionsRadios'));
        if($request -> param('optionsRadios') =='option1'){
            $optionsRadios=1;
        }else{
            $optionsRadios=0;
        }
        $res = Db::table('zhuche')->
        where(['username' => $username ])->value('id');
        if($res != NULL){
            $this -> error('该用户名已经存在');
        }

        $res = Db::table('zhuche')->
        where(['email' => $email ])->value('id');
        if($res != NULL){
            $this -> error('该邮箱已经存在');
        }

        $db->insert([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'sex' => $optionsRadios,
            'username' => $username
        ]);
        $this->success('注册成功', 'admin/entry/login');

    }
    
    public function forgotpassword(){
       return view('forgotpassword');
    }
    public function into_info(Request $request){
        $id = $request -> param('id');
        $res = Db::table('main_info')->where(['id' => $id ])->find();
        $info1 = $res;
        $this -> assign('info1',$info1);
        $info2 = Db::table('zhuche')->where(['username' => $info1['author'] ])->find();
        $this -> assign('info2',$info2);
        return $this -> fetch('info'); 
    }
    public function checklogin(Request $request){
            $db = Db::name('zhuche');
            $username = $request -> param('username');
            $password = $request -> param('password'); 
            $res = Db::table('zhuche')->
             where(['username' => $username ])->value('id');
             if($res==NULL){
                $this -> error('用户名错误');
             }
             $res = Db::table('zhuche')->
             where(['username' => $username ])->value('password');
             if($res != $password){
                 $this -> error('用户名与密码不匹配');
             }
             session('username',$username);
             session('password',$password);
             $this -> up();

             return view('index');
    }
    public function learn(){
        return $this -> fetch();
    }
    public function check3(Request $request){
        $db = Db::name('zhuche');
        $email = $request -> param('email');
        $password = $request -> param('password');            
        $res = $db->where([
            'email' => $email 
        ])->update([
            'password' => $password
        ]);
        if($res != NULL){
            echo "<script>alert('密码更新成功');</script>";
        }else{
            echo "更改失败";
        }
    }
    public function modifypassword(Request $request){
        $rand=rand(1000,9999);
        $db = Db::name('zhuche');
        $email=$request -> param('email');
       // dump($email);
        $res = Db::table('zhuche')->
        where(['email' => $email ])->value('id');
        if($res == NULL){
            $this -> error('不存在这个邮箱');
        }
        SendEmail($email,'blog找回密码',
        '您好,尊敬的 '.$email.' 用户<br>
        亲爱的用户,这是blog找回密码,您的博客密码找回验证码为:'.$rand.',<br>请妥善保存此验证码,勿向任何人透露。');
        $this->assign('email',$email);
        $this->assign('rand',$rand);
        return $this -> fetch();
    }
    
    public function someone(Request $reqeust){
        $id = $request -> param('id');
    }
    public function error1(){
        return $this -> error('此处功能未开发');
    }
}
?>