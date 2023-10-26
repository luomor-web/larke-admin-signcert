<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use phpseclib3\Crypt\RSA as CryptRSA;

use Illuminate\Http\Request;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Rsa证书
 *
 * @create 2020-12-25
 * @author deatil
 */
class Rsa extends BaseController
{
    /**
     * Rsa创建
     *
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Rsa创建", 
        desc:   "Rsa证书创建",
        order:  1301,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        $len = $request->input('len', '2048');
        $ktype = $request->input('ktype', 'pkcs8');
        $passphrase = $request->input('pass', null);

        $len = match($len) {
            '384', '512', '1024', '2048', '4096' => (int) $len,
            default => 2048,
        };
        
        $private = CryptRSA::createKey($len);
        $public = $private->getPublicKey();
        
        if (! empty($passphrase)) {
            $private = $private->withPassword($passphrase);
        }

        $type = match($ktype) {
            'pkcs8' => 'PKCS8',
            default => 'PKCS1',
        };
        
        $data = [
            'private_key' => $private->toString($type),
            'public_key'  => $public->toString($type),
        ];

        return $this->success(__('创建成功'), $data);
    }
    
}
