<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
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

    /**
     * 验证事务操作
     * +------------------------------------------------------------------
     * @functionName : routine
     * +------------------------------------------------------------------
     * @author yucheng
     * +------------------------------------------------------------------
     */
    public function routine()
    {
        $dbTitle = db('title');
        $dbContent = db('content');
        //开启事务
        $dbTitle->startTrans();

        $data1 = array(
            'title' => '新增1',
            'addtime' => time()
        );
        $data2 = array(
            'title' => '新增2',
            'addtime' => time()
        );
        $res1 = $dbTitle->insert($data1);
        $res2 = $dbTitle->insert($data2);

        if ($res1 && $res2) {
            //提交事务
            $dbTitle->commit();
        } else {
            //回滚事务
            $dbTitle->rollback();
        }

        /*try{
            $data = array(
                'title' => '新增',
                'addtime' => time()
            );
            $res1 = $dbTitle->insert($data);
            $res2 = $dbTitle->insert($data);

            //提交事务
            $dbTitle->commit();
        } catch (\Exception $e) {
            //回滚事务
            $dbTitle->rollback();
        }*/
    }
}