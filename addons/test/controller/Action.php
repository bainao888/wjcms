<?php
namespace addons\test\controller;
use \think\controller'

class Action extends controller
{
    public function link()
    {
    	echo 1111;exit;
        return $this->fetch();
    }
}