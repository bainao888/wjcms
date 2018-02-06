<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Db;
/**
 * 公共控制器
 */
class Base extends Controller{
    
    /**
     * 初始化控制器
     *
     */
	public function _initialize(){

        // $this->getStatus();
         $this->checkLogin();
         $sideBar = get_menu(0,2,['id','title','icon','url','listorder','pid','param']);
         //say($sideBar);
         $this->assign('sideBar',$sideBar);
	}


    /*
      检查是否登录
     */
	public function checkLogin(){
		$user = session('ht_user');
		if(empty($user)){
		    $this->error('请先登录',url('admin/login/login'));
		}
	}
    
    /**
     *  获取登录id
     * @return [type] [description]
     */
	public function getUserId(){

		return session('ht_user.uid');
	}
    /*
      检测用户有没有超时
     */
	public function getStatus(){
		$requesttime = input('server.REQUEST_TIME');//刷新时间

        $now = time();
		$time        = session('ht_user.time');
		//session('ht_user.time',$requesttime);
		$min         = $requesttime - $time;
		
        echo '刷新时间：'.date('Y-m-d H:i:s',$requesttime).'<br>';
        echo '现在时间：'.date('Y-m-d H:i:s',$time).'<br>';
        echo '过了'.$min.'秒';

        exit;
		//十分钟不操作则退出登录
        if($min>=60*2){
        	Session::delete('ht_user');
        	return json(['error'=>-1,'msg'=>'操作已超时，请重新登录']);
        }else{
        	//session('ht_user.time',time());
        	return json(['error'=>0,'msg'=>'正常','result'=>$min]);
        }
	}
	
}