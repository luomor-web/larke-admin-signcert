<?php

use Larke\Admin\Facade\Extension;

if (! class_exists("Larke\\Admin\\SignCert\\ServiceProvider")) {
    Extension::namespaces([
        'Larke\\Admin\\SignCert\\' => __DIR__ . '/src',
    ]);
}

Extension::extend('larke/sign-cert', Larke\Admin\SignCert\ServiceProvider::class);
