<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
use think\Db;
class Document extends Model{

	// 设置返回数据集的对象名
	protected $resultSetType = 'collection';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    use SoftDelete;
    //删除字段名
    protected $deleteTime = 'delete_time';
    
    //自动写入时间戳
    protected $autoWriteTimestamp = true;
	protected $auto = [];
    protected $insert = ['create_time','status' => 1];  
    protected $update = ['update_time']; 
    protected $type = [
        'status'          =>  'integer',
        'create_time'     =>  'timestamp:Y/m/d',
        'update_time'     =>  'timestamp:Y-m-d',
    ];
    protected $prefix;
    
   
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
        $this->prefix = config('database.prefix');
    }

    public function getStatusAttr($value){

        $status = [0=>'<span class="am-text-danger">禁用</span>',1=>'<span class="am-text-success">正常</span>',2=>'待审核',3=>'草稿'];
        return $status[$value];
    }

    /*
     获取列表
     */
    public function getList($where,$order){
    	
        $list = Document::where($where)->order($order)->paginate();
		//$list = Document::where($where)->order($order)->paginate();
		// 获取分页显示
		return $list;
    	
    }

     /*
      数据添加
      return id_module
     */
    public function dataAdd($data){
       $Document = new Document($data);
        // 过滤post数组中的非数据表字段数据
       $Document->allowField(true)->save();
       $id = $Document->id;
       $this->insertContent($id,$data);
       return  ['error'=>0,'msg'=>'数据添加成功'];

     
    }
      /*
      数据添加
      return id_module
     */
    public function dataEdit($data,$id){
       $Document = new Document();
       if(!empty($data['thumb_id'])){
            $img = db('images')->where('id',$data['old_thumb_id'])->find();
            
            if(!empty($img)){
                $rs = delimg($img['url']);

                db('images')->delete($data['old_thumb_id']); 
            }
           
        }else{
            $data['thumb_id'] = $data['old_thumb_id'];
        }
        //say($data);
        // 过滤post数组中的非数据表字段数据
       $Document->allowField(true)->save($data,['id'=>$id]);
       $this->insertContent($id,$data,true);
       return  ['error'=>0,'msg'=>'数据保存成功'];
    }
    /**
     * 根据不同类型的模型进行插入
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    protected function insertContent($id,$data,$flag=false){
        $table = 'document_'.$data['module'];
        
        switch ($data['module']) {
            case 'article':
               $datas = ['id'=>$id,'content'=>$data['content'],'template'=>$data['template']]; 
                break;
            case 'download':
                $datas = ['id'=>$id,'content'=>$data['content'],'template'=>$data['template'],'file_id'=>$data['file_id'],'size'=>$data['size'],'download'=>$data['download']]; 
                break;
            case 'picture':
                $datas = ['id'=>$id,'content'=>$data['content'],'template'=>$data['template']]; 
            break;
            case 'news':
                $datas = ['id'=>$id,'content'=>$data['content'],'template'=>$data['template']]; 
            break;
            
            default:
                # code...
                break;
        }
        //say($datas);
        if($flag == true){
            db($table)->update($datas);
        }else{
            db($table)->insert($datas);
        }
        
    }
}