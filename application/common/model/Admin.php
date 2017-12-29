<?php
namespace app\common\model;

use think\Model;
use think\Loader;
use think\Db;
use think\Validate;

class Admin extends Model
{
    protected $table = 'zhuche'; //当前数据表的名字
    /*
    登录
    */
    public function login($data){
       //1.执行验证
       $validate = Loader::validate('Admin'); //Admin文件下的验证信息
       
       if(!$validate->check($data)){  //判断的是data的信息
        return ['valid'=> 0 ,'msg'=> $validate->getError()];
       }
       //2.比对用户名与密码是否正确
       $res =Db::table($this ->table)->where(['username' => $data['username']])->where(['password' => $data['password']])->find();  //按照多条件查询数据库
       if(!$res){
           return ['valid' => 0 , 'msg' => '用户名帐号或密码不正确'];
       }
       //3.如果数据无问题,将用户信息存入session
        //这里涉及密码的加密与解密(跳过)
        session('username',$data['username']);
        return ['valid' => 1,
                'msg' => '登录成功'];
    }
    /**
     * 修改密码
     */
    public function pass($data)
    {
        //1.执行验证
        $validate = new Validate([       //因为这个类不是扩展了的Validate,任何时刻都可以用这个类来验证
            'old_password' => 'require',
            'password' => 'require',
            're_password' => 'require|confirm:password'       //confirm:password 是一个验证规则，就是验证2个是否相同
        ],[
            'old_password.require' => '请输入原始密码',
            'password.require' => '请输入新密码',
            're_password.require' => '请确认您的新密码',
            're_password.confirm'=> '确认密码与新密码不符合'       //在confirm这个验证规则下不符合
        ]
    );
        if(!$validate->check($data)){    //注意这个函数的调用需要validate
            return ['valid'=>0,'msg'=> $validate ->getError()];
            dump($validate->getError());
        }
        //2.对比用户名密码是否正确
        $username = session('username');
        $res =Db::table($this ->table)->where(['username' => $username ])->find();
        if($res['password']!=$data['old_password']){
            return ['valid' => 0, 'msg' => '旧密码错误'];
        }
        //3.修改密码
        Db::table($this ->table)->where(['username' => $username])->update(['password' => $data['password']]);
        return ['valid'=>1, 'msg'=> '密码修改成功'];
    }
    /**
     * 修改个人信息(名字&邮箱)
     */
    public function info($data)
    {
        //1.执行验证
        $validate = new Validate([       //因为这个类不是扩展了的Validate,任何时刻都可以用这个类来验证
            'old_password' => 'require',
            'email' => 'require',
            'name' => 'require',       //confirm:password 是一个验证规则，就是验证2个是否相同
            'intro' => 'require'
        ],[
            'old_password.require' => '请输入原密码',
            'email' => '请输入新邮箱号',
            'name' => '请输入新名字',    //在confirm这个验证规则下不符合
            'intro' => '请输入个人介绍'
            ]
    );
        if(!$validate->check($data)){    //注意这个函数的调用需要validate
            return ['valid'=>0,'msg'=> $validate ->getError()];
            dump($validate->getError());
        }
        //2.对比用户名密码是否正确
        $username = session('username');
        $res =Db::table($this ->table)->where(['username' => $username ])->find();
        if($res['password']!=$data['old_password']){
            return ['valid' => 0, 'msg' => '旧密码错误'];
        }
        //3.修改邮箱和名字 
        Db::table($this ->table)->where(['username' => $username])->update(['email' => $data['email'],'name' => $data['name'],'intro' => $data['intro']]);
        return ['valid'=>1, 'msg'=> '新信息修改成功'];
    }
    /**
     * 查找文章列表内容
     */
    public function get_list()
    {
        $list = Db::name('main_info')->order('Time desc')->paginate(10);
        return $list;
    }
    
    public function get_suggest(){
        $list = Db::name('suggest')->order('Time desc')->paginate(10);
        return $list;
    }
    public function get_list2(){
        $list = Db::name('deleted_info')->order('Time desc')->paginate(10);
        return $list;
    }
    /*添加建议
    */
    public function add_suggest($data){
        // 1、执行验证
         $validate = new Validate([       //因为这个类不是扩展了的Validate,任何时刻都可以用这个类来验证
            'username' => 'require',       //confirm:password 是一个验证规则，就是验证2个是否相同
            'content' => 'require',
            'phone' => 'require'
        ],[
            'username.require' =>  '请输入作者名',
            'content.require' => '请输入需要留言内容',
            'phone.require' => '请输入练习方式'
            ]
    );
    if(!$validate->check($data)){    //注意这个函数的调用需要validate
            return ['valid'=>0,'msg'=> $validate ->getError()];
        }
  //2、传入数据库
  $db =Db::name('suggest');

   $res=$db->insert([
 'username' => $data['username'],
 'content' => $data['content'],
 'phone_number' => $data['phone'],
 'time' => date('Y-m-d',time())
 ]);    
  if(!$res){
      return ['valid'=>0,'msg'=> '添加留言失败'];
  }
  return ['valid'=>1, 'msg' => '添加留言成功'];
    }
    /**
     * 文章的一个添加
     */
    public function article_add($data,$username){
        $res = Db::name('main_info')->insert([
            'time' => date('Y-m-d',time()),
            'title' => $data['title'],
            'content' => $data['content'],
            'author' => $username
            ]);
            if(!$res){
            return ['valid'=>0,'msg'=>'文章添加失败'];
        }
        return ['valid'=>1, 'msg'=>'文章添加成功'];
    }
    public function article_delete($id){
        $res = Db::name('main_info')->where(['id'=> $id])->find();
        if(!$res){
            return ['valid'=>'0','msg'=>'数据查询失败'];
        }
        $db = Db::name('deleted_info');
        $res2 = $db->insert([
            'id' => $res['id'],
            'title' => $res['title'],
            'content' => $res['content'],
            'time' => $res['time'],
            'author' => $res['author']
        ]);
        if(!$res2){
            return ['valid'=>'0','msg'=>'数据插入回收站失败'];
        }
        $res = Db::name('main_info')->where(['id'=> $id])->delete();
        if(!$res2){
            return ['valid'=>'0','msg'=>'数据删除失败'];
        }
        return ['valid'=>'1','msg'=>'数据删除成功'];
    }

    public function article_push($id){
        $res = Db::name('deleted_info')->where(['id'=> $id])->find();
        if(!$res){
            return ['valid'=>'0','msg'=>'数据查询失败'];
        }
        $db = Db::name('main_info');
        $res2 = $db->insert([
            'id' => $res['id'],
            'title' => $res['title'],
            'content' => $res['content'],
            'time' => $res['time'],
            'author' => $res['author']
        ]);
        if(!$res2){
            return ['valid'=>'0','msg'=>'数据恢复失败'];
        }
        $res = Db::name('deleted_info')->where(['id'=> $id])->delete();
        if(!$res2){
            return ['valid'=>'0','msg'=>'数据恢复失败'];
        }
        return ['valid'=>'1','msg'=>'数据恢复成功'];
    }

    public function article_modify($id){
        $res = Db::name('main_info')->where(['id'=> $id])->find();
        return $res;
    }
    public function article_modify2($data){
        $db = Db::name('main_info');
        $res = $db->where([
            'id'=> $data['id']
            ])->update([
            'title' => $data['title'],
            'content' => $data['content']
        ]);
        if(!$res){
            return ['valid'=>0,'msg'=>'文章修改失败'];
        }
        return ['valid'=>1,'msg'=>'文章修改成功'];
    }
}