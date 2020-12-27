<?php

namespace SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Larke\Admin\Http\Controller as BaseController;

/**
 * Ecdsa
 *
 * @title Ecdsa证书
 * @desc Ecdsa证书配置
 * @order 150
 * @auth true
 *
 * @create 2020-12-25
 * @author deatil
 */
class Ecdsa extends BaseController
{
    /**
     * Ecdsa创建
     *
     * @title Ecdsa创建
     * @desc Ecdsa证书创建
     * @order 1501
     * @auth true
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
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
            "private_key_type" => OPENSSL_KEYTYPE_EC, 
            "curve_name" => "secp256k1", 
            "config" => $opensslConfigPath,
        ];
        
        $numberofdays = 365;
        
        $configargs = [
            'config' => $opensslConfigPath
        ];
        
        // 生成证书
        $privkey = openssl_pkey_new($config);
        $csr = openssl_csr_new($dn, $privkey, $configargs);
        $sscert = openssl_csr_sign($csr, null, $privkey, $numberofdays, $configargs);
        
        $privkeypass = $passphrase;
        
        // 导出证书$csrkey
        openssl_x509_export($sscert, $csrkey);
        // 导出密钥$privatekey
        openssl_pkcs12_export($sscert, $privatekey, $privkey, $privkeypass);
        
        // 获取私钥
        openssl_pkcs12_read($privatekey, $certs, $privkeypass);
        if (! empty($certs['pkey'])) {
            $prikeyid = $certs['pkey'];
        } else {
            $prikeyid = '';
        }
        
        // 获取公钥
        $pub_key = openssl_pkey_get_public($csrkey);
        $keyData = openssl_pkey_get_details($pub_key);
        if (! empty($keyData['key'])) {
            $public = $keyData['key'];
        } else {
            $public = '';
        }
        
        $data = [
            'csr_key' => $csrkey,
            'private_key' => $prikeyid,
            'public_key' => $public,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
