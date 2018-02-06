<?php
namespace app\admin\controller;
use think\Controller;

class Module extends Base{

	/*
	  列表
	 */
	public function getList(){
       
        $module = model('Module');
        $list = $module->getList('',['id_module'=>'asc']);
        $this->assign('list',$list);
		return $this->fetch();
	}
	/*
	  添加
	 */
	public function add(){
	 if (request()->isAjax()){

	 	$data['module_name'] = input('post.m_title_en');
	 	$data['module_title'] = input('post.m_title_zh');
	 	$data['type'] = input('post.type');
	 	$module = model('Module');
	 	$res = $module->dataAdd($data);
	 	return $res;
	 }
	 return $this->fetch();
	}


    
    /*
	  编辑
	 */
	public function edit(){

		return $this->fetch();
	}
    /*
      获取字段信息
     */
	public function fields(){
		$module = model('Module');
        $fields = $module->getField('document');
        say($fields);
	}

    /**
     * 恢复数据
     * @return [type] [description]
     */
	public function restore(){
      if(request()->isPost()){
    	$module = model('Module');
    	$result = $module->dataRestore(input('post.id'));
    	return $result;
      }
	}

	/*
	  软删除 可恢复
	 */
	public function del(){
    if(request()->isPost()){
    	$module = model('Module');
    	$result = $module->dataDel(input('post.'));
    	return $result;
     }

	}
    

    /*
     删除 不可恢复
     */
	public function readyDel(){

	 if(request()->isPost()){
    	$module = model('Module');
    	$result = $module->dataDel(input('post.'),true);
    	return $result;
     }
	}
    /*
      状态改变
     */
	public function change(){

		if(request()->isPost()){
	    $id     = input('post.id/d');
    	$module = model('Module');
    	$result = $module->dataChangeStatus(['id_module'=>$id]);
    	return $result;
        }
	}

	/**
	 * 已删除列表
	 * @return 
	 */
	public function trash(){
        
        $module = model('Module');
        $list = $module->getTrashList('',['id_module'=>'asc']);
        //say($list);
        $this->assign('list',$list);
		return $this->fetch();
	}


}