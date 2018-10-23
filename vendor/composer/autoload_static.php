<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95e447876aae8f9388cb76a281f659d9
{
    public static $files = array (
        '2c102faa651ef8ea5874edb585946bce' => __DIR__ . '/..' . '/swiftmailer/swiftmailer/lib/swift_required.php',
    );

    public static $prefixLengthsPsr4 = array (
        'h' => 
        array (
            'hightman\\xunsearch\\' => 19,
        ),
        'E' => 
        array (
            'Egulias\\EmailValidator\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'hightman\\xunsearch\\' => 
        array (
            0 => __DIR__ . '/..' . '/hightman/xunsearch/wrapper/yii2-ext',
        ),
        'Egulias\\EmailValidator\\' => 
        array (
            0 => __DIR__ . '/..' . '/egulias/email-validator/EmailValidator',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/lexer/lib',
            ),
        ),
    );

    public static $classMap = array (
        'EXunSearch' => __DIR__ . '/..' . '/hightman/xunsearch/wrapper/yii-ext/EXunSearch.php',
        'EasyPeasyICS' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
        'PHPMailer' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
        'PHPMailerOAuth' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauth.php',
        'PHPMailerOAuthGoogle' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauthgoogle.php',
        'POP3' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.pop3.php',
        'SMTP' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.smtp.php',
        'XS' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XS.class.php',
        'XSCommand' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSServer.class.php',
        'XSComponent' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XS.class.php',
        'XSDocument' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSDocument.class.php',
        'XSErrorException' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XS.class.php',
        'XSException' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XS.class.php',
        'XSFieldMeta' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSFieldScheme.class.php',
        'XSFieldScheme' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSFieldScheme.class.php',
        'XSIndex' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSIndex.class.php',
        'XSSearch' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSSearch.class.php',
        'XSServer' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSServer.class.php',
        'XSTokenizer' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerFull' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerNone' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerScws' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerSplit' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerXlen' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'XSTokenizerXstep' => __DIR__ . '/..' . '/hightman/xunsearch/lib/XSTokenizer.class.php',
        'ntlm_sasl_client_class' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
        'phpmailerException' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95e447876aae8f9388cb76a281f659d9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95e447876aae8f9388cb76a281f659d9::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit95e447876aae8f9388cb76a281f659d9::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit95e447876aae8f9388cb76a281f659d9::$classMap;

        }, null, ClassLoader::class);
    }
}
