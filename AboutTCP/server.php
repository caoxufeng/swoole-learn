<?php

class Server
{

    private $serv;

    public function __construct()
    {
        $this->serv = new Swoole\Server("127.0.0.1",8088,SWOOLE_BASE,SWOOLE_SOCK_TCP);
        $this->serv->set([
            'worker_num' => 10,
            'damonize' => true,
        ]);


        $this->serv->addlistener('127.0.0.1',9501,SWOOLE_SOCK_TCP);

		$this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

		
		$this->serv->on('ManagerStart',function(swoole_server $server){
			echo "On manager start.";
		});

        $this->serv->on('WorkerStart',function($serv,$workerId){
            echo $workerId.'---';
        });

		$this->serv->on('WorkerStop',function(){
			echo '--stop';
		});


		$this->serv->start();


    }


	public function onStart($serv){
		echo "Start\n";
	} 
	
	//client 连接成功后触发
    public function onConnect($serv,$fd,$from_id){
        echo "Client have connected!";
		$a = $serv->send($fd,"Hello {$fd}!");
		var_dump($a);
    }
	
	//接收 client 的请求
    public function onReceive(swoole_server $serv,$fd,$from_id,$data){
		echo "Get message from client {$fd}:{$data}\n";
		$serv->send($fd,$data);
    }

    //客户端断开连接触发
    public function onClose($serv,$fd,$from_id){
		echo "Client {$fd} close connecttion\n";
    }



}

echo swoole_version();

var_dump(swoole_get_local_ip()['eth0']);


$server = new Server();
