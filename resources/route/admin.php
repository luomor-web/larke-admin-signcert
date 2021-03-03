<?php

use Larke\Admin\Facade\Extension;

Extension::routes(function ($router) {
    $router->namespace('Larke\\Admin\\SignCert\\Controller')
        ->group(function ($router) {
            $router->post('/sign-cert/hmac', 'Hmac@create')->name('larke-admin.sign-cert.hmac');
            $router->post('/sign-cert/rsa', 'Rsa@create')->name('larke-admin.sign-cert.rsa');
            $router->post('/sign-cert/rsa-pfx', 'RsaPfx@create')->name('larke-admin.sign-cert.rsa-pfx');
            $router->post('/sign-cert/rsa-pfx-pem', 'RsaPfx2Pem@create')->name('larke-admin.sign-cert.rsa-pfx-pem');
            $router->post('/sign-cert/ecdsa', 'Ecdsa@create')->name('larke-admin.sign-cert.ecdsa');
            $router->post('/sign-cert/eddsa', 'Eddsa@create')->name('larke-admin.sign-cert.eddsa');
            $router->get('/sign-cert/download/{code}', 'Cert@download')->name('larke-admin.sign-cert.cert-download');
        });
});