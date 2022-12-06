<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * 证书
 *
 * @create 2022-2-25
 * @author deatil
 */
#[RouteRule(
    title: "签名证书", 
    desc:  "用于签名所需要的证书生成",
    order: 1700,
    auth:  true,
    slug:  "larke-admin.ext.sign-cert"
)]
class Cert extends BaseController
{
    /**
     * 下载
     *
     * @param string $code
     * @return Response
     */
    #[RouteRule(
        title:  "证书下载", 
        desc:   "证书文件下载",
        order:  1701,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
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
