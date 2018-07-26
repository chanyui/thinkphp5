<?php
/**
 * cURL功能（post）
 * Function:xcurl
 * @return mixed
 * @param string $url 地址
 * @param null $ref 包含一个”referer”头的字符串
 * @param array $post 参数
 * @param string $ua
 * @param bool|false $print
 */
if (!function_exists('xcurl')) {
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
}

/**
 * cURL功能（get）
 * Function:gcurl
 * @return mixed
 * @param string $url 地址
 * @param array $header 请求头
 * @param array $get
 * @param string $ua
 * @param bool|false $print
 */
if (!function_exists('gcurl')) {
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
}

/**
 * 导出 Excel
 * +-----------------------------------------------------------
 * @param $expTitle string 导出文件的名称
 * +-----------------------------------------------------------
 * @param $expCellName array 导出字段的名称
 * +-----------------------------------------------------------
 * @param $expTableData array  导出的数据
 * +-----------------------------------------------------------
 */
if (!function_exists('exportExcel')) {
    function exportExcel($expTitle, $expCellName, $expTableData)
    {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle;
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("phpoffice.phpexcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }

        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}

/**
 * 导入 Excel
 * +-----------------------------------------------------------
 * @param $file $_FILES['file']['tmp_name']
 * +-----------------------------------------------------------
 * @return array
 * +-----------------------------------------------------------
 */
if (!function_exists('importExecl')) {
    function importExecl($file)
    {
        if (!file_exists($file)) {
            return array("error" => 0, 'message' => 'file not found!');
        }
        //import("PHPExcel.PHPExcel.IOFactory",EXTEND_PATH);
        vendor("phpoffice.phpexcel");
        $fileType = \PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($fileType);
        try {
            $objReader->setReadDataOnly(true);              //只读取数据，去除其他格式
            $PHPReader = $objReader->load($file);
        } catch (Exception $e) {
        }
        if (!isset($PHPReader)) return array("error" => 1, 'message' => 'read error!');
        $allWorksheets = $PHPReader->getAllSheets();
        $i = 0;
        foreach ($allWorksheets as $objWorksheet) {
            $sheetname = $objWorksheet->getTitle();
            $allRow = $objWorksheet->getHighestRow();//how many rows
            $highestColumn = $objWorksheet->getHighestColumn();//how many columns
            $allColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $array[$i]["Title"] = $sheetname;
            $array[$i]["Cols"] = $allColumn;
            $array[$i]["Rows"] = $allRow;
            $arr = array();
            $isMergeCell = array();
            foreach ($objWorksheet->getMergeCells() as $cells) {//merge cells
                foreach (PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference) {
                    $isMergeCell[$cellReference] = true;
                }
            }
            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
                $row = array();
                for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++) {
                    ;
                    $cell = $objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
                    $afCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn + 1);
                    $bfCol = PHPExcel_Cell::stringFromColumnIndex($currentColumn - 1);
                    $col = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                    $address = $col . $currentRow;
                    $value = (string)$objWorksheet->getCell($address)->getValue();
                    if (substr($value, 0, 1) == '=') {
                        return array("error" => 0, 'message' => 'can not use the formula!');
                        exit;
                    }
                    if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_NUMERIC) {
                        $cellstyleformat = $cell->getStyle($cell->getCoordinate())->getNumberFormat();
                        $formatcode = $cellstyleformat->getFormatCode();
                        if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode)) {
                            $value = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($value));
                        } else {
                            $value = PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                        }
                    }
                    if ($isMergeCell[$col . $currentRow] && $isMergeCell[$afCol . $currentRow] && !empty($value)) {
                        $temp = $value;
                    } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$col . ($currentRow - 1)] && empty($value)) {
                        $value = $arr[$currentRow - 1][$currentColumn];
                    } elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$bfCol . $currentRow] && empty($value)) {
                        $value = $temp;
                    }
                    $row[$currentColumn] = $value;
                }
                $arr[$currentRow] = $row;
            }
            $array[$i]["Content"] = $arr;
            $i++;
        }
        spl_autoload_register('Think\Think::autoload');
        unset($objWorksheet);
        unset($PHPReader);
        unset($PHPExcel);
        unlink($file);
        return array("error" => 0, "data" => $array);
    }
}

