<?php

namespace app\api\controller;

use think\Controller;

class Base extends Controller
{
    static public $code;        //返回code码
    static public $msg;         //返回提示信息
    static public $data = [];   //返回数据
    static public $param;
    static public $class;
    //不需要登录就能访问的控制器方法
    static protected $notLogin = [
        'api/user/login'
    ];
    //api默认的访问命名空间
    protected static $namespace = 'app\\api\\service\\';
    //系统默认返回信息
    static private $defaultCode = [
        '100' => ['code' => 100, 'msg' => 'error！'],
        '104' => ['code' => 104, 'msg' => 'SessionId Invalid！'],
        '200' => ['code' => 200, 'msg' => 'Success！'],
        '400' => ['code' => 400, 'msg' => 'Version Is error！'],
        '401' => ['code' => 401, 'msg' => 'User Is Not Login！'],
        '405' => ['code' => 405, 'msg' => 'Class Not Allowed！'],
        '406' => ['code' => 406, 'msg' => 'Method Not Allowed！']
    ];

    /**
     * 初始化
     */
    public function _initialize()
    {
        $request = request();
        if ($request->type() == 'json') {
            $param = file_get_contents("php://input");
            self::$param = json_decode(urldecode($param), true);
        } else {
            self::$param = $request->param();
        }
        self::$param['token'] = isset(self::$param['token']) ? self::$param['token'] : '';
        $version = config('version');
        //验证系统|版本号
        $this->versionValidate($version);
        //验证是否已经登录
        $this->loginV();
        //注册类名
        $this->makeCurrentClass(self::$param['ver']);
    }

    /**
     * 验证系统版本号
     */
    private function versionValidate($version)
    {
        //验证系统类型
        if (array_key_exists(self::$param['system'], $version)) {
            //验证版本号
            if (!in_array(self::$param['ver'], $version[self::$param['system']])) {
                self::$code = 400;
                self::$msg = '系统版本号错误！';
                $this->returnData();
            }
        } else {
            self::$code = 400;
            self::$msg = '系统类型错误！';
            $this->returnData();
        }
    }

    /**
     * 验证用户是否已经登录
     */
    private function loginV()
    {
        if (!in_array($this->getCurrPathInfo(), self::$notLogin)) {
            if (!self::$param['token'] || !get_user(self::$param['token'])) {
                self::$code = 401;
                self::$msg = '您未登录,请先登录！';
                $this->returnData();
            } elseif (self::$param['token'] && !get_user(self::$param['token'])) {
                self::$code = 401;
                self::$msg = '登录超时，请重新登录！';
                $this->returnData();
            }
        }
    }

    /**
     * 生成当前访问的类
     */
    private function makeCurrentClass($ver)
    {
        //版本号
        $ver = str_replace('.', '_', $ver);
        $a = ucfirst(request()->action());
        self::$class = self::$param['system'] . '\\ver' . $ver . '\\' . $a;
    }

    /**
     * 当前访问的模块/控制器/方法
     * @return string
     */
    private function getCurrPathInfo()
    {
        $m = strtolower(request()->module());
        $c = strtolower(request()->controller());
        $a = strtolower(request()->action());
        return $m . '/' . $c . '/' . $a;
    }

    /**
     * 判断当前的文件是否存在
     * @param $class
     */
    protected function requestClassExists($class)
    {
        if (!class_exists($class)) {
            self::$code = 404;
            self::$msg = '类文件不存在！';
            $this->returnData();
        }
    }

    /**
     * 统一返回数据方法
     */
    protected function returnData()
    {
        if (!isset(self::$code) || !isset(self::$msg)) {
            self::$code = 500;
            self::$msg = '服务端出错啦！';
        }
        return !isset(self::$data) || empty(self::$data) ? json_encode(['code' => self::$code, 'msg' => self::$msg], JSON_UNESCAPED_UNICODE) : json_encode(['code' => self::$code, 'msg' => self::$msg, 'data' => self::$data], JSON_UNESCAPED_UNICODE);
    }

}