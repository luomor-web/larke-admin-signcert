<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Hmac证书
 *
 * @create 2020-12-25
 * @author deatil
 */
class Hmac extends BaseController
{
    /**
     * Hmac创建
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Hmac创建", 
        desc:   "Hmac证书创建",
        order:  1201,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        $types = ['sha256', 'sha384', 'sha512'];
        $type = $request->input('type');
        if (! in_array($type, $types)) {
            $type = 'sha256';
        }
        
        $payload = $request->input('payload');
        if (empty($payload)) {
            return $this->error(__('加密字符不能为空'));
        }
        
        $key = $request->input('key');
        if (empty($key)) {
            return $this->error(__('加密秘钥不能为空'));
        }
        
        $data = [
            'data' => hash_hmac($type, $payload, $key),
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
