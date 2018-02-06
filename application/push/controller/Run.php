<?php
namespace app\push\controller;
use Workerman\Worker;
use GatewayWorker\Register;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
class Run 
{
   //控制器无需继承Controller
  /**
     * 构造函数
     * @access public
     */
    public function __construct()
    {
        //由于是手动添加，因此需要注册命名空间，方便自动加载，具体代码路径以实际情况为准
        \think\Loader::addNamespace([
            'Workerman' => EXTEND_PATH . 'Workerman/workerman',
            'GatewayWorker' =>EXTEND_PATH . 'Workerman/gateway-worker/src',
        ]);
        
        //初始化各个GatewayWorker
    //初始化register
    new Register('text://0.0.0.0:1238');
     
     //初始化 bussinessWorker 进程
    $worker = new BusinessWorker();
    $worker->name = 'Worker';
    $worker->count = 1;
    $worker->registerAddress = '127.0.0.1:1238';
        //设置处理业务的类,此处制定Events的命名空间
    $worker->eventHandler = '\app\push\controller\Events';
    // 初始化 gateway 进程
    $context = array(
	    'ssl' => array(
	        'local_cert' => '/www/wdlinux/nginx/conf/cert/wechat.faryao.cn.pem', // 也可以是crt文件
            'local_pk'   => '/www/wdlinux/nginx/conf/cert/wechat.faryao.cn.key',
	        'verify_peer' => false
	    )
	);
	// websocket协议(端口任意，只要没有被其它程序占用就行)
	// 开启SSL，websocket+SSL 即wss
	
    $gateway = new Gateway("websocket://wechat.faryao.cn:2346", $context);
    $gateway->transport = 'ssl';
    $gateway->name = 'faryao';
    $gateway->count = 1;
    $gateway->lanIp = '127.0.0.1';
    $gateway->startPort = 2900;
    $gateway->registerAddress = '127.0.0.1:1238';
  
    //心跳检测
    $gateway->pingInterval = 30;
    $gateway->pingNotResponseLimit = 1;

    $gateway->pingData = '{"message_type":"ping","content":"","username":"sys"}';//'{"type":"ping"}'

     //运行所有Worker;
    Worker::runAll();
    }

}