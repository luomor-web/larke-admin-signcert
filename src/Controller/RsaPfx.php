<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Rsa的pfx格式证书
 *
 * @create 2020-12-29
 * @author deatil
 */
class RsaPfx extends BaseController
{
    /**
     * pfx创建
     *
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Rsa的pfx创建", 
        desc:   "Rsa的pfx创建",
        order:  1701,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        $lens = ['384', '512', '1024', '2048', '4096'];
        $len = $request->input('len');
        if (! in_array($len, $lens)) {
            $len = '2048';
        }
        
        $passphrase = $request->input('pass', null);
        if (empty($passphrase)) {
            return $this->error(__('秘钥密码不能为空'));
        }
        
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
        
        $opensslConfigPath = __DIR__ . "/../../resources/ssl/openssl.cnf";
        
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
        
        openssl_free_key($privkey);
        
        $pfxId = md5(microtime().mt_rand(10000, 99999));
        $pfxKeyId = $pfxId.'_rsa_key.pfx';
        
        Cache::put($pfxKeyId, $pfxKey, 3000);
        
        $data = [
            'csr_key' => $cerKey,
            'pfx_key' => $pfxKeyId,
            'private_key' => $prikeyid,
            'public_key' => $public,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
