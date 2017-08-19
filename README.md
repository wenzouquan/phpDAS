# phpDAS

php 分布式微服务架构

php7  (开发环境是在php7 , php7以下版本没有测试)

Swoole （C语言写的网络通信框架 ）

thrift（多语言服务接口定义）

consul（分布服务发现与管理）

Phalcon（C语言写的php mvc框架）

composer(php 依赖管理)




# 安装composer 

http://www.phpcomposer.com/




# 安装php扩展 Swoole

http://www.swoole.com/




# 安装php扩展 Phalcon 

http://phalcon.ipanta.com/1.3/tutorial.html#checking-your-installation




#开始使用phpDAS (php Distributed Application Service) 分布式微服务架构



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



#thrift 使用


#consul服务安装搭建与管理


#swoole服务器进程守护无人值守


#分布式 LVS+Keepalive 


# 微服务开发与调式方法


# 性能测试


# phpDAS 与 EDAS 比较










