<?php

namespace SignCert;

use Illuminate\Support\Facades\Artisan;

use Larke\Admin\Facade\Extension;
use Larke\Admin\Extension\Rule;
use Larke\Admin\Extension\ServiceProvider as BaseServiceProvider;
use Larke\Admin\Frontend\Support\Menu;

class ServiceProvider extends BaseServiceProvider
{
    public $info = [
        'name' => 'SignCert',
        'title' => '签名证书',
        'introduce' => '用于签名所需要的证书生成',
        'author' => 'deatil', 
        'authorsite' => 'http://github.com/deatil',
        'authoremail' => 'deatil@github.com',
        'version' => '1.0.0',
        'adaptation' => '1.0.*',
        'require_extension' => [],
    ];
    
    /**
     * 运行中
     */
    public function start()
    {
        $this->exceptSlugs();
        
        $this->registerNamespaces();
        
        $this->loadRoutesFrom(__DIR__ . '/resource/route/admin.php');
    }
    
    /**
     * 过滤slug
     */
    protected function exceptSlugs()
    {
        $excepts = config('larkeadmin.auth.excepts', []);
        $excepts[] = 'sign-cert.cert-download';
        
        config([
            'larkeadmin.auth.excepts' => $excepts,
        ]);
    }
    
    /**
     * 注册命名空间
     */
    protected function registerNamespaces()
    {
        Extension::namespaces([
            'phpseclib\\' => __DIR__ . '/lib/phpseclib',
        ]);
    }
    
    /**
     * 推送
     */
    protected function assetsPublishes()
    {
        $this->publishes([
            __DIR__.'/resource/assets/sign-cert' => public_path('extension/sign-cert'),
        ], 'larke-admin-sign-cert-assets');
        
        Artisan::call('vendor:publish', [
            '--tag' => 'larke-admin-sign-cert-assets',
        ]);
    }
    
    /**
     * 安装后
     */
    public function install()
    {
        $rules = [
            'title' => '证书签名',
            'url' => '#',
            'method' => 'OPTIONS',
            'slug' => md5('larke-admin.sign-cert.extension'),
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
        
        // 添加权限
        Rule::create($rules);
        
        // 添加菜单
        Menu::create($rules);
        
        $this->assetsPublishes();
    }
    
    /**
     * 卸载后
     */
    public function uninstall()
    {
        $slug = md5('larke-admin.sign-cert.extension');
        
        // 删除权限
        Rule::delete($slug);
        
        // 删除菜单
        Menu::delete($slug);
    }
    
    /**
     * 更新后
     */
    public function upgrade()
    {}
    
    /**
     * 启用后
     */
    public function enable()
    {
        $slug = md5('larke-admin.sign-cert.extension');
        
        // 启用权限
        Rule::enable($slug);
        
        // 启用菜单
        Menu::enable($slug);
    }
    
    /**
     * 禁用后
     */
    public function disable()
    {
        $slug = md5('larke-admin.sign-cert.extension');
        
        // 禁用权限
        Rule::disable($slug);
        
        // 禁用菜单
        Menu::disable($slug);
    }
    
}
