<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/11/1
 * Time: 14:43
 */

namespace app\index\logic\socket;


use app\index\model\Chats;
use app\index\model\ChatsLinkman;
use app\index\model\Member;

class SwooleSocketLogic implements SocketInterface
{
    //websocket服务
    protected $server;

    //redis对象服务
    protected $redis;

    //数据库连接
    protected $mysql;

    protected $config = [];

    //发送在线用户列表
    const SENDUSERSLISTS = 1;

    //普通信息
    const COMMONMESSAGE = 2;

    //未选择联系人
    const UNSELECTRECEIVE = 3;

    //添加用户列表
    const ADDUSER = 4;

    //用户绑定
    const BIND = 5;

    //通知用户当前尚未登录
    const UNLOGIN = 6;

    //减少用户列表
    const DELUSER = 7;

    //发送信息给个人
    const SENDTOPERSON = 8;

    //发送历史记录
    const HISTORYMSG = 9;

    //消息提醒
    const TIP = 10;

    //服务器异常 通常为插入数据失败等
    const SERVERERROR = 500;

    public function __construct($server = null, $config = [])
    {
        $this->server = $server;
        if (!empty($config)) {
            $this->config = array_merge($config);
        }
    }

    /**
     * 处理监听WebSocket连接打开事件时的任务
     * @param $req
     */
    public function onOpen($req)
    {
        echo "新客户端连接: " . $req->fd . " 时间:" . date("Y-n-j H:i:s") . "\n";
    }

    /**
     * 处理监听WebSocket消息事件时的任务
     * @param $frame
     */
    public function onMessage($frame)
    {
        //send过来的信息
        $sendData = json_decode($frame->data, true);
        $eventType = $this->judgeMsgEventType($sendData);
        switch ($eventType) {
            //用户绑定
            case self::BIND:
                // 会员模型
                $memberDb = new Member();
                $ufd = $memberDb->getFdByUid($sendData['uid']);
                if (!$ufd || $ufd !== $frame->fd) {
                    //更新绑定的fd
                    $memberDb->updateBind($sendData['uid'], $frame->fd);
                }
                $this->sendToPerson($frame->fd, '绑定成功！', self::BIND);
                break;
            case self::HISTORYMSG:
                // 联系人模型
                $linkmanDb = new ChatsLinkman();
                // 会员模型
                $memberDb = new Member();
                //更新联系人
                $linkman = $linkmanDb->findLinkman($sendData['uid'], $sendData['touid']);
                if ($linkman) {
                    if ($sendData['touid'] == $linkman['uid']) {
                        $to_uid = $sendData['touid'];
                    } else {
                        $to_uid = $linkman['to_uid'];
                    }
                    $info = $memberDb->findMemberArr($to_uid);
                    $this->sendToPerson($frame->fd, $info, self::SENDUSERSLISTS);
                } else {
                    $add = $linkmanDb->addLinkman($sendData['uid'], $sendData['touid']);
                    if ($add) {
                        $info = $memberDb->findMemberArr($sendData['touid']);
                        $this->sendToPerson($frame->fd, $info, self::ADDUSER);
                    }
                }
                $data = [];
                //点击对方用户时加载历史记录
                if (isset($sendData['touid']) && $sendData['touid']) {
                    // 聊天记录模型
                    $chatDb = new Chats();
                    // 查找历史记录并排序
                    $data = $chatDb->loadHistory($sendData['uid'], $sendData['touid']);
                }
                //替换为换行
                foreach ($data as $key => $value) {
                    $data[$key]['content'] = str_replace(["\r\n", "\n", "\r"], '<br />', $value['content']);
                }
                //发送消息历史记录 给当前用户
                $this->sendToPerson($frame->fd, $data, self::HISTORYMSG);
                break;
            //联系人错误
            case self::UNSELECTRECEIVE:
                $this->sendToPerson($frame->fd, '您不能跟自己聊天！', self::UNSELECTRECEIVE);
                break;
            //服务器错误
            case self::SERVERERROR:
                $this->sendToPerson($frame->fd, "服务器异常 请联系管理员！", self::SERVERERROR);
                break;
            //用户当前未登录
            case self::UNLOGIN:
                $this->sendToPerson($frame->fd, "当前未登录！", self::UNLOGIN);
                break;
            //发送私信给个人
            case self::SENDTOPERSON:
                if (!$sendData['touid']) {
                    $this->sendToPerson($frame->fd, '请选择联系人！', self::UNSELECTRECEIVE);
                } else {
                    $sendMsg = $sendData['content'];
                    //敏感词过滤
                    $sendMsg = $this->filterBadWord($sendMsg);
                    if ($sendMsg) {
                        // 聊天记录模型
                        $chatDb = new Chats();
                        $chatDb->addChatMsg($sendData['uid'], $sendData['touid'], $sendMsg);
                        // 会员模型
                        $memberDb = new Member();
                        //要发送给对方的fd
                        $toFd = $memberDb->getFdByUid($sendData['touid']);
                        if ($toFd) {
                            //给接收方提示信息
                            $this->sendToPerson($toFd, '来消息了！', self::TIP, $sendData['touid'], $sendData['uid']);
                        }
                        //发送聊天内容
                        $sendMsg = str_replace(["\r\n", "\n", "\r"], '<br />', $sendMsg);
                        $this->sendToPerson($frame->fd, $sendMsg, self::SENDTOPERSON, $toFd, $sendData['uid']);
                    }
                }
                unset($sendMsg);
                break;
        }
    }

