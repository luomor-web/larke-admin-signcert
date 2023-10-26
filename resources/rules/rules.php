<?php

return [
    'title' => '签名证书',
    'url' => '#',
    'method' => 'OPTIONS',
    'slug' => $slug,
    'description' => '用于签名所需要的证书生成',
    'children' => [
        [
            'title' => 'hmac证书',
            'url' => 'sign-cert/hmac',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.hmac',
            'description' => 'hmac证书',
        ],
        [
            'title' => 'dsa证书',
            'url' => 'sign-cert/dsa',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.dsa',
            'description' => 'dsa证书',
        ],
        
        [
            'title' => 'rsa证书',
            'url' => 'sign-cert/rsa',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.rsa',
            'description' => 'rsa证书',
        ],
        
        [
            'title' => 'rsa-pfx证书',
            'url' => 'sign-cert/rsa-pfx',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.rsa-pfx',
            'description' => 'rsa-pfx格式证书',
        ],
        
        [
            'title' => 'pfx转pem证书',
            'url' => 'sign-cert/rsa-pfx-pem',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.rsa-pfx-pem',
            'description' => 'RsaPfx格式转pem格式证书',
        ],
        
        [
            'title' => 'ecdsa证书',
            'url' => 'sign-cert/ecdsa',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.ecdsa',
            'description' => 'ecdsa证书',
        ],
        
        [
            'title' => 'eddsa证书',
            'url' => 'sign-cert/eddsa',
            'method' => 'POST',
            'slug' => 'larke-admin.sign-cert.eddsa',
            'description' => 'rsa证书',
        ],
    ],
];
