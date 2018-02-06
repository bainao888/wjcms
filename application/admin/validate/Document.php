<?php
namespace app\admin\validate;
use think\Validate;
/**
* 验证
*/
class Document extends Validate{

	/*
	  验证规则
	 */
	protected $rule = [
        'title' => 'require',
        'content' => 'require',
        
    ];
    
    /*
    错误信息
     */
    protected $message  =   [
        'title.require'    => '标题必须填写',
        'content.require'      => '内容不能为空',

    ];
}
