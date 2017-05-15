<?php

namespace app\index\controller;

use think\Controller;
use OSS\OssClient;
use OSS\Core\OssException;

define('access_id','lkajlsdfsdf');
define('access_key','asdfasdfasdfdasfasdf');
define('endpoint','oss-cn-hangzhou.aliyuncs.com');
define('bucket','zyqc');

class Home extends Controller
{
    /**
     * 首页
     * +------------------------------------------------------------------
     * @functionName : index
     * +------------------------------------------------------------------
     */
    public function index()
    {
        return $this->display('欢迎登录');
    }

    /**
     * 阿里云对象存储测试
     * +------------------------------------------------------------------
     * @functionName : test_oss
     * +------------------------------------------------------------------
     * @author yucheng
     * +------------------------------------------------------------------
     */
    public function test_oss()
    {
        vendor('aliyun-oss.autoload'); //不加载autoload的情况下，实例化OssUtil.php失败，所以要用__autoload加载
        try {
            $ossClient = new OssClient(access_id, access_key, endpoint);
        } catch (OssException $e) {
            print $e->getMessage();
        }

    }

}