<?php

class Client
{



    private $client;

    public function __construct()
    {
        $this->client = new Swoole\Client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
        $this->client->connect('127.0.0.1',9501,1);
    }

    public function connect()
    {
        

        $i = 0;
        while($i < 100)
        {

            $this->client->send($i."\n");
            $message = $this->client->recv();
            echo $message;
            $i++;


        }



        $this->client->close();

    }


}



$client = new Client();
$client->connect();






