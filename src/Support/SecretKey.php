<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Support;

use Exception;

/**
 * 生成公、私钥对
 *
 * @create 2022-12-14
 * @author deatil
 */
class SecretKey
{
    /**
     * 私钥资源
     * @var OpenSSLAsymmetricKey
     */
    public static $privateKey;

    /**
     * openssl配置文件地址
     * 
     * @var string
     */
    public static $_config = __DIR__ . "/../../resources/ssl/openssl.cnf";

    /**
     * 生成私钥的配置
     * 
     * @var array
     */
    private static $_option = [];

    /**
     * 生成秘钥数据
     * 
     * @param string $type 私钥类型 RSA，EC，DH，DSA
     * @return string
     */
    public static function genPrivateKey($type = 'RSA'): string
    {
        $type = strtoupper($type);
        if (! in_array($type, ['RSA', 'EC', 'DH', 'DSA'])) {
            throw new Exception('私钥类型错误');
        }
        
        $option = $type . 'Option';
        static::$option();

        static::$privateKey = openssl_pkey_new(static::$_option);
        
        // 获取私钥字符串
        openssl_pkey_export(static::$privateKey, $privateKey, '', static::$_option);
        
        return $privateKey;
    }

    /**
     * RSA秘钥生成配置
     * 
     * @return void
     */
    private static function RSAOption(): void
    {
        static::$_option = [
            'config'           => static::$_config,
            'private_key_type' => OPENSSL_KEYTYPE_RSA, // 秘钥类型
            'private_key_bits' => 2048, // 秘钥位数
        ];
    }

    /**
     * ECC秘钥生成配置
     * 
     * @return void
     */
    private static function ECOption(): void
    {
        static::$_option = [
            'config'           => static::$_config,
            'private_key_type' => OPENSSL_KEYTYPE_EC, // 秘钥类型
            'curve_name'       => 'prime256v1', // 两个广泛的标准化/支持的曲线:prime256v1(NIST P-256)和secp384r1(NIST P-384)
        ];
    }

    /**
     * DH秘钥生成配置,共享秘钥；
     *   通信双方仅通过交换一些可以公开的信息就能够生成出共享的密码数字，
     *   而这一密码数字就可以被用作对称密码的密钥
     * 
     * @return void
     */
    private static function DHOption(): void
    {
        static::$_option = [
            'config'           => static::$_config,
            'private_key_type' => OPENSSL_KEYTYPE_DH, // 秘钥类型
            'private_key_bits' => 2048, // 秘钥位数
        ];
    }

    /**
     * DSA秘钥生成配置
     * 
     * @return void
     */
    private static function DSAOption(): void
    {
        static::$_option = [
            'config'           => static::$_config,
            'private_key_type' => OPENSSL_KEYTYPE_DSA, // 秘钥类型
        ];
    }

    /**
     * 根据秘钥资源对象生成公钥
     * 
     * @return string 公钥
     */
    public static function genPublicKey(): string
    {
        return openssl_pkey_get_details(static::$privateKey)['key'];
    }

    /**
     * 将私钥导出到文件，文件格式为ascii格式(PEM编码)
     * 
     * @param string $filename 文件保存地址
     * @param string $password 加密后，读取时需指定密码
     * @return bool 成功或失败
     */
    public static function privateKeyOutFile(string $filename, string $password = ''): bool
    {
        return openssl_pkey_export_to_file(static::$privateKey, $filename, $password, static::$_option);
    }

    /**
     * 从文件中获取私钥
     * 
     * @param string $filename 文件地址
     * @param string $password 指定密码，如果生成文件时指定了密码，则必须指定，否则无法读取
     * @return string
     */
    public static function getPrivKeyByFile(string $filename, string $password = ''): string
    {
        static::$privateKey = openssl_pkey_get_private('file://' . $filename, $password);
        if (!static::$privateKey) {
            throw new Exception('读取错误,失败原因：' . implode(',', static::errors()));
        }
        
        openssl_pkey_export(static::$privateKey, $privateKey);
        return $privateKey;
    }

    /**
     * 根据证书获取公钥字符串
     * 
     * @param mixed $certif 证书资源或证书文件地址
     * @return string
     */
    public static function getPubKeyByCert($certif): string
    {
        $cert = is_resource($certif) ? $certif : ('file://'. $certif);
        return openssl_pkey_get_details(openssl_pkey_get_public($cert))['key'];
    }

    /**
     * 释放私钥资源
     * 
     * @param {*}
     * @return void
     */
    public static function freePrivateKey(): void
    {
        // 该方法在php8中已废弃
        openssl_pkey_free(static::$privateKey);
    }

    /**
     * 获取openSSL库的错误，错误消息已被队列化，可以查询到多条错误信息，最后一个是最近的一个错误。
     * 
     * @param {*}
     * @return {*}
     */
    public static function errors(): array
    {
        $result = [];
        while (($data = openssl_error_string()) !== false) {
            $result[] = $data;
        }
        
        return $result;
    }

}