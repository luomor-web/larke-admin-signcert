## larke-admin 签名证书生成扩展


### 项目介绍

*  签名证书生成扩展
*  签名证书包括：RSA, ECDSA, EDDSA


### 环境要求

 - PHP >= 7.3.0
 - Laravel >= 8.0.0
 - larke-admin >= 1.0.8


### 截图预览

![Ecdsa](https://user-images.githubusercontent.com/24578855/103175887-82bc0d80-48a8-11eb-949c-bf4339cdfea4.png)

![Eddsa](https://user-images.githubusercontent.com/24578855/103175889-851e6780-48a8-11eb-99bb-b4b822833ec7.png)

![Hmac](https://user-images.githubusercontent.com/24578855/103175890-85b6fe00-48a8-11eb-8506-ce9180315155.png)

![Rsa](https://user-images.githubusercontent.com/24578855/103175891-864f9480-48a8-11eb-9a63-b621e6277c51.png)


更多截图 
[larke-admin-signcert 截图](https://github.com/deatil/larke-admin-signcert/issues/1)


### 安装步骤

首先安装了 `larke-admin` 后台管理

再下载扩展

```php
composer require lake/larke-admin-signcert
```

或者本地上传，上传请将压缩包名改为 `SignCert` 

然后在 `后台->扩展` 安装本扩展

安装后可以在 `public/extension/sign-cert` 发现本扩展的前端文件

将 `sign-cert` 该文件夹复制到前端编译目录 `src/extension` 下进行编译预览

你可以在 `src/routes.js` 文件修改扩展在右侧菜单的排序


### 开源协议

*  本扩展 遵循 `Apache2` 开源协议发布，在保留本扩展版权的情况下提供个人及商业免费使用。 


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
