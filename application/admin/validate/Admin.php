<?php
namespace app\admin\validate;
use think\Validate;
/**
* 登录验证
*/
class Admin extends Validate
{
	/*
	  验证规则
	 */
	protected $rule = [
        'usergroup'=> 'require',
        'username' => 'require',
        'nickname' => 'require',
        'password' => 'require|length:5,12|alphaDash',
        //'code'     => 'require|checkCode:',
    ];
    
    /*
    错误信息
     */
    protected $message  =   [
        'usergroup.require'   => '用户组必须选择',
        'username.require'    => '账号必须填写',
        'nickname.require'    => '姓名必须填写',
        'password.require'    => '密码必须填写',
        'password.length'     => '密码长度在6-12之间', 
        'password.alphaDash'  => '密码由字母和数字，下划线_及破折号-', 
        //'code.require'        => '验证码必须填写',

    ];
    /*
     场景验证
     */
    protected $scene = [
        'edit'=> ['nickname'],
        'pwd' => ['password'],
    ];  

   
    
}