<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use phpseclib3\Crypt\DSA as CryptDSA;

use Illuminate\Http\Request;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Dsa证书
 *
 * @create 2023-10-26
 * @author deatil
 */
class Dsa extends BaseController
{
    /**
     * Rsa创建
     *
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Dsa创建", 
        desc:   "Dsa证书创建",
        order:  1301,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        $ln = $request->input('ln', 'ln2048_224');
        $ktype = $request->input('ktype', 'pkcs8');
        $passphrase = $request->input('pass', null);
        
        $nowLn = match($ln) {
            'ln1024_160' => [1024, 160],
            'ln2048_224' => [2048, 224],
            'ln2048_256' => [2048, 256],
            'ln3072_256' => [3072, 256],
            default => [2048, 224],
        };
        
        $private = CryptDSA::createKey($nowLn[0], $nowLn[1]);
        $public = $private->getPublicKey();
        
        if (! empty($passphrase)) {
            $private = $private->withPassword($passphrase);
        }
        
        if ($ktype == 'pkcs8') {
            $type = 'PKCS8';
            $option = [
                'encryptionAlgorithm' => 'id-PBES2', // id-PBES2 或者 PBES1 类型 或者 PKCS1 类型
                'encryptionScheme' => 'aes256-CBC-PAD',
            ];
        } else {
            $type = 'PKCS1';
            $option = [
                'encryptionAlgorithm' => 'AES-256-CBC',
            ];
        }
        
        // PKCS1:
        // AES-(128|192|256)-(CBC|ECB|CFB|OFB|CTR)
        // DES-EDE3-(CBC|ECB|CFB|OFB|CTR)
        // DES-(CBC|ECB|CFB|OFB|CTR)
        // 
        // PKCS8-PBES1:
        // DES | RC2 | 3-KeyTripleDES | 2-KeyTripleDES 
        // 128BitRC2 | 40BitRC2 | 128BitRC4 | 40BitRC4
        // 
        // PKCS8-PBES2:
        // desCBC | des-EDE3-CBC | rc2CBC
        // aes128-CBC-PAD | aes192-CBC-PAD | aes256-CBC-PAD
        $data = [
            'private_key' => $private->toString($type, $option),
            'public_key'  => $public->toString($type),
        ];
        
        return $this->success(__('创建成功'), $data);
    }
    
}
