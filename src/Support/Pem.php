<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Support;

/**
 * pem 证书格式化
 *
 * @create 2022-12-14
 * @author deatil
 */
class Pem
{
    /**
     * 私钥 pem 格式解析为 der 数据
     * 
     * @param string $key
     * @return string
     */
    public static function privateKeyPemToDer(string $key): string
    {
        $key = str_replace([
            "-----BEGIN PRIVATE KEY-----", 
            "-----END PRIVATE KEY-----", 
            "\r\n",
            "\r",
            "\n",
        ], "", $key);
        
        $key = base64_decode($key);
        
        return $key;
    }

    /**
     * 公钥 pem 格式解析为 der 数据
     * 
     * @param string $key
     * @return string
     */
    public static function publicKeyPemToDer(string $key): string
    {
        $key = str_replace([
            "-----BEGIN PUBLIC KEY-----", 
            "-----END PUBLIC KEY-----", 
            "\r\n",
            "\r",
            "\n",
        ], "", $key);
        
        $key = base64_decode($key);
        
        return $key;
    }
}