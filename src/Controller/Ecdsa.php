<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Larke\Admin\Http\Controller as BaseController;

/**
 * Ecdsa
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
     * @parent larke-admin.extension.sign-cert
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
        
        $privkeypass = $request->input('pass', null);

        $privkey = ""; // 私钥
        $pubkey = ""; // 公钥
        
        $opensslConfigPath = __DIR__ . "/../../resources/ssl/openssl.cnf";
        
        $config = [
            "private_key_bits" => $len, 
            "private_key_type" => OPENSSL_KEYTYPE_EC, 
            "curve_name" => "secp256k1", 
            "config" => $opensslConfigPath,
        ];
        
        // 生成证书
        $res = openssl_pkey_new($config); 
        openssl_pkey_export($res, $privkey, $privkeypass, $config);
        
        $pubkeyDetails = openssl_pkey_get_details($res);
        $pubkey = $pubkeyDetails["key"];
        
        openssl_free_key($res);
        
        $data = [
            'private_key' => $privkey,
            'public_key' => $pubkey,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
