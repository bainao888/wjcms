<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;

class Login extends Controller{

	public function login(){
		$user =  session('ht_user');
		
		if(empty($user)){
			return $this->fetch();
		}else{
			$this->redirect(url('admin/index/index'));
		}
       /* $user = model('Admin');
        $data = [ 
			'admin_user'     => 'admin',
			'admin_password' => get_md5('public1')['md5'],
			'admin_code'     => get_md5('public1')['code'],
			'admin_nickname' => 'public1',
			'gid'            => '',
		];
		dump($user->dataAdd($data));*/
        //$info = $user->getUser(['id_admin'=>1]);
       // say($info);
		
	}

	public function logout(){

		Session::delete('ht_user');
		$this->error('退出成功',url('admin/login/login'));
	}
    
	/*
	 登录数据处理
	 */
	public function loginHandle(){
		$str = input('post.');
        $validate = validate('Login');		
		// 是否为 POST 请求
		if (request()->isAjax()){
			if(!$validate->check($str)){
				$this->error($validate->getError());
			}else{
				
				$module = model('Admin');
				$info = $module->getUser(['admin_user'=>$str['username']]);
				//say($info->getData('admin_status'));
				if(!empty($info)){
					$opwd = set_md5($str['password'],$info->admin_code);
					
					if($info->admin_password != $opwd ){
                       return json(['msg'=>'密码输入有误','error'=>-2]);
					}
                    if($info->getData('admin_status') != 1){
                       return json(['msg'=>$info->admin_remark,'error'=>-3]);
					}
                    $group = db('auth_access')->where('uid',$info->id_admin)->find();
					session('ht_user', ['uid'=>$info->id_admin,'gid'=>$group['group_id'],'ip'=>$info->admin_ip,'nickname'=>$info->admin_nickname,'time'=>time()]);

					return json(['msg'=>'登录成功','url'=>url('admin/index/index'),'error'=>200]);

				}else{
					return json(['msg'=>'用户不存在','error'=>-1]);
				}
				
			}
		}		
		
	}

	/*$data = [
			'admin_user'=>'admin',
			'admin_password'=> get_md5('demo')['md5'],
			'admin_code'   =>get_md5('demo')['code'],
			'admin_nickname' =>'demo',
			'admin_ip'      =>get_client_ip(),
			'admin_create_time'=>time(),
			'admin_update_time'=>time(),
		];
        $res = db('admin')->insert($data);*/
}