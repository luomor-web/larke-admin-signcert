<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Eddsa
 *
 * @create 2020-12-25
 * @author deatil
 */
class Eddsa extends BaseController
{
    /**
     * Eddsa创建
     *
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Eddsa创建", 
        desc:   "Eddsa证书创建",
        order:  1601,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        if (! extension_loaded('sodium')) {
            return $this->error(__('sodium 扩展不存在'));
        }
        
        $keypair   = sodium_crypto_sign_keypair();
        $secretkey = sodium_crypto_sign_secretkey($keypair);
        $public    = sodium_crypto_sign_publickey($keypair);
        
        $privateKey = bin2hex($secretkey);
        $publicKey  = bin2hex($public);
        
        $data = [
            'private_key' => $privateKey,
            'public_key'  => $publicKey,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
