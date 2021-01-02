<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

use Larke\Admin\Http\Controller as BaseController;

/**
 * RsaPfx格式转pem格式
 *
 * @title RsaPfx格式转pem格式
 * @desc RsaPfx格式转pem格式证书
 * @order 180
 * @auth true
 *
 * @create 2020-12-20
 * @author deatil
 */
class RsaPfx2Pem extends BaseController
{
    /**
     * RsaPfx格式转pem格式
     *
     * @title RsaPfx2pem格式
     * @desc RsaPfx格式转pem格式证书
     * @order 1801
     * @auth true
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $cerKey = $request->input('cer', null); // cer证书内容
        if (empty($cerKey)) {
            return $this->error(__('cer证书不能为空'));
        }
        
        $pfxKeyFile = $request->file('pfx');
        if (empty($pfxKeyFile)) {
            return $this->error(__('pfx上传文件不能为空'));
        }
        
        $pfxKeyFilename = $pfxKeyFile->getPathname();
        $pfxKey = File::get($pfxKeyFilename); // pfx文件内容
        
        if (empty($pfxKey)) {
            return $this->error(__('pfx证书不能为空'));
        }
        
        $pass = $request->input('pass', null);
        
        // 获取私钥
        openssl_pkcs12_read($pfxKey, $certs, $pass);
        if (! empty($certs['pkey'])) {
            $prikey = $certs['pkey'];
        } else {
            $prikey = '';
        }
        
        // 获取公钥
        $pubInfo = openssl_pkey_get_public($cerKey);
        $pubData = openssl_pkey_get_details($pubInfo);
        if (! empty($pubData['key'])) {
            $pubkey = $pubData['key'];
        } else {
            $pubkey = '';
        }
        
        $data = [
            'private_key' => $prikey,
            'public_key' => $pubkey,
        ];
        
        return $this->success(__('生成证书成功'), $data);
    }
}
