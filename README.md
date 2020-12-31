## larke-admin 签名证书生成扩展


### 项目介绍

*  签名证书生成扩展
*  签名证书包括：RSA, ECDSA, EDDSA


### 环境要求

 - PHP >= 7.3.0
 - Laravel >= 8.0.0
 - larke-admin >= 1.0.8


### 截图预览

![Hmac](https://user-images.githubusercontent.com/24578855/103189088-0d7f2580-4906-11eb-8992-a9713c4714e7.png)

![Rsa](https://user-images.githubusercontent.com/24578855/103189089-0e17bc00-4906-11eb-8be3-c122dbf7e5bc.png)

![Ecdsa](https://user-images.githubusercontent.com/24578855/103189081-09530800-4906-11eb-9d20-29bafec2430f.png)

![Eddsa](https://user-images.githubusercontent.com/24578855/103189085-0c4df880-4906-11eb-949c-0689818bb651.png)


更多截图 
[larke-admin-signcert 截图](https://github.com/deatil/larke-admin-signcert/issues/1)


### 安装步骤

1、首先安装了 `larke-admin` 后台管理

2、再下载扩展

```php
composer require larke/sign-cert
```

或者本地上传，上传请将压缩包目录改为 `larke/sign-cert` 后压缩

3、然后在 `本地扩展->扩展管理` 安装本扩展

安装后可以在 `public/extension/sign-cert` 发现本扩展的前端文件

4、将 `sign-cert` 该文件夹复制到前端编译目录 `src/extension` 下进行编译预览

5、你可以在 `src/routes.js` 文件修改扩展在左侧菜单的排序


### 开源协议

*  本扩展 遵循 `Apache2` 开源协议发布，在保留本扩展版权的情况下提供个人及商业免费使用。 


### 版权

*  该系统所属版权归 deatil(https://github.com/deatil) 所有。
