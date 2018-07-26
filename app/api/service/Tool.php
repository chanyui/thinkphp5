<?php
/**
 * Created by PhpStorm.
 * User: Lance Li
 * Date: 2018/3/13
 * Time: 下午5:31
 */

namespace app\api\service;

class Tool implements ToolInterface
{
    /**
     * 规则检查
     * @param $type
     * @param $val
     * @return bool|int
     */
    public static function rule($type, $val){
        switch($type){
            case 'password':
                //验证登陆密码，允许英文和数字_,5-20位，不允许以_开头
                return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_]{5,20}$/',$val);break;
            case 'deal_password':
                //验证交易密码
                return preg_match('/^([0-9]){6}$/',$val);break;
            case 'mobile':
                //验证手机号码
                return preg_match('/^1[34578]{1}\d{9}$/', $val);break;
            case 'telephone':
                //验证固定电话，区号-座机号-分机号，分机号可不填
                return preg_match('/^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/',$val);break;
            case 'username':
                //验证用户名，只含有数字、字母、下划线不能以下划线开头和结尾,6-16位
                return preg_match('/^(?!_)(?!.*?_$)[a-zA-Z0-9_]{6,16}$/', $val);break;
            case 'nickname':
                //验证昵称，只含有中文、字母、数字,1-16位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,16}$/u',$val);break;
            case 'msg_code':
                //验证短信验证码
                return preg_match('/^([0-9]){6}$/',$val);break;
            case 'md5':
                //验证md5格式
                return preg_match('/^([a-zA-Z0-9]){32}$/',$val);break;
            case 'id_card':
                //验证银行卡
                return preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/',$val);//身份证，15和18位都可以
                break;
            case 'address_code':
                //验证地址
                return preg_match('/^([0-9]){6}$/',$val);break;
            case 'name':
                //验证真实姓名
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z]{2,16}$/u',$val);break;//只含有中文、字母,2-16位
            case 'path':
                //验证文件路径
                return preg_match('/[a-zA-Z]:(\\[0-9a-zA-Z_.]+)+|\/([0-9a-zA-Z_.]+)/',$val);break;//验证文件的路径
            case 'shop_name':
                //验证店铺名称，只含有中文、字母、数字,1-20位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,20}$/u',$val);break;
            case 'lng':
                //地图经度验证，-180.0～+180.0（整数部分为0～180，必须输入1到6位小数）
                return preg_match('/^[\-\+]?(0?\d{1,2}\.\d{1,5}|1[0-7]?\d{1}\.\d{1,6}|180\.0{1,6})$/',$val);break;
            case 'lat':
                //地图纬度验证，-90.0～+90.0（整数部分为0～90，必须输入1到6位小数）
                return preg_match('/^[\-\+]?([0-8]?\d{1}\.\d{1,6}|90\.0{1,6})$/',$val);break;
            case 'money':
                //验证金额，9位整数位，2位小数，可以为0，不能为负
                return preg_match('/(^[1-9]([0-9]{0,8})?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/',$val);break;
            case 'close_time':
                //验证订单自动关闭时间验证，3位整数
                return preg_match('/^([0-9]){1,3}$/',$val);break;
            case 'time_ymd':
                //验证时间格式YYYY-MM-DD，YYYY/MM/DD，YYYY_MM_DD，YYYY.MM.DD,包含闰年
                return preg_match('/(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})([-\/\._])(((0[13578]|1[02])([-\/\._])(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)([-\/\._])(0[1-9]|[12][0-9]|30))|(02([-\/\._])(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))([-\/\._])02([-\/\._])29)/',$val);
                break;
            case 'qq':
                //验证qq
                return preg_match('/[1-9][0-9]{4,10}/',$val);break;
            case 'weixin':
                //验证微信
                return preg_match('/^[a-zA-Z0-9_]{5,}$/',$val);break;//允许英文和数字_,5位打底
            case 'sort':
                //验证排序，统一排序验证，0-999的数字
                return preg_match('/^[1-9][0-9]{0,2}|[0]$/',$val);break;
            case 'ind_name':
                //验证行业分类名称，匹配中文，英文，数字加/，2-16位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\/]{2,16}$/u',$val);break;
            case 'good_name':
                //验证商品名称，只含有中文、字母，数字,1-60位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,60}$/u',$val);break;
            case 'email':
                //验证邮箱
                return preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i',$val);break;
            case 'url':
                //验证URl
                return preg_match('/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',$val);break;
            case 'positiveInt':
                //验证正整数
                return preg_match('/^[1-9]\d*$/',$val);break;
            case 'ip':
                return preg_match('/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/',$val);
                break;
            case 'key_word':
                //验证关键词，只含有中文,1-5位
                return preg_match('/^[\x{4e00}-\x{9fa5}]{1,5}$/u',$val);break;
            case 'cate_name':
                //验证宝贝分类，只含有中文，英文，数字,1-20位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,20}$/u',$val);break;
            case 'gold_num':
                //验证金币数量，只含有数字,不能0开头,不能超过10000金币
                return preg_match('/^[1-9][0-9]{0,3}$|^10000$/',$val);break;
            case 'sp_name':
                //验证属性名(规格名)，只含有中文,1-5位
                return preg_match('/^[\x{4e00}-\x{9fa5}]{1,5}$/u',$val);break;
            case 'sp_value':
                //验证属性值(规格值)，只含有中文，英文，数字,1-10位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,10}$/u',$val);break;
            case 'web_name':
                //验证网站名称，只含有中文，英文，数字,1-5位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,5}$/u',$val);break;
            case 'web_describe':
                //验证网站名称，只含有中文，英文，数字,1-200位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,200}$/u',$val);break;
            case 'file_name':
                //验证网站名称，只含有中文，英文，数字,1-10位
                return preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{1,200}$/u',$val);break;
            default:
                return false;
        }
    }


}