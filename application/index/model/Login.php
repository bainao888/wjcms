<?php
namespace app\index\model;
use think\Model;
class Login extends Model 
{   
	protected $table = 'tp_user';
	// 设置返回数据集的对象名
	protected $resultSetType = 'collection';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
	//protected $autoWriteTimestamp = '';
	protected $auto = [];
    protected $insert = ['create_time','status' => 0,'ip'];  
    protected $update = ['update_time']; 
    protected $type = [
        'status'          =>  'integer',
        'create_time'     =>  'timestamp:Y-m-d',
        'update_time'     =>  'timestamp:Y-m-d',
    ];

    protected function setIpAttr()
    {
        return request()->ip();
    }


    public function add($data){

    	$res = Login::create($data);
    	return $res->uid;
    }

    public function getUser($data){
        $res = Login::get($data);
        return $res;
    }
}  