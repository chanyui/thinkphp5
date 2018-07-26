<?php

namespace app\index\controller;

use app\common\logic\superMan\SuperManLogic;
use app\common\service\iocContainer\IocContainerService;
use think\Build;
use think\Controller;

class Tools extends Controller
{
    protected $db;

    public function _initialize()
    {
        $this->db = model('Upload');
    }

    /**
     * 使用IoC容器的方法
     */
    public function index()
    {
        // 创建一个容器（后面称作超级工厂）
        $container = new IocContainerService();

        // 向该 超级工厂添加超人的生产脚本
        SuperManLogic::bindInstance('SuperManService', $container);

        // 开始启动生产
        $res1 = $container->make('SuperManService', ['XpowerService'])->action(['能力X-Power']);
        $res2 = $container->make('SuperManService', ['UltraBombService'])->action(['终极爆炸能力','asldjflasd']);
        dump($res2);
    }

    /**
     * image 上传图片
     * +------------------------------------------------------------------
     * @functionName : image
     * +------------------------------------------------------------------
     * @author yucheng
     * +------------------------------------------------------------------
     * @return mixed
     */
    public function image()
    {
        //exit($_FILES);
        $upload = new \think\File($_FILES);
        $upload->move();

        return $this->fetch();
    }

}