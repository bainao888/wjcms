<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
/*
  用户中心
 */
class User extends Controller
{
	public function home(){

		return $this->fetch();
	}

}