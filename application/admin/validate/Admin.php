<?php
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [ //判断的时候应该输入这3个信息
        'username' => 'require',
        'password' => 'require',   //这里require是一个验证规则，其为判断是否为空
        'code' => 'require|captcha'   //如果一个变量有多个验证规则
    ];
    protected $message = [
        'username.require' => '请输入用户名',
        'password.require' => '请输入密码',
        'code.require' => '请输入验证码',
        'code.captcha' => '验证码不正确'        //这里的验证规则还需要验证码有name
    ];
}
