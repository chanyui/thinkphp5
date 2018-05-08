<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/4/30
 * Time: 16:29
 */
namespace app\index\service;

class TimeService
{
    //swoole_server只能运行在cli模式下，运行在命令行模式下

    /*protected $server;

    // 设置命令返回信息
    public function __construct()
    {
        $this->server = new \swoole_server('0.0.0.0', 9502);
        $this->server->set([
            'worker_num' => 4,
            'daemonize' => false,
        ]);
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('Connect', [$this, 'onConnect']);
        $this->server->on('Receive', [$this, 'onReceive']);
        $this->server->on('Close', [$this, 'onClose']);
        $this->server->start();
        // $output->writeln("TCP: Start.\n");
    }

    // 主进程启动时回调函数
    public function onStart(\swoole_server $server)
    {
        echo "Start" . PHP_EOL;
    }

    // Worker 进程启动时回调函数
    public function onWorkerStart(\swoole_server $server, $worker_id)
    {
        echo "workerStart\n";
        // 仅在第一个 Worker 进程启动时启动 Timer 定时器
        if ($worker_id == 0) {
            // 启动 Timer，每 1000 毫秒回调一次 onTick 函数，
            swoole_timer_tick(1000, [$this, 'onTick']);
        }
    }

    // 定时任务函数
    public function onTick($timer_id, $params = null)
    {
        file_put_contents('test.txt',"测试\n",FILE_APPEND);
    }

    // 建立连接时回调函数
    public function onConnect(\swoole_server $server, $fd, $from_id)
    {
        echo "Connect" . PHP_EOL;
    }

    // 收到信息时回调函数
    public function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        echo "message: {$data} form Client: {$fd}" . PHP_EOL;
        // 将受到的客户端消息再返回给客户端
        $server->send($fd, "Message form Server: " . $data);
    }

    // 关闭连时回调函数
    public function onClose(\swoole_server $server, $fd, $from_id)
    {
        echo "Close" . PHP_EOL;
    }*/

    /**
     * $ms 最大不得超过 86400000
     * tick定时器在1.7.14以上版本可用
     * 定时器在执行的过程中可能会产生微小的偏差，请勿基于定时器实现精确时间计算
     */
    public function __construct($ms)
    {
        // $ms 秒后触发一次
        \swoole_timer_after($ms, function () {
            file_put_contents('test.txt',"测试\n",FILE_APPEND);
        });
    }
}

new TimeService(1000);