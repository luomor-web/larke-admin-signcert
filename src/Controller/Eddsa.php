<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use phpseclib3\Crypt\EC;

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
        $passphrase = $request->input('pass', null);

        $private = EC::createKey('ed25519');
        $public = $private->getPublicKey();
        
        if (! empty($passphrase)) {
            $private = $private->withPassword($passphrase);
        }
        
        $data = [
            'private_key' => $private->toString('PKCS8'),
            'public_key'  => $public->toString('PKCS8'),
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
