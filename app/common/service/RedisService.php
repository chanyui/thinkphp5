<?php

namespace app\common\service;

use Think\Exception;

/**
 * Class RedisService
 * @package app\common\service
 */
class RedisService
{
    protected static $config = [
        'server' => [
            [
                'redis.local.com', 6379, ''
            ],
        ],
        'timeout' => 1,
    ];

    /**
     * 共享静态变量
     * @var
     */
    private static $_instance;

    /**
     * 数据库连接资源句柄
     * @var
     */
    private static $_connectSource;

    /**
     * 私有构造函数，防止类外实例化
     * RedisL constructor.
     */
    private function __construct()
    {

    }

    /**
     * 私有魔术方法，防止类外克隆
     * RedisL constructor.
     */
    private function __clone()
    {

    }

    /**
     * 访问实例的公共静态方法
     */
    public static function getInstance($name = '')
    {
        $ikey = self::serverTag($name);
        if (!(self::$_instance[$ikey] instanceof \Redis)) {
            self::$_instance[$ikey] = self::connRedis($ikey);
        }
        return self::$_instance[$ikey];
    }

    /**
     * 连接redis
     * @param int $ikey
     * @return mixed
     */
    private static function connRedis($ikey = 0)
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if (!(self::$_connectSource[$ikey] instanceof \Redis)) {
            try {
                self::$_connectSource[$ikey] = new \Redis;
                self::$_connectSource[$ikey]->connect(self::$config['server'][$ikey][0], self::$config['server'][$ikey][1], self::$config['timeout']);
                self::$_connectSource[$ikey]->auth(self::$config['server'][$ikey][2]);
            } catch (Exception $e) {
                $content = $e->getMessage() . date('Y-m-d H:i:s') . PHP_EOL;
                file_put_contents('./log/redis_log.txt', $content, FILE_APPEND);
                die();
            }
        }
        return self::$_connectSource[$ikey];
    }

    private static function serverTag($name)
    {
        $name = $name ?: request()->ip();
        $mkey = md5($name);
        $n = 0;
        for ($i = 8; $i <= 16; $i++) {
            $n += ord(substr($mkey, $i, 1));
        }
        $ikey = (int)($n % 2);
        return $ikey;
    }
}