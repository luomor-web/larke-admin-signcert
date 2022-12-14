## larke-admin 签名证书生成扩展


### 项目介绍

*  签名证书生成扩展
*  签名证书包括：RSA, ECDSA, EDDSA


### 环境要求

 - PHP >= 7.3.0
 - Laravel >= 8.0.0
 - larke-admin >= 1.0.8


### 截图预览

![Hmac](https://user-images.githubusercontent.com/24578855/109032151-5a159000-7700-11eb-844d-3a21cd41780a.png)
![Rsa](https://user-images.githubusercontent.com/24578855/207554542-fb8a99b7-8ac0-4746-ac1c-01e468f34947.png)
![RsaPfx](https://user-images.githubusercontent.com/24578855/207554714-94aeb7a6-5503-473b-95d4-06f8daada49e.png)
![RsaPfx2pem](https://user-images.githubusercontent.com/24578855/207554757-fc64c4f5-b23d-46fe-9cd3-7201c17a4255.png)
![Ecdsa](https://user-images.githubusercontent.com/24578855/207554834-52b65f88-e403-4af7-a2d0-1f2629998c14.png)
![Eddsa](https://user-images.githubusercontent.com/24578855/207554897-491fbd78-94f5-4de9-9df1-e33f07e962a3.png)


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
