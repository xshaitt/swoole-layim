<?php

namespace App\Console\Commands;

use App\Http\Swoole\Websocket\WebSocketServer;
use Illuminate\Console\Command;

class SwooleStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动Swoole';

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
        $websocketServer = new WebSocketServer();
        $websocketServer->start();
    }
}