/**
 * 读取Excel数据
 * +-----------------------------------------------------------
 * @functionName : readExcel
 * +-----------------------------------------------------------
 * @param string $file Excel文件路径
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 */
if (!function_exists('readExcel')) {
    function readExcel($file)
    {
        vendor("phpoffice.phpexcel");
        $fileType = \PHPExcel_IOFactory::identify($file);
        $PHPReader = \PHPExcel_IOFactory::createReader($fileType);
        $PHPReader->setReadDataOnly(true);              //只读取数据，去除其他格式
        $objPHPExcel = $PHPReader->load($file);         //读取Excel文件
        $currentSheet = $objPHPExcel->getSheet(0);      //获取第一个工作表
        $allColumn = $currentSheet->getHighestColumn(); //Excel所有列数最大值
        $allRow = $currentSheet->getHighestRow();       //Excel总行数
        $content = $currentSheet->toArray();
        return array('row' => $allRow, 'col' => $allColumn, 'data' => $content);
    }
}

/**
 * PHPMailer发送邮件
 * +-----------------------------------------------------------
 * @functionName : sendPHPMail
 * +-----------------------------------------------------------
 * @param array $tomail 要发送邮件给那个人
 * @param string $title 邮件的标题
 * @param string $body 邮件的内容
 * @param array $config 邮件的配置文件
 * @param string $filePath 文件路径
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 */
if (!function_exists('sendPHPMail')) {
    function sendPHPMail($tomail, $title, $body, $config = [], $filePath = '')
    {
        vendor('phpmailer.phpmailer.PHPMailerAutoload.php');
        $mail = new PHPMailer();  // 实例化PHPMailer核心类

        //Server settings
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 0;
        if ($config['mail_type'] == 'smtp') {
            // 使用smtp鉴权方式发送邮件
            $mail->isSMTP();
            // smtp需要鉴权 这个必须是true
            $mail->SMTPAuth = true;
        }
        // 链接smtp.163.com域名服务器地址
        $mail->Host = $config['mail_host'];
        // smtp登录的账号 QQ邮箱即可
        $mail->Username = $config['mail_user'];
        // smtp登录的密码 使用生成的授权码
        $mail->Password = $config['mail_pwd'];
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = $config['mail_port'];

        // 添加多个收件人 则多次调用方法即可
        //$mail->addAddress('87654321@163.com');
        if (is_array($tomail)) {
            foreach ($tomail as $key => $value) {
                $mail->addAddress($value);
            }
        } else {
            //Recipients(收件人)
            // 设置收件人邮箱地址
            $mail->addAddress($tomail);
        }

        //Attachments
        // 为该邮件添加附件
        if ($filePath) {
            $name = basename($filePath) ?: '';
            $mail->addAttachment($filePath, $name);
        }
        //$mail->addAttachment('./example.pdf');

        //Content
        // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
        //$mail->Encoding = "base64"; //编码方式
        // 邮件正文是否为html编码 注意此处是一个方法
        $mail->isHTML(true);
        // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = $config['send_name'];
        // 设置发件人邮箱地址 同登录账号
        $mail->From = $config['mail_user'];
        // 添加该邮件的主题
        $mail->Subject = $title;
        // 添加邮件正文
        $mail->Body = $body;

        try {
            // 发送邮件 返回状态
            $status = $mail->send();
            if ($status) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}

/**
 * PHPMailer发送邮件
 * +-----------------------------------------------------------
 * @functionName : sendSwiftMailer
 * +-----------------------------------------------------------
 * @param array $tomail 要发送邮件给那个人
 * @param string $subject 邮件的标题
 * @param string $body 邮件的内容
 * @param array $config 邮件的配置文件
 * @param string $filePath 文件路径
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * return bool|string
 * +-----------------------------------------------------------
 */
if (!function_exists('sendSwiftMailer')) {
    function sendSwiftMailer($tomail, $subject, $body, $config = [], $filePath = '')
    {
        $transport = (new Swift_SmtpTransport($config['mail_host'], $config['mail_port']))
            ->setUsername($config['mail_user'])
            ->setPassword($config['mail_pwd']);

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message($subject))
            ->setFrom([$config['mail_user']])
            ->setTo([$tomail])
            ->setBody($body);

        // Send the message
        try {
            // 发送邮件 返回状态
            $result = $mailer->send($message);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $exception->getError();
        }
    }
}

/**
 * 获取随机码
 * Function:random
 * @param int $length 随机码的长度
 * @param int $numeric 0是字母和数字混合码，不为0是数字码
 * @return string
 */
if (!function_exists('random')) {
    function random($length, $numeric = 0)
    {
        PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
        $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }
}

/**
 * 加密解密（可逆）
 * Function:authcode
 * @param string $string 加密的字符串
 * @param string $operation DECODE表示解密,其它表示加密
 * @param string $key 密钥
 * @param int $expiry 密文有效期
 * @return string
 */
if (!function_exists('authcode')) {
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
}

/**
 * 加密（不可逆）
 * Function:encrypt
 * @param string $password 原始密码
 * @param string $salt 密钥
 * @return string
 */
if (!function_exists('encrypt')) {
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
}

/**
 * 加密（可逆）
 * Function:wotu_crypt
 * @return string
 */
if (!function_exists('reversible_crypt')) {
    function reversible_crypt($str, $op = 'enc', $key = 'wotu')
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
}

/**
 * 生成密码
 * Function:get_password
 * @param string $password 原始密码
 * @param string $salt 密钥
 * @return string
 */
if (!function_exists('get_password')) {
    function get_password($password, $salt)
    {
        return encrypt($password, $salt);
    }
}

/**
 * 验证密码
 * Function:check_password
 * @param string $password 原始密码
 * @param string $salt 密钥
 * @param string $pwd 加密后密码
 * @return bool
 */
if (!function_exists('check_password')) {
    function check_password($password, $salt, $pwd)
    {
        if (get_password($password, $salt) == $pwd) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * 手机发送验证码
 * Function:send_by_phone
 * @param int $phone 手机号码
 * @param string $message 模板id为0是发送信息，模板id不为0是者模板值
 * @param int $tpl_id 模板id
 * @return bool
 */
if (!function_exists('send_by_phone')) {
    function send_by_phone($phone, $message, $tpl_id = 0)
    {
        if (!$phone || !$message) {
            return false;
        }

        $apikey = 'b66e3c9ac9e3a877d690560892c43d5f';
        $mobile = $phone;

        $sendSms = new \sendSms();
        if ($tpl_id) {
            $tpl_value = $message;
            $output = $sendSms->tpl_send_sms($apikey, $tpl_id, $tpl_value, $mobile);
        } else {
            $text = $message;
            $output = $sendSms->send_sms($apikey, $text, $mobile);
        }
        $output = json_decode($output, true);
        if ($output['code'] != 0) {
            return false;
        } else {
            return true;
        }
    }
}


/**
 * 创建session
 * Function:set_session
 * @param array $session session的数组
 * @param string $name session存储的名称
 * @return bool
 */
if (!function_exists('set_session')) {
    function set_session($session, $name)
    {
        if (is_array($session)) {
            $session = json_encode($session);
        }
        $key = config('secret_key');
        $session = authcode($session, 'ENCODE', $key);
        session($name, $session);
    }
}

/**
 * 获得session
 * Function:get_session
 * @param string $name session存储的名称
 * @return array|bool
 */
if (!function_exists('get_session')) {
    function get_session($name)
    {
        $key = config('secret_key');
        $auth = session($name);

        if ($auth) {
            $session = authcode($auth, 'DECODE', $key);
            $ary = json_decode($session, true);
            if (!$ary) {
                $ary = $session;
            }
            return $ary;
        } else {
            return false;
        }
    }
}

/**
 * 设置用户在线
 * Function:set_online
 * @param $uid
 */
/*function set_online($uid)
{
    $db = M('user');
    $tmp = array(
        'uid' => $uid,
        'lastIp' => $_SERVER['REMOTE_ADDR'],
        'lastTime' => time()
    );
    $db->save($tmp);
    $db = M('session_online');
    $liveTime = time() + config('SESSION_EXPIRY');
    $expriy = array(
        'uid' => $uid,
        'expiry' => $liveTime,
        'sessionId' => session_id()
    );
    $isOn = $db->where('uid = "' . $uid . '"')->find();
    if ($isOn) {
        $db->save($expriy);
    } else {
        $db->add($expriy);
    }
}*/


/**
 * 七牛上传图片加裁切缩放
 * Function:QiNiuUpload
 * @param array $file 为要上传的文件
 * @param array $data 裁切参数 $data['x'] 起点x轴  $data['y'] 起点y轴 $data['w'] $data['h'] 图片预裁切宽高 $data['targetW'] $data['targetH']图片尺寸
 * @return mixed
 */
if (!function_exists('QiNiuUpload')) {
    function QiNiuUpload($file, $data)
    {
        $setting = config('UPLOAD_SITEIMG_QINIU');
        $Upload = new \Think\Upload($setting);
        $domain = $setting["driverConfig"]["domain"];
        $info = $Upload->upload(array($file));
        /*裁切*/
        $img = $info[0]['url'];
        $data['copy'] = basename($img);
        $crop = $Upload->uploader->imgCrop($img, $data);
        foreach ($crop as $k => $v) {
            $imgArr = json_decode($v);
            $imgR[$k] = "http://" . $domain . "/" . $imgArr->key;
        }
        return $imgR;
    }
}

/**
 * 七牛上传附件
 * Function:QiNiuUploadFile
 * @param array $file 为要上传的文件
 * @return array|bool
 */
if (!function_exists('QiNiuUploadFile')) {
    function QiNiuUploadFile($file)
    {
        $setting = config('UPLOAD_SITEIMG_QINIU');
        $Upload = new \Think\Upload($setting);
        $info = $Upload->upload(array($file));
        return $info;
    }
}

/**
 * 字符串截取
 * Function:subtext
 * @param string $text 需要截取的字符串
 * @param int $length 截取长度
 * @return string
 */
if (!function_exists('subtext')) {
    function subtext($text, $length)
    {
        if (mb_strlen($text, 'utf8') > $length)
            return mb_substr($text, 0, $length, 'utf8') . '...';
        return $text;
    }
}

/**
 * 时间转换成秒、分、小时、天
 * @Name:formatTime
 * @Description:
 * @HideInMenu:0
 * @param $time
 * @return string str
 */
if (!function_exists('formatTime')) {
    function formatTime($time)
    {
        $ago = time() - $time;
        if ($ago < 60) {
            return $ago . ' sec';
        } elseif ($ago >= 60 && $ago < 3600) {
            return round($ago / 60) . ' min';
        } elseif ($ago >= 3600 && $ago < 3600 * 24) {
            return round($ago / 3600) . ' hour';
        } else {
            return round($ago / (3600 * 24)) . ' day';
        }
    }
}

/**
 * +----------------------------------------------------------
 * 二维数组根据某个字段排序
 * +----------------------------------------------------------
 * @param  array $multi_array 排序数组
 * +----------------------------------------------------------
 * @param  string $sort_key 排序字段
 * +----------------------------------------------------------
 * @param  string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 * +----------------------------------------------------------
 */
if (!function_exists('multi_array_sort')) {
    function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
    {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }
}

/**
 * 获取中文字符拼音首字母
 * +-----------------------------------------------------------
 * @functionName : getFirstCharter
 * +-----------------------------------------------------------
 * @param string $str
 * +-----------------------------------------------------------
 * @return null|string
 */
if (!function_exists('getFirstCharter')) {
    function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $encode = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
        if ($encode != 'GB2312') {
            $str = iconv($encode, 'GB2312', trim($str));
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('Z')) return strtoupper($str{0});
        $asc = ord($str{0}) * 256 + ord($str{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }
}

/**
 * 生成二维码
 * @param $url string 地址
 */
if (!function_exists('generateQRCode')) {
    function generateQRCode($url)
    {
        import('phpQrCode.phpqrcode', EXTEND_PATH); //引入phpqrcode类
        $QRcode = new \QRcode();
        ob_start();
        $QRcode->png($url, false, 'L', 4);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();
        return $imageString;
    }
}

/**
 * 获取视频信息
 * @param $file string 视频文件
 * @return array 视频信息
 */
if (!function_exists('getVideoInfo')) {
    function getVideoInfo($file)
    {
        $command = sprintf(config('FFMPEG_PATH'), $file);
        ob_start();
        passthru($command);
        $info = ob_get_contents();
        ob_end_clean();

        $data = array();
        if (preg_match("/Duration: (.*?), start: (.*?), bitrate: (\d*) kb\/s/", $info, $match)) {
            $data['duration'] = $match[1]; //播放时间
            $arr_duration = explode(':', $match[1]);
            $data['seconds'] = $arr_duration[0] * 3600 + $arr_duration[1] * 60 + $arr_duration[2]; //转换播放时间为秒数
            $data['start'] = $match[2]; //开始时间
            $data['bitrate'] = $match[3]; //码率(kb)
        }
        if (preg_match("/Video: (.*?), (.*?), (.*?)[,\s]/", $info, $match)) {
            $data['vcodec'] = $match[1]; //视频编码格式
            $data['vformat'] = $match[2]; //视频格式
            $data['resolution'] = $match[3]; //视频分辨率
            $arr_resolution = explode('x', $match[3]);
            $data['width'] = $arr_resolution[0];
            $data['height'] = $arr_resolution[1];
        }
        if (preg_match("/Audio: (\w*), (\d*) Hz/", $info, $match)) {
            $data['acodec'] = $match[1]; //音频编码
            $data['asamplerate'] = $match[2]; //音频采样频率
        }
        if (isset($data['seconds']) && isset($data['start'])) {
            $data['play_time'] = $data['seconds'] + $data['start']; //实际播放时间
        }
        $data['size'] = filesize($file); //文件大小
        return $data;
    }
}

/**
 * 将秒格式化为 00:00:00格式
 * +-----------------------------------------------------------
 * @functionName : formatSec
 * +-----------------------------------------------------------
 * @param int $seconds 秒数
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * @return string
 */
if (!function_exists('formatSec')) {
    function formatSec($seconds)
    {
        if (!$seconds || $seconds <= 0) {
            return '未知';
        }
        $hour = floor($seconds / 3600);
        $min = floor(($seconds % 3600) / 60);
        $sec = $seconds % 60;
        if ($hour < 10) {
            $hour = '0' . $hour;
        }
        if ($min < 10) {
            $min = '0' . $min;
        }
        if ($sec < 10) {
            $sec = '0' . $sec;
        }
        return $hour . ':' . $min . ':' . $sec;
    }
}

/**
 * 冒泡排序算法
 * +----------------------------------------------------------
 * @param array $arr 要排序的数组
 * +----------------------------------------------------------
 * @return array|boolean
 * +----------------------------------------------------------
 */
if (!function_exists('bubble_sort')) {
    function bubble_sort($arr)
    {
        $len = count($arr);
        if ($len <= 0) {
            return false;
        }
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
}

/**
 * 获取日期和星期，默认为当前时间
 * +----------------------------------------------------------
 * @param $string $time $del1,$del2,$del3
 * +----------------------------------------------------------
 * @return string
 * +----------------------------------------------------------
 */
if (!function_exists('getDateStr')) {
    function getDateStr($time, $del1 = '年', $del2 = '月', $del3 = '日')
    {
        $dayArr = array('日', '一', '二', '三', '四', '五', '六');
        $day = date('w', $time); //一周中的第几天 0~6
        return date("Y{$del1}m{$del2}d{$del3}", $time) . ' 星期' . $dayArr[$day];
    }
}

/**
 * 菲波那切数列递归版（没有使用静态变量，效率非常低）
 * +-----------------------------------------------------------
 * @functionName : fibonacci
 * +-----------------------------------------------------------
 * @param int $a 数列的第几个key
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * @return int
 */
if (!function_exists('fibonacci')) {
    function fibonacci($a)
    {
        if ($a <= 2) {
            return $cache[1] = $cache[2] = 1;
        } else {
            return $cache[$a] = fibonacci($a - 1) + fibonacci($a - 2);
        }
    }
}

/**
 * 菲波那切数列递归版（使用静态变量，效率高）
 * +-----------------------------------------------------------
 * @functionName : fibonacci_cache
 * +-----------------------------------------------------------
 * @param int $a 数列的第几个key
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * @return int|mixed
 */
if (!function_exists('fibonacci_cache')) {
    function fibonacci_cache($a)
    {
        static $cache = array(); //静态变量
        if (isset($cache[$a])) {
            return $cache[$a];
        } else {
            if ($a <= 2) {
                return $cache[1] = $cache[2] = 1;
            } else {
                return $cache[$a] = fibonacci_cache($a - 1) + fibonacci_cache($a - 2);
            }
        }
    }
}

/**
 * 斐波那切数列非递归版（返回数组）
 * +-----------------------------------------------------------
 * @functionName : fibonac
 * +-----------------------------------------------------------
 * @param int $num 数列的总数
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * @return array
 */
if (!function_exists('fibonac')) {
    function fibonac($num)
    {
        $array = array();
        $array[0] = 0;
        $array[1] = 1;
        for ($i = 2; $i < $num; $i++) {
            $array[$i] = $array[$i - 1] + $array[$i - 2];
        }
        return $array;
    }
}

/**
 * 获取红包（1个分100有问题） 需改进
 * +-----------------------------------------------------------
 * @functionName : get_redEnvelope
 * +-----------------------------------------------------------
 * @param int $total 红包总金额
 * @param int $num 红包数量
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 * @return int|array 随机红包的金额
 * +-----------------------------------------------------------
 */
if (!function_exists('get_redEnvelope')) {
    function get_redEnvelope($total, $num)
    {
        static $current = array();
        if ($num == 1) {
            $current[] = $total;
            return $current;
        } else {
            $min = 0.01;
            $max = round(($total / $min) / $num);
            $rand_money = rand($min * 100, $max * 2);
            $money = $rand_money * $min;
            $current[] = $money;
            $lava_money = $total - $money;
            return get_redEnvelope($lava_money, $num - 1);
        }
    }

}

/**
 * 拆分红包算法（1个分100有问题） 需改进
 * +-----------------------------------------------------------
 * @functionName : get_split
 * +-----------------------------------------------------------
 * @param int $total 红包总金额
 * @param int $num 红包总个数
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 */
if (!function_exists('get_split')) {
    function get_split($total, $num)
    {
        $min = 0.01;        //每个人最少能收到0.01元
        for ($i = 1; $i < $num; $i++) {
            $safe_total = ($total - ($num - $i) * $min) / ($num - $i);//随机安全上限 这个不对有误差
            $money = mt_rand($min * 100, $safe_total * 100) / 100;
            $getMoney = $total - $money;
            echo '第' . $i . '个红包：' . $money . ' 元，余额：' . $getMoney . ' 元 <br/>';
        }
        echo '第' . $num . '个红包：' . $total . ' 元，余额：0 元';
    }
}

/**
 * 获取一个分类下面的所有分类id (写在Model里面)
 * +-----------------------------------------------------------
 * @functionName : getIdArr
 * +-----------------------------------------------------------
 * @param int $id 分类id
 * +-----------------------------------------------------------
 * @author yc
 * +-----------------------------------------------------------
 */
if (!function_exists('getIdArr')) {
    function getIdArr($id)
    {
        //分类层级数
        $levelSet = 3;
        $db = M();
        $where = [
            'if_show' => '1',
            'store_id' => 0,
        ];
        //查看当前是否在最后一级分类
        $is_end = $db->where(array_merge($where, ['cate_id' => $id]))->getField('is_end');
        if ($is_end === '1') return [$id];
        $idArr = [$id];
        $sonWhere = array_merge($where, ['parent_id' => $id]);
        for ($i = $levelSet; $i >= 0; $i--) {
            $arr = $db->where($sonWhere)->getField('cate_id,is_end');
            //没有下级分类结束循环
            if (empty($arr)) break;
            $cateArr = array_keys($arr);
            $idArr = array_merge($idArr, $cateArr);
            //到达最下级分类结束循环
            if (current($arr) === '1') break;
            $sonWhere['parent_id'] = ['in', join(',', $cateArr)];
        }
        return $idArr;
    }
}

// +----------------------------------------------------------------------
// | Author: Lance Li
// +----------------------------------------------------------------------
// | Version: 生成批量更新语句，1.0
// +----------------------------------------------------------------------
// | param: string tableName 表名称（不需要前缀）
// +----------------------------------------------------------------------
// | param: string $key 主键的名称
// +----------------------------------------------------------------------
// | param: array $updateArr 需要更新的二维数组
// +----------------------------------------------------------------------
// | return: string|bool
// +----------------------------------------------------------------------
// | Last edit time: 2018-3-28
// +----------------------------------------------------------------------
if (!function_exists('_getMysqlBatchUpdateStatement')) {
    function _getMysqlBatchUpdateStatement($tableName, $key, array $updateArr)
    {
        $tableName = strtolower(parse_name($tableName));
        $sql = 'UPDATE ' . config('prefix') . $tableName . ' SET ';
        $setKeyArr = array_keys($updateArr[0]);
        foreach ($setKeyArr as $v) {
            if ($v !== $key) {
                $sql .= $v . ' = CASE ' . $key . ' ';
                foreach ($updateArr as $vv) {
                    $sql .= 'WHEN ' . $vv[$key] . ' THEN \'' . $vv[$v] . '\' ';
                }
                $sql .= 'END,';
            }
        }
        $sql = trim($sql, ',');
        $sql .= ' WHERE ' . $key . ' IN (' . join(',', get_rows_by_array($updateArr, $key)) . ')';
        return $sql;
    }
}

// +----------------------------------------------------------------------
// | Author: Lance Li
// +----------------------------------------------------------------------
// | Version: 二维数组根据指定下标排序，1.0
// +----------------------------------------------------------------------
// | param: array $arr 需要排序的数组
// +----------------------------------------------------------------------
// | param: string $field 需要排序的数组的下标
// +----------------------------------------------------------------------
// | param: string $sort 排序类型
// |        SORT_ASC - 默认，按升序排列。
// |        SORT_DESC - 按降序排列
// +----------------------------------------------------------------------
// | param: array $type 排序类型
// |        SORT_REGULAR - 默认。将每一项按常规顺序排列。
// |        SORT_NUMERIC - 将每一项按数字顺序排列。
// |        SORT_STRING - 将每一项按字母顺序排列
// +----------------------------------------------------------------------
// | return: string|bool
// +----------------------------------------------------------------------
// | Last edit time: 2018-4-9
// +----------------------------------------------------------------------
if (!function_exists('_getTwoDimensionalArrSort')) {
    function _getTwoDimensionalArrSort(array $arr, $field, $sort = SORT_ASC, $type = SORT_REGULAR)
    {
        if (empty($arr)) return false;
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $key_array[$k] = $v[$field];
            } else {
                return false;
            }
        }
        array_multisort($key_array, $sort, $type, $arr);
        return $arr;
    }
}

// +----------------------------------------------------------------------
// | Author: Lance Li
// +----------------------------------------------------------------------
// | Version: 获取完整的url，1.0
// +----------------------------------------------------------------------
// | return: string
// +----------------------------------------------------------------------
// | Last edit time: 2018-4-10
// +----------------------------------------------------------------------
if (!function_exists('getAllURL')) {
    function getAllURL()
    {
        $pageURL = 'http';

        if ($_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

/**
 * url加密
 */
if (!function_exists('url_encode')) {
    function url_encode($url)
    {
        if (!$url) return false;
        $data = str_replace('/', '_', urlencrypt(serialize($url), 'E'));
        $data = str_replace('+', '-', $data);
        $data = str_replace('%', '^', $data);
        return $data;
    }
}

/**
 * url解密
 */
if (!function_exists('url_decode')) {
    function url_decode($url)
    {
        if (!$url) return false;
        $data = str_replace('^', '%', $url);
        $data = str_replace('-', '+', $data);
        $data = unserialize(urlencrypt(str_replace('_', '/', $data), 'D'));
        return $data;
    }
}

/**
 * 用户加密
 */
if (!function_exists('user_encode')) {
    function user_encode($string = NULL, $key = '')
    {
        return urlencrypt($string, 'E', $key);
    }
}

/**
 * 用户解密
 */
if (!function_exists('user_decode')) {
    function user_decode($string = NULL, $key = '')
    {
        return urlencrypt($string, 'D', $key);
    }
}

/*********************************************************************
 * 函数名称:urlencrypt
 * 函数作用:加密解密字符串
 * 使用方法:
 * 加密     :encrypt('str','E','nowamagic');
 * 解密     :encrypt('被加密过的字符串','D','fno2o');
 * 参数说明:
 * $string   :需要加密解密的字符串
 * $operation:判断是加密还是解密:E:加密   D:解密
 * $key      :加密的钥匙(密匙);
 *********************************************************************/
if (!function_exists('urlencrypt')) {
    function urlencrypt($string, $operation, $key = 'zj')
    {
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
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
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}


/**
 * +----------------------------------------------------------
 * 获取数组中某个键名的所有的键值
 * +----------------------------------------------------------
 * @param  array $array 数组
 * +----------------------------------------------------------
 * @param  string $row 键名
 * +----------------------------------------------------------
 */
if (!function_exists('get_rows_by_array')) {
    function get_rows_by_array($array, $row)
    {
        $result = array();
        foreach ($array as $k => $value) {
            $result[] = $value[$row];
        }
        return $result;
    }
}

/**
 * +----------------------------------------------------------
 * 以数组中某个键名作为数组的键名得到新的数组
 * +----------------------------------------------------------
 * @param  array $array 数组
 * +----------------------------------------------------------
 * @param  string $row 键名
 * +----------------------------------------------------------
 */
if (!function_exists('get_array_by_rows')) {
    function get_array_by_rows($array, $row)
    {
        $result = array();
        foreach ($array as $k => $value) {
            $result[$value[$row]] = $value;
        }
        return $result;
    }
}

/**
 * +----------------------------------------------------------
 * 以数组中的两个键值分别作为数组的键名，键值得到新的数组
 * +----------------------------------------------------------
 * @param  array $array 数组
 * +----------------------------------------------------------
 * @param  string $key1 键名
 * +----------------------------------------------------------
 * @param  string $key2 键名
 * +----------------------------------------------------------
 */
if (!function_exists('get_array_by_key')) {
    function get_array_by_key($array, $key1, $key2)
    {
        $result = array();
        foreach ($array as $k => $value) {
            $result[$value[$key1]] = $value[$key2];
        }
        return $result;
    }
}

/**
 * +----------------------------------------------------------
 * 以数组的某个字段作为键得到新的数组
 * +----------------------------------------------------------
 * @param  array $array 数组
 * +----------------------------------------------------------
 * @param  string $key 键名
 */
if (!function_exists('get_array_by_field')) {
    function get_array_by_field($array, $key)
    {
        $result = array();
        foreach ($array as $k => $value) {
            $result[$value[$key]][] = $value;
        }
        return $result;
    }
}

/**
 * +----------------------------------------------------------
 * 判断字符串的字符个数
 * +----------------------------------------------------------
 * @param  string $string 字符串
 * +----------------------------------------------------------
 */
if (!function_exists('check_string_length')) {
    function check_string_length($string)
    {
        $len = strlen($string);
        preg_match_all("/./us", $string, $match);
        $len = count($match[0]);
        return $len;
    }
}

/**
 * +----------------------------------------------------------
 * 生成唯一订单号
 * +----------------------------------------------------------
 * @param  string $string 字符串
 * +----------------------------------------------------------
 */
if (!function_exists('build_order_no')) {
    function build_order_no()
    {
        return date('ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}