<?php
namespace app\admin\model;
use think\Model;
class AuthAccess extends Model {

	protected $table = 'tp_auth_access';
	protected $pk = 'uid';
	protected $autoWriteTimestamp = false;
	protected $type = [];
   
     /*
     关联auth_access模型
     */
    public function authGroup()
    {
        return $this->hasOne('AuthGroup','id_auth_group','uid')->field('id_auth_group,auth_group_title');
    }


    public function getById($id){
        return AuthAccess::get($id,'authGroup');
    }

    public function add($data){

        return AuthAccess::create($data);
    }
   
}