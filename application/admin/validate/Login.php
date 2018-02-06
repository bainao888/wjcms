<?php
namespace app\admin\validate;
use think\Validate;
/**
* 登录验证
*/
class Login extends Validate
{
	/*
	  验证规则
	 */
	protected $rule = [
        'username' => 'require',
        'password' => 'require|length:5,12|alphaDash',
        //'code'     => 'require|checkCode:',
    ];
    
    /*
    错误信息
     */
    protected $message  =   [
        'username.require'    => '账号必须填写',
        //'username.email'      => '邮箱格式错误',
        'password.require'    => '密码必须填写',
        'password.length'     => '密码长度在6-12之间', 
        'password.alphaDash'  => '密码由字母和数字，下划线_及破折号-', 
        //'code.require'        => '验证码必须填写',

    ];
    /*
     场景验证
     */
    protected $scene = [
        
    ];  

    // 自定义验证 验证码
    protected function checkCode($value,$rule,$data)
    {

        if(!captcha_check($value)){
        	return $rule = "验证码输入错误";
        }else{
        	return true;
        }
    }
    
}