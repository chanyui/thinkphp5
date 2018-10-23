<?php

namespace app\index\controller;

use think\Controller;
use think\Db;
use OSS\OssClient;
use OSS\Core\OssException;
use think\paginator\driver\Bootstrap;

define('access_id', 'lkajlsdfsdf');
define('access_key', 'asdfasdfasdfdasfasdf');
define('endpoint', 'oss-cn-hangzhou.aliyuncs.com');
define('bucket', 'zyqc');

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

    /**
     * 使用 xunsearch 获取搜索结果
     * @return mixed
     */
    public function search()
    {
        //引入xunsearch的基类
        import('vendor/hightman/xunsearch/lib/XS', ROOT_PATH);

        $param = input('get.');
        if (!$param['keyword'] || $param['keyword'] == '') {
            $this->error('请输入关键词！');
            exit;
        }

        //使用xunsearch全文检索技术
        try {
            $xs = new \XS('shop');
            // 获取搜索对象
            $search = $xs->getSearch();
            $search->setCharset('UTF-8');

            $limit = 20;
            $p = max(1, intval($param['p']));
            $offset = $limit * ($p - 1);

            if (empty($param['keyword'])) {
                // just show hot query
                $hot = $search->getHotQuery(10);
            } else {
                // fuzzy search 模糊搜索
                $search->setFuzzy();

                // synonym search 自动同义词搜索功能 false关闭|true开启
                $search->setAutoSynonyms(true);

                // set query
                if (!empty($param['field']) && $param['field'] != '_all') {
                    // 搜索包含 "杭州" 的结果，并且提升 subject 字段包含 "西湖" 的数据的排序
                    //$search->addWeight('salenum');

                    // 搜索特定字段里面包含关键词 字段检索
                    $search->setQuery($param['field'] . ':' . $param['keyword']);
                } else {
                    $search->setQuery($param['keyword']);
                }

                // set sort 字段排序
                if (($pos = strrpos($param['sort'], '|')) !== false) {
                    $sf = substr($param['sort'], 0, $pos);
                    $st = substr($param['sort'], $pos + 1);
                    $st = strtolower($st) == 'asc' ? true : false;
                    $search->setSort($sf, $st);
                }

                // set offset, limit
                $search->setLimit($limit, $offset);

                // get the result
                $search_begin = microtime(true);
                $docs = $search->search();
                //搜索所用的时间
                $search_cost = microtime(true) - $search_begin;


                echo '搜索耗时：';
                printf('%.4f', $search_cost);
                echo '秒';
                echo "<br/>";

                // get other result
                $count = $search->getLastCount();
                // get all data count
                $total = $search->getDbTotal();

                echo '搜索结果总数(估算值)：' . $count;

                var_dump($docs);

                $data = [];
                foreach ($docs as $key => $value) {
                    $data[$key] = $value->getFields();
                }
                //var_dump($data);

                // 框架分页
                $page = Bootstrap::make($data, $limit, $p, $count, false, [
                    'path' => url(),
                    'query' => [
                        'keyword' => $param['keyword'], 'field' => $param['field'], 'sort' => $param['sort']
                    ],
                ]);

                // try to corrected, if resul too few 搜索纠错
                if ($count < 1 || $count < ceil(0.001 * $total)) {
                    $corrected = $search->getCorrectedQuery();
                }
                // get related query 获取相关的搜索词
                $related = $search->getRelatedQuery();
            }

            /*$this->assign('page',$show);
            $this->display('index');*/
        } catch (\XSException $e) {
            echo $e;                     // 直接输出异常描述
            if (defined('DEBUG'))  // 如果是 DEBUG 模式，则输出堆栈情况
                echo "\n" . $e->getTraceAsString() . "\n";
        }


        //return $this->fetch();
    }
}