    /**
     * 处理监听WebSocket连接关闭事件时的任务
     * @param $fd
     */
    public function onClose($fd)
    {
        echo "客户端({$fd})已断开连接\n";
    }


    /**
     * 判断用户发送的信息 是什么行为
     * @param $data
     * @return int
     */
    public function judgeMsgEventType($data)
    {
        if (!isset($data['uid']) || !$data['uid']) {
            return self::UNLOGIN;
        }
        if (isset($data['content'])) {
            return self::SENDTOPERSON;
        } else {
            if (isset($data['touid']) && $data['touid']) {
                if ($data['uid'] == $data['touid']) {
                    return self::UNSELECTRECEIVE;
                } else {
                    return self::HISTORYMSG;
                }
            } else {
                return self::BIND;
            }
        }
    }

    /**
     * 发消息给个人
     * @param int $fd scoket连接号
     * @param mixed $msg 发送的信息
     * @param int $type 发送消息的类型
     * @param int $sendTo 发送给谁
     * @param int $from 谁发送的
     */
    public function sendToPerson($fd, $msg, $type, $sendTo = 0, $from = 0)
    {
        /*if ($type === self::COMMONMESSAGE) {
            $msg = htmlspecialchars($msg, ENT_NOQUOTES);
            //将换行转换为br
            $msg = nl2br($msg);
            $msg = str_replace(["\n", "\""], ["", "\\\""], $msg);
        }*/
        switch ($type) {
            case self::SENDUSERSLISTS:
                //通知用户 用户列表
                $this->server->push($fd, json_encode(['code' => 1, 'msg' => $msg]));
                break;
            case self::COMMONMESSAGE:
                //普通消息
                $this->server->push($fd, json_encode(['code' => 2, 'msg' => $msg]));
                break;
            case self::UNSELECTRECEIVE:
                //未选择联系人
                $this->server->push($fd, json_encode(['code' => -1, 'msg' => $msg]));
                break;
            //列表新增用户
            case self::ADDUSER:
                $this->server->push($fd, json_encode(['code' => 4, 'msg' => $msg]));
                break;
            case self::BIND:
                //通知用户 用户绑定成功
                $this->server->push($fd, json_encode(['code' => 5, 'msg' => $msg]));
                break;
            //发私信给个人
            case self::SENDTOPERSON:
                /*$msg = htmlspecialchars_decode($msg, ENT_NOQUOTES);*/
                $time = date('Y-m-d H:i:s');
                foreach ($this->server->connections as $tempfd) {
                    if ($fd == $tempfd || $sendTo == $tempfd) {
                        $this->server->push($tempfd, json_encode(['code' => 8, 'msg' => $msg, 'from' => $from, 'time' => $time]));
                    }
                }
                break;
            case self::HISTORYMSG:
                //历史消息
                $this->server->push($fd, json_encode(['code' => 9, 'msg' => $msg]));
                break;
            //消息提醒
            case self::TIP:
                foreach ($this->server->connections as $onfd) {
                    if ($onfd == $fd) {
                        $this->server->push($fd, json_encode(['code' => 10, 'msg' => $msg, 'to' => $sendTo, 'from' => $from]));
                    }
                }
                break;
            //通知用户当前未登录
            case self::UNLOGIN:
                $this->server->push($fd, json_encode(['code' => -1, 'msg' => $msg]));
                break;
            //通知用户 服务器错误
            case self::SERVERERROR:
                $this->server->push($fd, json_encode(['code' => -1, 'msg' => $msg]));
                break;
        }
    }

    /**
     * 过滤敏感词
     * @param $str
     */
    private function filterBadWord($str)
    {
        $word_arr = include_once(ROOT_PATH . 'extend/badWord/BadWord.php');
        static $badword = [];
        if (!$badword) {
            $badword = $word_arr;
        }
        $arr = array_fill(0, count($badword), '**');
        $badword_to = array_combine($badword, $arr);
        return strtr($str, $badword_to);
    }
}