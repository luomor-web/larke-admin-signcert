<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use phpseclib\Crypt\RSA as CryptRSA;

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
        $ktype = $request->input('ktype', 'pkcs8');
        if ($ktype == 'pkcs8') {
            $data = $this->pkcs8($request);
        } else {
            $data = $this->pkcs1($request);
        }
        
        return $this->success(__('创建成功'), $data);
    }
    
    /**
     * Rsa创建 pkcs1格式
     *
     * @param  Request  $request
     * @return Response
     */
    protected function pkcs1(Request $request)
    {
        // $lens = ['384', '512', '1024', '2048', '4096'];
        $lens = ['512', '1024', '2048', '4096'];
        $len = $request->input('len');
        if (! in_array($len, $lens)) {
            $len = '2048';
        }
        
        $passphrase = $request->input('pass', null);
        
        $rsa = new CryptRSA();
        $rsa->setEncryptionMode(CryptRSA::ENCRYPTION_PKCS1);
        $keys = $rsa->createKey((int) $len);
        
        $privkey = $keys['privatekey']; // 私钥
        $pubkey = $keys['publickey']; // 公钥
        
        $rsa->loadKey($privkey);
        if (! empty($passphrase)) {
            $rsa->setPassword($passphrase);
        }
        
        $newPrivkey = $rsa->getPrivateKey(CryptRSA::PRIVATE_FORMAT_PKCS1);
        $newPubkey = $rsa->getPublicKey(CryptRSA::PUBLIC_FORMAT_PKCS1);
        
        if ($newPrivkey === false) {
            $newPrivkey = '';
        }
        if ($newPubkey === false) {
            $newPubkey = '';
        }
        
        $data = [
            'private_key' => $newPrivkey,
            'public_key' => $newPubkey,
        ];
        
        return $data;
    }
    
    /**
     * Rsa创建 pkcs8格式
     *
     * @param  Request  $request
     * @return Response
     */
    protected function pkcs8(Request $request)
    {
        // $lens = ['384', '512', '1024', '2048', '4096'];
        $lens = ['512', '1024', '2048', '4096'];
        $len = $request->input('len');
        if (! in_array($len, $lens)) {
            $len = '2048';
        }
        
        $passphrase = $request->input('pass', null);
        
        $rsa = new CryptRSA();
        $rsa->setEncryptionMode(CryptRSA::ENCRYPTION_PKCS1);
        $keys = $rsa->createKey((int) $len);
        
        $privkey = $keys['privatekey']; // 私钥
        $pubkey = $keys['publickey']; // 公钥
        
        $rsa->loadKey($privkey);
        if (! empty($passphrase)) {
            $rsa->setPassword($passphrase);
        }
        
        $newPrivkey = $rsa->getPrivateKey(CryptRSA::PRIVATE_FORMAT_PKCS8);
        $newPubkey = $rsa->getPublicKey(CryptRSA::PUBLIC_FORMAT_PKCS8);
        
        if ($newPrivkey === false) {
            $newPrivkey = '';
        }
        if ($newPubkey === false) {
            $newPubkey = '';
        }
        
        $data = [
            'private_key' => $newPrivkey,
            'public_key' => $newPubkey,
        ];
        
        return $data;
    }
}
