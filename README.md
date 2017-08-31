# phpDAS 特性


1、php7  (开发环境是在php7 , php5.6)

建议使用php7 , 经测试运行速度是php5.6, 8倍左右



2、Swoole （C语言写的网络通信框架 ）

使用Swoole 实现异步多线程服务器 , 异步任务 , 异步IO


3、thrift（多语言RPC服务接口定义）

基于thrift 编写RPC服务 , 实现多语言调用服务,如:java , php , .net 等等


4、consul（分布式服务发现与管理）

使用consul 实现分布式中的服务管理, 服务健康检测,服务监控等


5、Phalcon（C语言写的php mvc框架）

使用Phalcon 实现mvc框架 , 它是C语言写的php扩展, 服务注入方式


6、composer(php 依赖管理)

框架使用到的类都提交到composer了 , 可以按需要安装依赖, 由我们来维护代码, 你可以更专注于业务代码



7、阿里云的日志服务logger

提供日志采集和强大日志接口、日志自定义索引搜索 , 还有日志上下文查询锁定问题更简单,更有报警等功能



8、阿里云的MQ消息服务

简直就是一万能的变压器,支持上亿消息产生 , 然后我们只在平滑消费消息就可以了,不会因为高并发而把服务挂掉! 好评!


9 、 分布式redis

架构里必须安装redis ,  服务发现是在redis里找服务的 , 这样支持高并发  ,  consul 会监听服务是健康而更新redis里的服务


10、多线程调用

使用Swoole实现多线程调用 ,

$content = $this->getDi()->getAsyncClient()->setClassName("\\app\\services\\HiService")->callBack(function ($params) {
                              var_dump($params);
                               })


->errorCallback(function($error) {
                                  // var_dump($this->say3("error"));
                              })
                              

->say3('wen');






# 安装composer 

http://www.phpcomposer.com/




# 安装php扩展 Swoole

http://www.swoole.com/




# 安装php扩展 Phalcon 

http://phalcon.ipanta.com/1.3/tutorial.html#checking-your-installation




# phpDAS手册




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

说明,java示例中没有加服务发现代码 , 代码参考以下,从redis中取一个服务名字,java中实现就不再写了

  $services=$this->redis->sMembers($serviceName);

  $key = array_rand($services, 1); //随机找到一个服务

  $service = explode("@",$services[$key]);



其它语言调用服务,请参与thrift客户端代码(官网下载的安装包里有各种语言代码),注意客户端传输协议需要与服务端一致。改下代码希望有助于理解

transport = new TSocket(SERVER_IP, SERVER_PORT, TIMEOUT);

TProtocol protocol = new TBinaryProtocol(new TFramedTransport(transport));

//Services\\Demo\\HiService 为服务名称
TMultiplexedProtocol tMultiplexedProtocol = new TMultiplexedProtocol(protocol, "Services\\Demo\\HiService");

transport.open();

HiService.Client client = new HiService.Client(tMultiplexedProtocol);

String result = client.say(userName);






# thrift 使用

首先需要安装thrift ： http://thrift.apache.org/



安装好之后，就可以使用thrift命令生成接口代码了



这里我们写了一个示例，可进入phpDAS test.thrift 这是接口定义文件



在phpDAS目录下，执行 thrift -gen java test.thrift 就会生成java服务接口代码



因为我们使用php来实现服务接口 所以要加上:server 就可以生成php服务接口代码了 thrift -gen php:server test.thrift  





# consul服务安装搭建与管理

https://www.consul.io/  进入官网 下载安装 consul


通常我们在本地测试使用: consul agent -dev -config-dir=/Volumes/UNTITLED/www/phpDAS/consul/config



线上consul配置参考:

http://tonybai.com/2015/07/06/implement-distributed-services-registery-and-discovery-by-consul/


在这里架构里服务发现是存在redis里,所以使用前请先配置redis


线上需要修改这里代码 redis配置 :phpDAS_service/bin/AddServerToRedis.php



这样consul监听到服务添加或减少都会调用phpDAS_service/bin/AddServerToRedis.php 这个脚本 ,从而达到服务同步到redis






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



也可以在编辑器上打断点了，debug 配置相关文档如下：http://www.cnblogs.com/derrck/p/5195946.html




# swoole服务器进程守护

打开phpDAS_service/Swoole/check_server.sh 文件 , 修改把端口(8091)改成自己服务的端口,目录也改成server.php的目录

count=`ps -fe |lsof -i :8091| wc -l`

echo $count

if [ $count -lt 1 ]; then

ps -eaf |grep "server.php" | grep -v "grep"| awk '{print $2}'|xargs kill -9

sleep 2

ulimit -c unlimited

php  /Volumes/UNTITLED/www/phpDAS/phpDAS_service/server.php

echo "restart";

echo $(date +%Y-%m-%d_%H:%M:%S) >/Volumes/UNTITLED/www/phpDAS/phpDAS_service/log/restart.log

fi

使用 crontab 监控 check_server.sh 文件 ,一分钟检查一次服务

crontab -e //添加任务

*/1 * * * * /Volumes/UNTITLED/www/phpDAS/phpDAS_service/bin/check_server.sh

crontab -l //查看任务列表










# 性能测试

php client.php

1、调用100次发现服务并使用tcp协议执行服务

[发现服务100次，页面执行时间：0.092694044113159 秒]



2、调用100次发现,并curl使用http协议执行服务

[发现服务100次，页面执行时间：3.4024829864502 秒]


//定义服务接口
$servicesApi = array(
	'Services\\Demo' => dirname(dirname(__FILE__)) . '/phpDAS_api/gen-php', //Services的目录
);
//实例化redis

$redis =  new \Redis();

$redis->connect("127.0.0.1", "6379");

//实例化客户端

$client = new \phpkit\thriftrpc\Client($servicesApi);

$client->setRedis($redis);//注入redis 对象

$client->setHttpDedug(1);//使用http调试

//$client->setXdebugSession(14462);//调试XdebugSession

//实例化服务

$stime = microtime(true);

$count =100;

for($i=0;$i<$count;$i++){

    //发现服务

    $hiService = $client->getRPCService("Services\\Demo\\HiService");

    //服务调用

    $hiService->say("wen");

}

$etime = microtime(true);

$total = $etime - $stime;

echo "<br />[发现服务{$count}次，页面执行时间：{$total} ]秒";













