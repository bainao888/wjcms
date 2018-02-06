<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model 
{   
	//protected $table = 'tp_admin';
	// 设置返回数据集的对象名
	protected $resultSetType = 'collection';
    // 定义时间戳字段名
    protected $createTime = 'admin_create_time';
    protected $updateTime = 'admin_update_time';
    protected $deleteTime = 'admin_delete_time';
    
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
	//protected $autoWriteTimestamp = '';
	protected $auto = [];
    protected $insert = ['admin_create_time','admin_ip','admin_status' => 0,];  
    protected $update = ['admin_update_time']; 
    protected $type = [
        'admin_status'          =>  'integer',
        'admin_create_time'     =>  'timestamp:Y-m-d H:i',
        'admin_update_time'     =>  'timestamp:Y-m-d',
    ];

    protected function setAdminIpAttr()
    {
        return request()->ip();
    }

    public function getAdminStatusAttr($value)
    {
        $status = [0=>'<span class="am-text-danger">禁用</span>',1=>'<span class="am-text-success">正常</span>'];
        return $status[$value];
    }

    /*
     关联auth_access模型
     */
    public function authAccess()
    {
        return $this->hasOne('AuthAccess','uid','id_admin');
    }

    /*
     列表
     */
    public function getlist(){
       
       $list = Admin::where('')->order('')->field('admin_password,admin_delete_time,admin_update_time,admin_code',true)->paginate();
       foreach ($list as $key => $admin) {
           
            $gacc = $admin::get($admin->data['id_admin'],'authAccess');
            $gid = $gacc->relation['auth_access']->data['group_id'];
            $group = new AuthAccess();
            $g = $group->getById($gid);
            $list[$key]->data['grouptitle'] = $g->relation['auth_group']->data['auth_group_title'];
       }
        return $list;
    }

    /*
      添加
     */
    public function dataAdd($data){
        $info = $this->getUserField(['admin_user'=>$data['admin_user']],'admin_user');
        if(empty($info)){
            $admin = new Admin($data);
            $admin->allowField(true)->save();
            $gid = empty($data['gid'])?3:$data['gid'];
        	//$res = Admin::create($data);
            $authaccess = new AuthAccess;
            $authaccess->add(['uid'=>$admin->id_admin,'group_id'=>$gid]);
            //db('auth_access')->insert(['uid'=>$admin->id_admin,'group_id'=>$gid]);
        	return json(['result'=>'','msg'=>'添加成功','error'=>0]);
        }else{
            return json(['result'=>'','msg'=>'用户名已存在','error'=>-1]);
        }
    }
   /*
     修改
    */
    public function dataEdit($data){

        $admin = new Admin();
        $admin->allowField(true)->save($data,['id_admin'=>$data['id']]);
        db('auth_access')->where('uid',$data['id'])->update(['group_id'=>$data['gid']]);
        return json(['result'=>'','msg'=>'更新成功','error'=>0]);
    }

    public function getUser($data){
        $res = Admin::get($data);
        return $res;
    }
    
    public function getUserField($where,$field=''){
        return Admin::where($where)->column($field);
    }
}  