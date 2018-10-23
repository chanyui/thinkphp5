<?php
namespace app\index\controller;

use think\Controller;

class Ajax extends Controller
{
    //返回code码
    private static $code;
    //返回提示信息
    private static $msg;
    //返回数据信息
    private static $data = [];

    public function index()
    {
        return $this->fetch();
    }

    public function receive()
    {
        $data['name'] = input('name');
        $data['age'] = input('age');
        var_dump($data);
        return json_encode($data);
    }

    public function ajax()
    {
        $data = input('post.');
        var_dump($data);
        return $data;
    }

    /**
     * 使用 xunsearch 获取搜索建议
     */
    public function getSuggestQuery()
    {
        $keyword = I('get.keyword');
        if (!$keyword) {
            self::$code = 400;
            self::$msg = '请输入搜索词！';
        } else {
            import('vendor/hightman/xunsearch/lib/XS', ROOT_PATH);
            $xs = new \XS('shop');
            // 获取搜索对象
            $search = $xs->getSearch();
            $search->setCharset('UTF-8');
            self::$data = $search->getExpandedQuery($keyword);
            self::$code = 400;
            self::$msg = '搜索成功！';
        }
        return $this->dataReturn();
    }

    /**
     * json返回数据
     * @return \think\response\Json
     */
    private function dataReturn()
    {
        if (!isset(self::$code)) die('请设置正确的code参数！');
        return json(['code' => self::$code, 'msg' => self::$msg, 'data' => self::$data]);
    }

}