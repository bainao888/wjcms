<?php
namespace app\admin\model;
use think\Model;
class AuthGroup extends Model {

	protected $table = 'tp_auth_group';
	protected $pk = 'id_auth_group';
	protected $autoWriteTimestamp = false;
	protected $type = [
        'auth_group_status'          =>  'integer',
    ];

	public function getAuthGroupStatusAttr($value)
    {
        $status = [0=>'<span class="am-text-danger">禁用</span>',1=>'<span class="am-text-success">正常</span>'];
        return $status[$value];
    }


    public function getlist(){

    	$list = AuthGroup::all();
    	return $list;
    }
}