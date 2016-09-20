## Winshop开发文档

### 快速开始
用于快速测试winshop3，系统自带派大叔项目的测试数据，可直接配置数据进行测试。
1. 执行根目录下的winshop3.sql；
2. 修改`/Application/Common/Conf/config.php`第51行的数据库配置信息：  

    'DB_TYPE'   => 'mysql', //数据库类型  
    'DB_HOST'   => '127.0.0.1', //服务器地址  
    'DB_NAME'   => 'winshop3', //数据库名  
    'DB_USER'   => 'root', //用户名  
    'DB_PWD'    => '123456',  //密码  
    'DB_PORT'   => '3306', //端口  
    'DB_PREFIX' => 'winshop_', //数据库表前缀，测试时请勿修改

3. 下载系统附件包[Uploads.zip](http://shop.winhu.com/Uploads.zip)，放于根目录下；
4. 修改系统根目录下的`Runtime`，`Uploads`，`Data`目录权限为777；
5. 默认自带派大叔商品数据，后台管理账号密码：

    账号：winshop  
    密码：123456  

注意：测试前请先在“后台 > 系统 > 系统设置 > 基本设置”中的公众号设置选项卡中，配置你的微信公众号信息。

### 安装说明
安装环境：PHP >= 5.3，Mysql >= 5.5。  
根据安装程序即可完成安装，我们内置了一个开发者账号。  

    账号：winshop
    密码：123456

可以在`/Application/Install/Common/function.php`第214行的`register_root()`方法中进行修改，开发者账号拥有系统的所有权限。

### 后台使用说明
后台使用请参照doc目录下的[《Winshop后台使用指南》](./Winshop后台使用指南.doc)。

### 开发说明
* 项目基于[OneThink1.1](http://onethink.cn/)二次开发，更多信息请参照[OT开发文档](http://document.onethink.cn/manual_1_0.html)。
* doc目录为文档说明目录，线上部署请删除。

### 前端构建说明
1. PC前端基于[Bootstrap3.3](https://github.com/twbs/bootstrap)二次开发，移动前端基于[Ratchet](https://github.com/twbs/ratchet)二次开发。
2. 前端CSS采用sass编写，所有的sass源码在/Public/src目录下。
3. 前端构建采用grunt，请先安装node；  
4. 运行`npm install`安装grunt构建工具；  
5. 运行`grunt <task name>`构建对应项目，说明：
    
    grunt dist-css //构建所有CSS模块  
    grunt dist-install //构建系统安装模块  
    grunt dist-default //构建默认模板PC版  
    grunt dist-default-mobile //构建默认模板手机版  
    grunt dist-paidashu //构建派大叔模板PC版  
    grunt dist-paidashu-mobile //构建派大叔模板手机版  

### 后端文档
* [Winshop项目结构](winshop-file-structure.html)
* [后端开发中的编码风格约定](back-end-code-style.html)
* [后端开发中的命名约定](back-end-name-style.html)
* [数据字典说明](dbdict.html)
* [伪静态说明](rewrite.html)

### 前端文档
* [前端构建说明](front-end-grunt.html)
* [Ajax请求编码风格约定](ajax-style.html)
* [seajs模块定义说明](sea-module.html)
* [UI示例文档](/Demo) （需要系统安装成功后才能访问）

### 编码规范
* [PHP编码规范](php-style-guide.html)
* [CSS编码规范](css-style-guide.html)
* [JavaScript编码规范](js-style-guide.html)


<!--
markdown-html md/winshop-file-structure.md -o winshop-file-structure.html && 
markdown-html md/back-end-code-style.md -o back-end-code-style.html && 
markdown-html md/back-end-name-style.md -o back-end-name-style.html && 
markdown-html md/rewrite.md -o rewrite.html && 
markdown-html md/front-end-grunt.md -o front-end-grunt.html && 
markdown-html md/ajax-style.md -o ajax-style.html && 
markdown-html md/sea-module.md -o sea-module.html && 
markdown-html md/php-style-guide.md -o php-style-guide.html && 
markdown-html md/css-style-guide.md -o css-style-guide.html && 
markdown-html md/js-style-guide.md -o js-style-guide.html && 
markdown-html md/index.md -o index.html
-->