<?php
namespace app\admin\controller;
use think\Controller;
/**
* 
*/
class Role extends Base
{   
	/*
	  后台用户列表
	 */
	public function lists(){
         //$list = db('admin')->field('id_admin,admin_user,admin_nickname,admin_ip,admin_status,admin_create_time')->select();
        $admin = model('Admin');
        $list = $admin->getlist();
        $this->assign('list',$list);
		return $this->fetch();
	}

    /*
     添加用户
     */
	public function userAdd(){
        if(request()->isAjax()){
        	$param = input('post.');
        	$validate = validate('Admin');	
        	if(!$validate->check($param)){
               return json(['error'=>-1,'msg'=>$validate->getError()]);
            }
            $data = [ 
			'admin_user'     => $param['username'],
			'admin_password' => get_md5($param['password'])['md5'],
			'admin_code'     => get_md5($param['password'])['code'],
			'admin_nickname' => $param['nickname'],
			'gid'            => $param['usergroup'],
		    ];
        	$res = model('Admin')->dataAdd($data);
        	return $res;
        	
        }
        $glist = db('auth_group')->where('id_auth_group','neq',1)->select();
        $this->assign('list',$glist);
		return $this->fetch();
	}

	public function useredit(){
		if(request()->isAjax()){
        	$param = input('post.');
        	$validate = validate('Admin');	
        	if(!$validate->scene('edit')->check($param)){
               return json(['error'=>-1,'msg'=>$validate->getError()]);
            }
            $data1 = [];
            if(!empty($param['password'])){
            	if(!$validate->scene('pwd')->check($param)){
               	return json(['error'=>-1,'msg'=>$validate->getError()]);
            	}
            	 $data1 = [ 
					'admin_password' => get_md5($param['password'])['md5'],
					'admin_code'     => get_md5($param['password'])['code'],
				    ];
            }
            $data = [ 
			'admin_user'     => $param['username'],
			'admin_nickname' => $param['nickname'],
			'gid'            => $param['usergroup'],
			'admin_remaker'  => $param['remaker'],
			'admin_status'   => empty($param['status'])?0:1,
 			'id'             => $param['id']
		    ];
		    $data = array_merge($data1,$data);
        	$res = model('Admin')->dataEdit($data);
        	return $res;
        	
        }
        $id = input('param.id/d');
        $info = db('admin')->find($id);
        $gid  = db('auth_access')->where('uid',$id)->find();
        $glist = db('auth_group')->select();
        $this->assign('list',$glist);
        $this->assign('info',$info);
        $this->assign('gid',$gid);
		return $this->fetch();
	}

	/*
	  权限组
	 */
	public function group(){
        $list = model('AuthGroup')->getlist();
        
        $this->assign('list',$list);
		return $this->fetch();
	}

    /*
    添加组
     */
	public function groupAdd(){

       if(request()->isAjax()){
        	$param = input('post.');
        	$validate = validate('Group');	
        	if(!$validate->check($param)){
               return json(['error'=>-1,'msg'=>$validate->getError()]);
            }
            $data = [ 
			'auth_group_title' => $param['title'],
			'auth_group_desc'  => $param['desc'],
		    ];
        	$res = model('AuthGroup')->dataAdd($data);
        	return $res;
        	
        }

       return $this->fetch();
	}
}