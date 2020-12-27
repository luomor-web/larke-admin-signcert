<?php

namespace SignCert\Controller;

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
        openssl_pkey_export($res, $privkey, $privkeypass, $config);
        
        $pubkey = openssl_pkey_get_details($res);
        $pubkey = $pubkey["key"];
        
        $data = [
            'csr_key' => '',
            'private_key' => $privkey,
            'public_key' => $pubkey,
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
        
        // 配置信息
        $dn = [
            "countryName" => "cn",
            "stateOrProvinceName" => "Beijing",
            "localityName" => "Beijing",
            "organizationName" => "LarkeAdmin",
            "organizationalUnitName" => "LarkeAdmin",
            "commonName" => "Larke Admin",
            "emailAddress" => "larke-admin@admin.com"
        ];
        
        $opensslConfigPath = __DIR__ . "/../resource/ssl/openssl.cnf";
        
        $config = [
            "private_key_bits" => $len, 
            "private_key_type" => OPENSSL_KEYTYPE_RSA, 
            "curve_name" => "secp256k1", // secp256k1 or secp384r1
            "config" => $opensslConfigPath,
        ];
        
        $configargs = [
            'config' => $opensslConfigPath,
        ];
        
        $privkeypass = $passphrase; // 私钥密码

        $numberofdays = 365; // 有效时长
        $cerKey = ""; // 生成证书内容
        $pfxKey = ""; // 密钥文件内容
    
        // 生成证书  
        $privkey = openssl_pkey_new($config); 
        $csr = openssl_csr_new($dn, $privkey, $configargs);
        $sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays, $configargs);  
        openssl_x509_export($sscert, $cerKey);
        openssl_pkcs12_export($sscert, $pfxKey, $privkey, $privkeypass);
        
        // 获取私钥
        openssl_pkcs12_read($pfxKey, $certs, $privkeypass);
        if (! empty($certs['pkey'])) {
            $prikeyid = $certs['pkey'];
        } else {
            $prikeyid = '';
        }
        
        // 获取公钥
        $pubKey = openssl_pkey_get_public($cerKey);
        $keyData = openssl_pkey_get_details($pubKey);
        if (! empty($keyData['key'])) {
            $public = $keyData['key'];
        } else {
            $public = '';
        }
        
        $data = [
            'csr_key' => $cerKey,
            'private_key' => $prikeyid,
            'public_key' => $public,
        ];
        
        return $data;
    }
}
