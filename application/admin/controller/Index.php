<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base{

	public function index(){
	    
	   // dump(session('ht_user'));
       ///hook('testhook', ['id'=>1]);
		return $this->fetch();
	}

	public function home(){

		return $this->fetch();
	}
    
	public function timeOut(){

     if(request()->isAjax()){

     	$res = $this->getStatus();
     	return $res;
     }

	}


	

}