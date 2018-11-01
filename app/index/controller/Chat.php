<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/8/1
 * Time: 09:34
 */

namespace app\index\controller;

use app\index\logic\SwooleSocketLogic;
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

    /*// 建立连接时回调函数
    public function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        echo "用户{$request->fd}加入。\n";
    }

    // 收到数据时回调函数
    public function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
    {
        //接受的json数据转化成数据
        $data = json_decode($frame->data, true);

        //判断是否是首次连接
        //dump($data) . "\n";
        //添加时间戳
        $data['addtime'] = time();
        //判断是有内容发送，如果没有则是第一次连接
        if (isset($data['content'])) {

            //找到接收消息者的fd
            $receive_id = db('fd')->where('user_id', $data['receive_id'])->find();
            //判断是否存在fd,如果不存在fd则表明已下线
            if ($receive_id['fd']) {
                //保存发送的数据
                $res = db('chat')->insertGetId($data);
                //发送消息给接收者
                $server->push($receive_id['fd'], $data['content']);
            } else {


                //设置未读消息
                $data['read_is'] = 1;
                //保存发送的数据
                $res = db('chat')->insertGetId($data);
                echo "该用户已下线。 \n";
            }
        } else {
            //重新连接或者首次连接绑定fd
            //获取当前用户
            $user_id = db('fd')->find($data['user_id']);
            //判断是否存在用户
            if ($user_id) {
                //找到接收消息者的fd
                $receive_id = db('fd')->where('user_id', $data['receive_id'])->find();
                if ($receive_id['fd']) {
                    //保存发送内容
                    $res = db('chat')->insert($data);
                } else {
                    //设置未读消息
                    $data['read_is'] = 1;
                    //保存发送的数据
                    $res = db('chat')->insert($data);
                }

                //更新fd
                db('fd')->where('user_id', $data['user_id'])->update(['fd' => $frame->fd]);
            } else {


                //找到接收消息者的fd
                $receive_id = db('fd')->where('user_id', $data['receive_id'])->find();
                if ($receive_id['fd']) {
                    //保存发送内容
                    $res = db('chat')->insert($data);
                } else {
                    //设置未读消息
                    $data['read_is'] = 1;
                    //保存发送的数据
                    $res = db('chat')->insert($data);
                }
                //插入新用户数据
                $data_fd = [
                    'user_id' => $data['user_id'],
                    'fd' => $frame->fd
                ];
                db('fd')->insert($data_fd);
            }
        }


        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}" . "test \n";

    }


    // 连接关闭时回调函数
    public function onClose($server, $fd)
    {
        db('fd')->where('fd', $fd)->update(['fd' => null]);
        echo "client {$fd} closed \n";
    }*/


}