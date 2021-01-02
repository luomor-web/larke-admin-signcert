<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use Larke\Admin\Http\Controller as BaseController;

/**
 * Eddsa
 *
 * @title Eddsa证书
 * @desc Eddsa证书配置
 * @order 160
 * @auth true
 *
 * @create 2020-12-25
 * @author deatil
 */
class Eddsa extends BaseController
{
    /**
     * Eddsa创建
     *
     * @title Eddsa创建
     * @desc Eddsa证书创建
     * @order 1601
     * @auth true
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        if (! extension_loaded('sodium')) {
            return $this->error(__('sodium 扩展不存在'));
        }
        
        $signPair = sodium_crypto_sign_keypair();
        $signSecret = sodium_crypto_sign_secretkey($signPair);
        $signPublic = sodium_crypto_sign_publickey($signPair);
        
        $signId = md5(microtime().mt_rand(10000, 99999));
        $privateId = $signId.'_eddsa_private_key.pem';
        $publicId = $signId.'_eddsa_public_key.pem';
        
        Cache::put($privateId, $signSecret, 3000);
        Cache::put($publicId, $signPublic, 3000);
        
        $data = [
            'private_key' => $privateId,
            'public_key' => $publicId,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
