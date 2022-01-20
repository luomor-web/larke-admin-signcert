<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert;

use Illuminate\Support\Facades\Artisan;

use Larke\Admin\Extension\Rule;
use Larke\Admin\Extension\ServiceProvider as BaseServiceProvider;
use Larke\Admin\Frontend\Support\Menu;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * 扩展信息
     */
    public $info = [
        'name' => 'larke/sign-cert',
        'title' => '签名证书',
        'description' => '生成RSA,EDDSA,ECDSA等非对称签名证书',
        'keywords' => [
            'rsa',
            'ecdsa',
            'eddsa',
            'SignCert',
        ],
        'homepage' => 'https://github.com/deatil/larke-admin-signcert',
        'authors' => [
            [
                'name' => 'deatil', 
                'email' => 'deatil@github.com', 
                'homepage' => 'https://github.com/deatil', 
            ],
        ],
        'version' => '1.1.0',
        'adaptation' => '1.2.*',
    ];
    
    /**
     * 扩展图标
     */
    public $icon = __DIR__ . '/../icon.png';
    
    protected $slug = 'larke-admin.extension.sign-cert';
    
    /**
     * 初始化
     */
    public function boot()
    {
        // 扩展注册
        $this->withExtension(
            $this->info['name'], 
            $this->withExtensionInfo(
                __CLASS__, 
                $this->info, 
                $this->icon
            )
        );
        
        // 事件
        $this->bootListeners();
    }
    
    /**
     * 运行中
     */
    public function start()
    {
        $this->exceptSlugs();
        
        $this->registerNamespaces();
        
        $this->loadRoutesFrom(__DIR__ . '/../resources/route/admin.php');
    }
    
    /**
     * 过滤slug
     */
    protected function exceptSlugs()
    {
        $this->withAuthenticateExcepts([
            'larke-admin.sign-cert.cert-download',
        ]);
        
        $this->withPermissionExcepts([
            'larke-admin.sign-cert.cert-download',
        ]);
    }
    
    /**
     * 注册命名空间
     */
    protected function registerNamespaces()
    {
        if (! class_exists('phpseclib\\Crypt\\RSA')) {
            $this->withNamespace([
                'phpseclib\\' => __DIR__ . '/../lib/phpseclib',
            ]);
        }
    }
    
    /**
     * 推送
     */
    protected function assetsPublishes()
    {
        $this->publishes([
            __DIR__.'/../resources/assets/sign-cert' => public_path('extension/sign-cert'),
        ], 'larke-admin-sign-cert-assets');
        
        Artisan::call('vendor:publish', [
            '--tag' => 'larke-admin-sign-cert-assets',
            '--force' => true,
        ]);
    }
    
    /**
     * 监听器
     */
    public function bootListeners()
    {
        $thiz = $this;
        
        // 安装后
        $this->onInatll(function ($name, $info) use($thiz) {
            if ($name == $thiz->info["name"]) {
                $thiz->install();
            }
        });
        
        // 卸载后
        $this->onUninstall(function ($name, $info) use($thiz) {
            if ($name == $thiz->info["name"]) {
                $thiz->uninstall();
            }
        });
        
        // 更新后
        $this->onUpgrade(function ($name, $oldInfo, $newInfo) use($thiz) {
            if ($name == $thiz->info["name"]) {
                $thiz->upgrade();
            }
        });
        
        // 启用后
        $this->onEnable(function ($name, $info) use($thiz) {
            if ($name == $thiz->info["name"]) {
                $thiz->enable();
            }
        });
        
        // 禁用后
        $this->onDisable(function ($name, $info) use($thiz) {
            if ($name == $thiz->info["name"]) {
                $thiz->disable();
            }
        });
    }
    
    /**
     * 安装后
     */
    protected function install()
    {
        $rules = [
            'title' => '签名证书',
            'url' => '#',
            'method' => 'OPTIONS',
            'slug' => $this->slug,
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
        
        // 添加权限
        Rule::create($rules);
        
        // 添加菜单
        Menu::create($rules);
        
        $this->assetsPublishes();
    }
    
    /**
     * 卸载后
     */
    protected function uninstall()
    {
        // 删除权限
        Rule::delete($this->slug);
        
        // 删除菜单
        Menu::delete($this->slug);
    }
    
    /**
     * 更新后
     */
    protected function upgrade()
    {}
    
    /**
     * 启用后
     */
    protected function enable()
    {
        // 启用权限
        Rule::enable($this->slug);
        
        // 启用菜单
        Menu::enable($this->slug);
    }
    
    /**
     * 禁用后
     */
    protected function disable()
    {
        // 禁用权限
        Rule::disable($this->slug);
        
        // 禁用菜单
        Menu::disable($this->slug);
    }
    
}
