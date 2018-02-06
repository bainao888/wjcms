<?php
namespace app\admin\controller;
use think\Controller;
use think\Log;
/**
* 日志
*/
class Logs extends Base
{
	public function _initialize(){

		Log::init([
		    'type'  =>  'File',
		    'path'  =>  APP_PATH.'logs/'
		]);
	}

	public function lists(){

		return $this->fetch();
	}

	public function test(){
		//echo __STATIC__;exit;
		//Log::record('测试日志信息');
		//Log::record('测试日志信息，这是警告级别','alert');
		//$list = Log::getLog();
		//say($list);
	}
	
}