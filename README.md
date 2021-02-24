## larke-admin 签名证书生成扩展


### 项目介绍

*  签名证书生成扩展
*  签名证书包括：RSA, ECDSA, EDDSA


### 环境要求

 - PHP >= 7.3.0
 - Laravel >= 8.0.0
 - larke-admin >= 1.0.8


### 截图预览

![Ecdsa](https://user-images.githubusercontent.com/24578855/109032140-57b33600-7700-11eb-9735-2910e87abfb5.png)
![Eddsa](https://user-images.githubusercontent.com/24578855/109032147-597cf980-7700-11eb-8ba4-8065d09187e2.png)
![Hmac](https://user-images.githubusercontent.com/24578855/109032151-5a159000-7700-11eb-844d-3a21cd41780a.png)
![Rsa](https://user-images.githubusercontent.com/24578855/109032156-5aae2680-7700-11eb-8b87-36c43e87dbf2.png)
![RsaPfx](https://user-images.githubusercontent.com/24578855/109032160-5b46bd00-7700-11eb-8a40-b169a01d1b9c.png)
![RsaPfx2pem](https://user-images.githubusercontent.com/24578855/109032162-5bdf5380-7700-11eb-9103-f64a5fac8e55.png)


更多截图 
[larke-admin-signcert 截图](https://github.com/deatil/larke-admin-signcert/issues/1)


### 安装步骤

1、下载安装扩展

```php
composer require larke/sign-cert
```

或者在`本地扩展->扩展管理->上传扩展` 本地上传

2、然后在 `本地扩展->扩展管理->安装/更新` 安装本扩展

3、安装后可以在 `public/extension/sign-cert` 发现本扩展的前端文件

4、将 `sign-cert` 该文件夹复制到前端编译目录 `src/extension` 下进行编译预览

5、你可以在 `src/routes.js` 文件修改扩展在左侧菜单的排序


### 开源协议

*  本扩展 遵循 `Apache2` 开源协议发布，在保留本扩展版权的情况下提供个人及商业免费使用。 


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
