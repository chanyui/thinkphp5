<?php

namespace app\index\controller;


use app\index\logic\container\IocContainerLogic;
use think\Controller;
use app\index\service\container\IocContainerService;

class Tools extends Controller
{
    protected $db;

    public function _initialize()
    {
        $this->db = model('Upload');
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


    /**
     * 使用IoC容器的方法
     */
    public function iocContainer()
    {
        // 创建一个容器（后面称作超级工厂）
        $container = new IocContainerService();

        // 向该 超级工厂添加超人的生产脚本
        IocContainerLogic::bindInstance('SuperManService', $container);
        // 开始启动生产
        $result = $container->make('SuperManService', ['XpowerService'])->test();

        dump($result);
    }

}