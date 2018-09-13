<?php
/**
 * Created by PhpStorm.
 * User: yc
 * Date: 2018/9/11
 * Time: 14:40
 */

namespace app\common\service\location;


use ip2region\binding\php\Ip2Region;

class Ip2RegionService
{
    private static $defaultCity = '杭州市';

    /**
     * 获取定位服务
     * 客户端/binary算法/b-tree算法/Memory算法：
     * java/0.x毫秒/0.x毫秒/0.1x毫秒 (使用RandomAccessFile)
     * php/0.x毫秒/0.1x毫秒/0.1x毫秒
     * c/0.0x毫秒/0.0x毫秒/0.00x毫秒(b-tree算法基本稳定在0.02x毫秒级别)
     * python/0.x毫秒/0.1x毫秒/未知
     * @param $ip mixed 请求ip
     * @param string https://github.com/juelite/ip2region
     * @return array
     */
    public static function getPositionByIp2Region($ip)
    {
        $ip2Region = new Ip2Region(APP_PATH . '/../extend/ip2region/data/ip2region.db');
        $result = $ip2Region->btreeSearch($ip);
        $result = explode('|', $result['region']);
        if (!$result || ($result[0] == 0 && $result[1] == 0 && $result[2] == 0)) {
            return [200, self::$defaultCity];
        } elseif ($result[3]) {
            return [200, $result[3]];
        } else {
            return [200, $result[4]];
        }
    }
}