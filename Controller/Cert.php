<?php

namespace SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use Larke\Admin\Http\Controller as BaseController;

/**
 * 证书
 *
 * @title 证书
 * @desc 证书下载相关
 * @order 170
 * @auth false
 *
 * @create 2020-12-25
 * @author deatil
 */
class Cert extends BaseController
{
    /**
     * 下载
     *
     * @title 证书下载
     * @desc 证书文件下载
     * @order 1701
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
