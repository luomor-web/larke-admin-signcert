<?php

namespace SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

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
    
    /**
     * 下载
     *
     * @title 证书下载
     * @desc 证书下载
     * @order 1602
     * @auth false
     *
     * @param string $code
     * @return Response
     */
    public function download(string $code)
    {
        if (empty($code)) {
            return $this->error(__('code值不能为空'));
        }
        
        $data = Cache::get($code);
        if (empty($data)) {
            return $this->error(__('数据不存在'));
        }
        
        $headers = [
            'Content-Type' => 'application/text',
        ];
        
        return Response::streamDownload(function () use($data) {
            echo $data;
        }, $code, $headers);
    }
}
