<?php

namespace App\Console\Commands;

use App\Http\Swoole\Websocket\WebSocketClient;
use Illuminate\Console\Command;

class SwooleSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '使用swoole发送websocket消息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = '0.0.0.0';
        $prot = 9501;
        $client = new WebSocketClient($host, $prot);
        $data = $client->connect();
        echo $data;
        $client->send("hello swoole");
        $recvData = $client->recv();
        var_dump($recvData);
    }
}
