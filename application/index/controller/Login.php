<?php
namespace app\index\controller;
use think\Controller;
#use think\Loader;
use think\captcha\Captcha;
/*
  会员登录
 */
class Login extends Controller{

	public function _initialize(){
		
	}
	/*
	  登录
	 */
	public function login(){

		return $this->fetch();
	}
	/*
	 登录数据处理
	 */
	public function loginHandle(){
		$str = input('post.');
        $validate = validate('Login');
		// 是否为 GET 请求
		if (request()->isGet()){
			if(!$validate->check($str)){
			$this->error($validate->getError());
			}else{
				$pos_data = ['email'=>$str['username'],'password'=>md5($str['password'])];
				$module = model('Login');
				$info = $module->getUser($pos_data);
				say($info);
			}
		}
		// 是否为 POST 请求
		if (request()->isPost()){
			if(!$validate->check($str)){
				$this->error($validate->getError());
			}else{
				$pos_data = ['email'=>$str['username'],'password'=>md5($str['password'])];
				$module = model('Login');
				$info = $module->getUser($pos_data);
				if(!empty($info)){
					if($info->password != $pos_data['password']){
                       return $this->error('密码输入有误');
					}
					session('user', ['uid'=>$info->uid,'ip'=>$info->ip,'nickname'=>$info->nickname]);
					$this->success('登录成功',url('index/index/index'));

				}else{
					return $this->error('改用户不存在！');
				}
				
			}
		}
		//ajax 请求
		if (request()->isAjax()){
			if(!$validate->check($str)){
			    return $this->result(['url'=>url('index/login/login')],-1,$validate->getError(),'json');
			}else{
				return $this->result(['url'=>url('index/login/login')],200,'验证通过','json');
			}
		}
		
		
	}
	/*
	 注册
	 */
	public function reg(){

		return $this->fetch();
	}

	/*
	  注册数据处理
	 */
	public function regHandle(){

		$pos_data = input('post.');
		$validate = validate('Login');
		// 是否为 POST 请求
		if (request()->isPost()){
			if(!$validate->check($pos_data)){
				$this->error($validate->getError());
			}else{
				$module = model('Login');
				$data = ['nickname'=>$pos_data['nickname'],'email'=>$pos_data['username'],'password'=>md5($pos_data['password'])];
				$res = $module->add($data);
				if($res){
					$this->success('注册成功',url('index/login/login'));
				}
			}
		}
		//ajax 请求
		if (request()->isAjax()){
			if(!$validate->check($data)){
			    return $this->result(['url'=>url('index/login/reg')],-1,$validate->getError(),'json');
			}else{
				return $this->result(['url'=>url('index/login/reg')],200,'验证通过','json');
			}
		}
	}

	public function verify(){

		$captcha = new Captcha(config('captcha'));
		return $captcha->entry();
	}

	public function _empty(){

        abort(403,'网页不存在');
    }
}