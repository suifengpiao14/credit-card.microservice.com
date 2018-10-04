nginx 虚拟域名配置
=======
开发微服务时项目比较多，每个项目需要配置开发、测试、预发布、正式等环境比较繁琐。该脚本能自动化配置nginx虚拟域名conf文件

安装方法
-----
1. 获取源码
```bash
composer require --dev suifengpiao14/nginx-config:dev-master
```
2.修改composer.json 文件，增加执行脚本
```json
"post-install-cmd": [
    "sh ./vendor/suifengpiao14/nginx-config/src/setup.sh"
],
"post-update-cmd": [
    "sh ./vendor/suifengpiao14/nginx-config/src/setup.sh"
]
```
3. 执行安装命令
```bash
composer install
```


使用方法
---------
安装后项目跟目录会新增domain.conf、conf_nginx.sh 两个文件。
1. 修改domain修改domain.conf 名称为域名（假设域名为abc.com 则改名为abc.com.conf）
2. 执行bash ./conf_nginx.sh 即可

注意事项
--------
 1. domain.conf中连接php cgi 默认使用 php7backend，可以根据自己的实际情况修改
 2. 配置文件中支持二级域名泛解析，方便通过增加dev、test、pre等前缀区分环境如（dev.abc.com,test.abc.com,pre.abc.com）
