<?php

use Larke\Admin\Facade\Extension;

Extension::routes(function ($router) {
    $router->namespace('\\SignCert\\Controller')->group(function ($router) {
        $router->post('/sign-cert/hmac', 'Hmac@create')->name('sign-cert.hmac');
        $router->post('/sign-cert/rsa', 'Rsa@create')->name('sign-cert.rsa');
        $router->post('/sign-cert/ecdsa', 'Ecdsa@create')->name('sign-cert.ecdsa');
        $router->post('/sign-cert/eddsa', 'Eddsa@create')->name('sign-cert.eddsa');
        $router->get('/sign-cert/eddsa/{code}', 'Eddsa@download')->name('sign-cert.eddsa-download');
    });
});