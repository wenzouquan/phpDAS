# phpDAS

php 分布式微服务架构

php7  (开发环境是在php7 , php7以下版本没有测试)

Swoole （C语言写的网络通信框架 ）

thrift（多语言服务接口定义）

consul（分布式服务发现与管理）

Phalcon（C语言写的php mvc框架）

composer(php 依赖管理)

LVS+Keepalive （分布式）





# 安装composer 

http://www.phpcomposer.com/




# 安装php扩展 Swoole

http://www.swoole.com/




# 安装php扩展 Phalcon 

http://phalcon.ipanta.com/1.3/tutorial.html#checking-your-installation








# 开始使用phpDAS



一、 下载代码 ： git clone https://github.com/wenzouquan/phpDAS.git phpDAS




二、 运行php写的服务端 ： 

1、cd phpDAS/phpDAS_service 

2、安装依赖 composer install

3、启动运行微服务： php server.php 





三、php客户调用示例代码

1、 cd phpDAS/client_php 

2、安装依赖 composer install

3、php client.php




四、java 客户端调用示例代码

1、将 client_java 里的 thrift-client-demo ，thrift-client-demo-api  maven项目导入到编辑器中，其中thrift-client-demo是依赖thrift-client-demo-api的



2、分别对 thrift-client-demo ，thrift-client-demo-api ， maven update project 下载依赖 ， 然后 maven install 



3、然后运行 thrift-client-demo的HelloClientDemo.java 示例代码 






# thrift 使用

首先需要安装thrift ： http://thrift.apache.org/



安装好之后，就可以使用thrift命令生成接口代码了



这里我们写了一个示例，可进入phpDAS test.thrift 这是接口定义文件



在phpDAS目录下，执行 thrift -gen java test.thrift 就会生成java服务接口代码



因为我们使用php来实现服务接口 所以要加上:server 就可以生成php服务接口代码了 thrift -gen php:server test.thrift  




# consul服务安装搭建与管理


# swoole服务器进程守护无人值守


# 分布式 LVS+Keepalive 


# 微服务开发与调式方法


 上面我们提到用php server.php  来启动微服务，这种方式使用基于Swoole启动的tpc协议服务，代码运行一次之后就常驻内存了，所以我们的改了代码，需要重启或热替换。这样对我们开发调试有点不方便。



 因此我们提供了一个传统的http web服务来调试 ， 我们可以使用apache 或其它运行这个项目
 


 将所有请求重写到dev.php文件， 使用apache的朋友 可以这样配置 .htaccess 



<IfModule mod_rewrite.c>
  Options +FollowSymlinks
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ dev.php/$1 [QSA,PT,L]
</IfModule>




配置好web服务器试，我们就可以使用调式普通网站一样了



路由：http://pdas.com/HiService/say/?name=12  ， 这样就调用 HiService 服务的say方法。 参数name



我们这样就可以使用postman来测试服务了



也可以在编辑器上打断点了，debug 配置相关文档如下：ttp://www.cnblogs.com/derrck/p/5195946.html





# 性能测试













