<?php

namespace SignCert;

use Illuminate\Support\Facades\Artisan;

use Larke\Admin\Extension\Rule;
use Larke\Admin\Extension\ServiceProvider;
use Larke\Admin\Frontend\Support\Menu;

class SignCertServiceProvider extends ServiceProvider
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
        
        $this->loadRoutesFrom(__DIR__ . '/resource/route/admin.php');
    }
    
    /**
     * 过滤slug
     */
    protected function exceptSlugs()
    {
        $excepts = config('larkeadmin.auth.excepts', []);
        $excepts[] = 'sign-cert.eddsa-download';
        
        config([
            'larkeadmin.auth.excepts' => $excepts,
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
                    'title' => 'hmac',
                    'url' => 'sign-cert/hmac',
                    'method' => 'POST',
                    'slug' => 'larke-admin.sign-cert.hmac',
                    'description' => 'hmac签名',
                ],
                
                [
                    'title' => 'rsa',
                    'url' => 'sign-cert/rsa',
                    'method' => 'POST',
                    'slug' => 'larke-admin.sign-cert.rsa',
                    'description' => 'rsa签名',
                ],
                
                [
                    'title' => 'ecdsa',
                    'url' => 'sign-cert/ecdsa',
                    'method' => 'POST',
                    'slug' => 'larke-admin.sign-cert.ecdsa',
                    'description' => 'ecdsa签名',
                ],
                
                [
                    'title' => 'eddsa',
                    'url' => 'sign-cert/eddsa',
                    'method' => 'POST',
                    'slug' => 'larke-admin.sign-cert.eddsa',
                    'description' => 'rsa签名',
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
