<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 *  cURL功能（post）
 * +----------------------------------------------------------
 * @param  string $url 地址
 * +----------------------------------------------------------
 * @param  string $ref 包含一个”referer”头的字符串
 * +----------------------------------------------------------
 * @param  array $post 参数
 * +----------------------------------------------------------
 */
function xcurl($url, $ref = null, $post = array(), $ua = "Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre", $print = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if (!empty($ref)) {
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($ua)) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    }
    if (count($post) > 0) {
        $o = "";
        foreach ($post as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post = substr($o, 0, -1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $output = curl_exec($ch);
    curl_close($ch);
    if ($print) {
        print($output);
    } else {
        return $output;
    }
}

/**
 *  cURL功能（get）
 * +----------------------------------------------------------
 * @param string $url 地址
 * +----------------------------------------------------------
 * @param string $header 包含一个”referer”头的字符串
 * +----------------------------------------------------------
 * @param array $get 参数
 * +----------------------------------------------------------
 */
function gcurl($url, $header = array(), $get = array(), $ua = "Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre", $print = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    if (count($get) > 0) {
        $o = "";
        foreach ($get as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $get = substr($o, 0, -1);
        $url = $url . '?' . $get;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($ua)) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    }

    $output = curl_exec($ch);
    curl_close($ch);
    if ($print) {
        print($output);
    } else {
        return $output;
    }
}

/**
 * 生成随机码
 * +------------------------------------------------------------------
 * @functionName : random
 * +------------------------------------------------------------------
 * @param int $length 随机码的长度
 * @param int $numeric 0是字母和数字混合码，不为0是数字码
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return string
 */
function random($length, $numeric = 0)
{
    $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $res = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $res .= $seed[mt_rand(0, $max)];
    }
    return $res;
}

/**
 * 生成密码
 * +------------------------------------------------------------------
 * @functionName : get_password
 * +------------------------------------------------------------------
 * @param string $passowrd 原始密码
 * @param string $salt 密钥
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return string
 */
function get_password($passowrd, $salt)
{
    $slt = $passowrd . '{' . $salt . '}';
    $h = 'sha256';
    $digest = hash($h, $slt, true);
    for ($i = 0; $i < 5000; $i++) {
        $digest = hash($h, $digest . $salt, true);
    }
    return base64_encode($digest);
}

/**
 * 验证密码是否正确
 * +------------------------------------------------------------------
 * @functionName : check_password
 * +------------------------------------------------------------------
 * @param string $password 原始密码
 * @param string $salt 密钥
 * @param string $pwd 加密后的密码
 * +------------------------------------------------------------------
 * @author yucheng
 * +------------------------------------------------------------------
 * @return bool
 */
function check_password($password, $salt, $pwd)
{
    $res = get_password($password, $salt);
    if ($res == $pwd) {
        return true;
    } else {
        return false;
    }
}

/**
 * 加密解密（可逆）
 * +----------------------------------------------------------
 * @param  string $string 加密的字符串
 * +----------------------------------------------------------
 * @param  string $operation DECODE表示解密,其它表示加密
 * +----------------------------------------------------------
 * @param  string $key 密钥
 * +----------------------------------------------------------
 * @param  string $expiry 密文有效期
 * +----------------------------------------------------------
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    $ckey_length = 4;
    $key = md5($key ? $key : "da7b4db15be94a4c597a34f9cf902b01");
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }

}

/**
 * 加密解密（可逆）
 * +----------------------------------------------------------
 * @param  string $string 加密的字符串
 * +----------------------------------------------------------
 * @param  string $op dec表示解密,enc表示加密
 * +----------------------------------------------------------
 * @param  string $key 密钥
 * +----------------------------------------------------------
 */
function mcr_crypt($str, $op = 'enc', $key = 'wotu')
{
    $from = array('/', '=', '+');
    $to = array('-', '_', '.');
    if ($op == 'enc') {
        $prep_code = serialize($str);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);
        return str_replace($from, $to, base64_encode($encrypt));
    } else if ($op == 'dec') {
        $str = str_replace($to, $from, $str);
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }
        return unserialize($str);
    }
}

/**
 * 加密（不可逆）
 * +----------------------------------------------------------
 * @param  string $password 原始密码
 * +----------------------------------------------------------
 * @param  string $salt 密钥
 * +----------------------------------------------------------
 */
function encrypt($password, $salt)
{
    $slt = $password . '{' . $salt . "}";
    $h = 'sha256';

    $digest = hash($h, $slt, true);

    for ($i = 1; $i < 5000; $i++) {
        $digest = hash($h, $digest . $slt, true);
    }

    return base64_encode($digest);
}

/**
 * 冒泡排序算法
 * +----------------------------------------------------------
 * @param array $arr 要排序的数组
 * +----------------------------------------------------------
 * @return array
 * +----------------------------------------------------------
 */
function bubble_sort($arr)
{
    $len = count($arr);
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $len - 1 - $i; $j++) {
            if ($arr[$j] < $arr[$j + 1]) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $tmp;
            }
        }
    }
    return $arr;
}