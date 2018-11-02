<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/8/1
 * Time: 09:34
 */

namespace app\index\controller;

use app\index\logic\socket\SwooleSocketLogic;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Chat extends Command
{
    // Server 实例
    protected $server;

    // 逻辑处理实例
    protected $socketLogic;

    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('websocket:start')->setDescription('Start Web Socket Server!');
    }

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @return null|int
     * @throws \LogicException
     * @see setCode()
     */
    protected function execute(Input $input, Output $output)
    {
        //配置项
        $config = config('swoole_websocket');

        $this->server = new \swoole_websocket_server($config['host'], $config['port']);

        $this->server->set($config['set']);

        $this->socketLogic = new SwooleSocketLogic($this->server);

        //监听WebSocket连接打开事件
        $this->server->on('open', function (\swoole_websocket_server $server, $req) {
            $this->socketLogic->onOpen($req);
        });

        //监听WebSocket消息事件
        $this->server->on('message', function (\swoole_websocket_server $server, $frame) {
            $this->socketLogic->onMessage($frame);
        });

        //监听WebSocket连接关闭事件
        $this->server->on('close', function (\swoole_websocket_server $server, $fd) {
            $this->socketLogic->onClose($fd);
        });

        $this->server->start();

        // $output->writeln("WebSocket: Start.\n");
    }

}