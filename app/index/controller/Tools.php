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

    /**
     * 生成滑块验证码
     * ! tncode 1.2 author:weiyingbin email:277612909@qq.com
     * @ https://github.com/binwind8/tncode
     */
    public function tnCode()
    {
        error_reporting(0);
        $tn = new \TnCode();
        $tn->make();
    }

    /**
     * 滑块验证码检查
     */
    public function check()
    {
        $tn = new \TnCode();
        if ($tn->check()) {
            $_SESSION['tncode_check'] = 'ok';
            echo "ok";
        } else {
            $_SESSION['tncode_check'] = 'error';
            echo "error";
        }
    }

    /**
     * 发送邮件(PHPmailer)
     * +-----------------------------------------------------------
     * @functionName : phpmailer
     * +-----------------------------------------------------------
     * @author yc
     * +-----------------------------------------------------------
     */
    public function phpmailer()
    {
        $mailCofig = config('sendmail');
        if (request()->isPost()) {
            $defaulttitle = '愿得一人心，白首不相离。';
            $body = <<<EOF
            <p align="center">
                皑如山上雪，皎若云间月。<br>
                闻君有两意，故来相决绝。<br>
                今日斗酒会，明旦沟水头。<br>
                躞蹀御沟上，沟水东西流。<br>
                凄凄复凄凄，嫁娶不须啼。<br>
                愿得一人心，白首不相离。<br>
                竹竿何袅袅，鱼尾何簁簁！<br>
                男儿重意气，何用钱刀为！</p>
EOF;
            //上传文件
            $file = request()->file('uploadfile');
            $filePath = '';
            if ($file) {
                $config = array(
                    'size' => 3145728,
                    'ext' => ['jpg', 'gif', 'png', 'jpeg', 'xls', 'xlsx', 'pdf', 'doc', 'docx'],
                );
                $info = $file->validate($config)->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    $this->error($file->getError(), url('tools/phpmailer'));
                    exit();
                } else {
                    $filePath = './uploads' . DS . $info->getSaveName();
                }
            }
            $toemail = input('post.toemail');
            $title = input('post.title') ?: $defaulttitle;
            $content = input('post.content') ? htmlspecialchars_decode(input('post.content')) : $body;
            $res = sendPHPMail($toemail, $title, $content, $mailCofig, $filePath);
            if ($filePath) {
                unlink($filePath);
            }
            if ($res) {
                $this->success('发送成功', url('tools/phpmailer'));
            } else {
                $this->error('发送失败');
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 发送邮件(swiftMailer)
     * +-----------------------------------------------------------
     * @functionName : swiftMailer
     * +-----------------------------------------------------------
     * @author yc
     * +-----------------------------------------------------------
     */
    public function swiftMailer()
    {
        $mailCofig = config('sendmail');
        if (request()->isPost()) {
            $defaulsubject = '愿得一人心，白首不相离。';
            $body = <<<EOF
            <p align="center">
                皑如山上雪，皎若云间月。<br>
                闻君有两意，故来相决绝。<br>
                今日斗酒会，明旦沟水头。<br>
                躞蹀御沟上，沟水东西流。<br>
                凄凄复凄凄，嫁娶不须啼。<br>
                愿得一人心，白首不相离。<br>
                竹竿何袅袅，鱼尾何簁簁！<br>
                男儿重意气，何用钱刀为！</p>
EOF;
            //上传文件
            $file = request()->file('uploadfile');
            $filePath = '';
            if ($file) {
                $config = array(
                    'size' => 3145728,
                    'ext' => ['jpg', 'gif', 'png', 'jpeg', 'xls', 'xlsx', 'pdf', 'doc', 'docx'],
                );
                $info = $file->validate($config)->move(ROOT_PATH . 'public' . DS . 'uploads');
                if (!$info) {
                    // 上传失败获取错误信息
                    $this->error($file->getError(), url('tools/phpmailer'));
                    exit();
                } else {
                    $filePath = './uploads' . DS . $info->getSaveName();
                }
            }

            $toemail = input('post.toemail');
            $subject = input('post.title') ?: $defaulsubject;
            $content = input('post.content') ? htmlspecialchars_decode(input('post.content')) : $body;
            $res = sendSwiftMailer($toemail, $subject, $content, $mailCofig, $filePath);
            if ($filePath) {
                unlink($filePath);
            }
            if ($res) {
                $this->success('发送成功', url('tools/phpmailer'));
            } else {
                $this->error('发送失败');
            }
        } else {
            return $this->fetch('phpmailer');
        }
    }

}