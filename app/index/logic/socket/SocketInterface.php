<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/11/1
 * Time: 14:52
 */

namespace app\index\logic\socket;


interface SocketInterface
{
    /**
     * 定义连接握手接口
     * @return mixed
     */
    public function onOpen($req);

    /**
     * 定义接收消息接口
     * @return mixed
     */
    public function onMessage($frame);

    /**
     * 定义关闭连接接口
     * @return mixed
     */
    public function onClose($fd);
}