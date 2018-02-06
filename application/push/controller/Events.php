<?php
namespace app\push\controller;
use Workerman\Lib\Timer;
use GatewayWorker\Lib\Gateway;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events{
    /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $data 具体消息
    */
    public static function onMessage($client_id, $data){
       $message = json_decode($data, true);
       //echo "<pre>";
       //print_r($message);
       $message_type = $message['type'];
        
       switch($message_type) {
           case 'login':
               // uid
               $uid = $message['uid'];
               // 设置session
               $_SESSION = [
                   'username' => $message['nickname'],
                   'id'       => $uid,
                   'type'     => 'online'
               ];
               // 将当前链接与uid绑定
               Gateway::bindUid($client_id, $uid);
               // 通知当前客户端初始化
               $userlist = Gateway::getAllClientSessions();
               $ulist = [];
               foreach ($userlist as $key => $val) {
               	 $ulist[] = $val;
               }
               $init_message = array(
                   'message_type' => 'login',
                   'id'           => $uid,
                   'username'     => $_SESSION['username'],
                   'content'      => $_SESSION['id'].'用户上线',
                   'userlist'     => $ulist
               );
               //var_dump($_SESSION);
               //Gateway::sendToClient($client_id, json_encode($init_message));
               return Gateway::sendToAll(json_encode($init_message));
               return;
               break;
           case 'chat':
               $userlist = Gateway::getAllClientSessions();
               $ulist = [];
               foreach ($userlist as $key => $val) {
               	 $ulist[] = $val;
               }
               // 聊天消息
               $chat_message = [
               	   'message_type' => $message_type,
                   'id'           => $_SESSION['id'],
                   'username'     => $_SESSION['username'],
                   'content'      => $message['data']['content'],
                   'formid'       => $message['tid'],
                   'userlist'     => $ulist
               ];
               //echo "<pre>";
              // print_r($chat_message);
               if($message['tid'] == 'all'){
               	return Gateway::sendToAll(json_encode($chat_message));
               }else{
               	  // 如果不在线就先存起来
	            if(!Gateway::isOnline($chat_message['formid'])){
                   // saveMsg($chat_message['formid'],$chat_message);
                    //$msgData = array();
                    $tid = $chat_message['formid'];
			    	$msgData[$tid][] = $chat_message;
			    	$_SESSION['msgdata'] = $msgData;
			    	Gateway::sendToUid($uid, json_encode($chat_message));
	            }else{
	            	$msgdata = $_SESSION['msgdata'][$chat_message['formid']];
	            	if(!empty($msgdata)){
	            		foreach ($msgdata as $key => $data) {
	            			Gateway::sendToUid($chat_message['formid'], json_encode($data));
	            		}
	            		unset($_SESSION['msgdata'][$chat_message['formid']]);
	            	}else{
	            		return Gateway::sendToUid($chat_message['formid'], json_encode($chat_message));
	            	}
	            	
	            }
               	
               }
               //return Gateway::sendToUid($to_id, json_encode($chat_message));
               //return Gateway::sendToAll(json_encode($chat_message));
               break;
           case 'hide':
           case 'online':
               $status_message = [
                   'message_type' => $message_type,
                   'id'           => $_SESSION['id'],
                   'username'     => $_SESSION['username'],
                   'content'      => $_SESSION['id'].'用户上线',
               ];
               $_SESSION['online'] = $message_type;
               Gateway::sendToAll(json_encode($status_message));
               return;
               break;
           case 'ping':
               $status_message = [
                   'message_type' => 'ping',
                   'content'      => '心跳检测',
               ];
               return Gateway::sendToUid($message['uid'], json_encode($status_message));
           default:
               echo "unknown message $data" . PHP_EOL;
       }
       
    }
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
    	//var_dump($_SESSION);
    }
    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public static function onClose($client_id){
       $logout_message = [
           'message_type' => 'logout',
           'id'           => $_SESSION['id'],
           'username'     => $_SESSION['username'],
       ];
       Gateway::sendToAll(json_encode($logout_message));
    }
    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public static function onError($client_id, $code, $msg)
    {
        echo "error $code $msg\n";
    }
    /**
     * 每个进程启动
     * @param $worker
     */
    public static function onWorkerStart($worker)
    {
    	Timer::add(10, function(){
            echo  date('Y-m-d H:i:s',time())."\n";
        });
    }


    public function saveMsg($uid,$data){

    	
    }

}