<?php

namespace SignCert\Controller;

use phpseclib\Crypt\RSA as CryptRSA;

use Illuminate\Http\Request;

use Larke\Admin\Http\Controller as BaseController;

/**
 * Rsa
 *
 * @title Rsa证书
 * @desc Rsa证书配置
 * @order 130
 * @auth true
 *
 * @create 2020-12-25
 * @author deatil
 */
class Rsa extends BaseController
{
    /**
     * Rsa创建
     *
     * @title Rsa创建
     * @desc Rsa证书创建
     * @order 1301
     * @auth true
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $ktype = $request->input('ktype', 'pkcs8');
        if ($ktype == 'pkcs8') {
            $data = $this->pkcs8($request);
        } else {
            $data = $this->pkcs1($request);
        }
        
        return $this->success(__('创建成功'), $data);
    }
    
    /**
     * Rsa创建 pkcs1格式
     *
     * @param  Request  $request
     * @return Response
     */
    protected function pkcs1(Request $request)
    {
        $lens = ['384', '512', '1024', '2048', '4096'];
        $len = $request->input('len');
        if (! in_array($len, $lens)) {
            $len = '2048';
        }
        
        $passphrase = $request->input('pass', null);
        
        $opensslConfigPath = __DIR__ . "/../resource/ssl/openssl.cnf";
        
        $config = [
            "private_key_bits" => $len, 
            "private_key_type" => OPENSSL_KEYTYPE_RSA, 
            "curve_name" => "secp256k1", // secp256k1 or secp384r1
            "config" => $opensslConfigPath,
        ];
        
        $privkeypass = $passphrase; // 私钥密码

        $privkey = ""; // 私钥
        $pubkey = ""; // 公钥
    
        // 生成证书  
        $res = openssl_pkey_new($config); 
        openssl_pkey_export($res, $privkey, null, $config);
        
        $pubkey = openssl_pkey_get_details($res);
        $pubkey = $pubkey["key"];
        
        openssl_free_key($res);
        
        // 转换为PKCS1格式
        $rsa = new CryptRSA();
        $rsa->loadKey($privkey);
        if (! empty($passphrase)) {
            $rsa->setPassword($passphrase);
        }
        $newPrivkey = $rsa->getPrivateKey(CryptRSA::PUBLIC_FORMAT_PKCS1);
        $newPubkey = $rsa->getPublicKey(CryptRSA::PUBLIC_FORMAT_PKCS1);
        
        if ($newPrivkey === false) {
            $newPrivkey = '';
        }
        if ($newPubkey === false) {
            $newPubkey = '';
        }
        
        $data = [
            'private_key' => $newPrivkey,
            'public_key' => $newPubkey,
        ];
        
        return $data;
    }
    
    /**
     * Rsa创建 pkcs8格式
     *
     * @param  Request  $request
     * @return Response
     */
    protected function pkcs8(Request $request)
    {
        $lens = ['384', '512', '1024', '2048', '4096'];
        $len = $request->input('len');
        if (! in_array($len, $lens)) {
            $len = '2048';
        }
        
        $passphrase = $request->input('pass', null);
        
        $opensslConfigPath = __DIR__ . "/../resource/ssl/openssl.cnf";
        
        $config = [
            "private_key_bits" => $len, 
            "private_key_type" => OPENSSL_KEYTYPE_RSA, 
            "curve_name" => "secp256k1", // secp256k1 or secp384r1
            "config" => $opensslConfigPath,
        ];
        
        $privkeypass = $passphrase; // 私钥密码

        $privkey = ""; // 私钥
        $pubkey = ""; // 公钥
    
        // 生成证书  
        $res = openssl_pkey_new($config); 
        openssl_pkey_export($res, $privkey, $privkeypass, $config);
        
        $pubkey = openssl_pkey_get_details($res);
        $pubkey = $pubkey["key"];
        
        openssl_free_key($res);
        
        $data = [
            'private_key' => $privkey,
            'public_key' => $pubkey,
        ];
        
        return $data;
    }
}
