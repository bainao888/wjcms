<?php
namespace app\push\controller;
#use think\Controller;
#use think\Session;
#use think\Db;
use think\worker\Server;
use Workerman\Lib\Timer;
class Worker extends Server{
    
    protected $worker;
    protected $processes = 1;//进程数
    protected $socket = 'websocket://wechat.faryao.cn:2346';
    protected $transport = 'ssl';
    protected $sslcontent = array(
            'ssl' => array(
                'local_cert' => '/www/wdlinux/nginx/conf/cert/wechat.faryao.cn.pem', // 也可以是crt文件
                'local_pk'   => '/www/wdlinux/nginx/conf/cert/wechat.faryao.cn.key',
            )
        );
    public $cid;
    
    

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {      
           
            $msginfo = json_decode($data,true);
           
           // $connection->uid = $msginfo['uid'];
           // $connection->nickname = $msginfo['nickname'];
            //发送id
            $to_id = $connection->id;
            
           if($msginfo['tid'] == 'all'){
              foreach ($this->worker->connections as $connect) {
                    if($to_id == $connect->id){
                        $datas =  ['fid'=>$to_id,'type'=>'message','msg'=>'我说：'.$msginfo['msg']];
                        $connect->send(json_encode($datas));
                    }else{
                    $datas =  ['fid'=>$to_id,'type'=>'message','msg'=>'我对大家说：'.$msginfo['msg']];
                    $connect->send(json_encode($datas));
                    }
              }
           }
           /* foreach ($this->worker->connections as $connect) {

                $id = $connect->id;
                if($to_id == $id){
                    $datas =  ['fid'=>$id,'type'=>'message','msg'=>'我说：'.$msginfo['msg']];
                    $connect->send(json_encode($datas));
                }else{
                    $datas =  ['fid'=>$id,'type'=>'message','msg'=>$msginfo['nickname'].'对所有人说：'.$msginfo['msg']];
                     $connect->send(json_encode($datas));
                }
                  
            }*/

            ///////
            // 判断当前客户端是否已经验证,即是否设置了uid
           /* if(!isset($connection->id))
            {
               // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
               $connection->id = $msginfo['uid'];
                保存uid到connection的映射，这样可以方便的通过uid查找connection，
                * 实现针对特定uid推送数据
                
               $this->uidConnections[$to_id] = $connection;
               $datas =  ['fid'=>'','type'=>'message','msg'=>'login success, your uid is ' . $connection->id];
               return $connection->send(json_encode($datas));
            }*/
            // 全局广播

        
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {
      // $this->cid = $connection->id;
      // var_dump($this->listUser());
       //$data =  ['fid'=>$connection->id,'type'=>'login','msg'=>'login success'];
      // $connection->send(json_encode($data));
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        
        if(isset($connection->uid))
        {
            // 连接断开时删除映射
            unset($this->worker->uidConnections[$connection->uid]);
        }
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {
       // 定时，每10秒一次
        Timer::add(30, function()use($worker)
        {
            // 遍历当前进程所有的客户端连接，发送当前服务器的时间
            foreach($this->worker->connections as $connection)
            {
                $data =  ['type'=>'message','msg'=>'广播：请不要乱说'];
                $connection->send(json_encode($data));
            }
        });
    }


    public function listUser(){
        $list = Db::table('tp_user')->field('uid,nickname')->select();
        return $list;
    }
}
