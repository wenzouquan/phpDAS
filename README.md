# phpDMS

php 分布式微服务架构， 基于 Swoole 、thrift、consul、Phalcon、composer


# 安装composer 

http://www.phpcomposer.com/


# 安装php扩展 Swoole

http://www.swoole.com/


# 安装php扩展 Phalcon 

http://phalcon.ipanta.com/1.3/tutorial.html#checking-your-installation


#开始使用phpDAS (php Distributed Application Service) 分布式微服务架构

一、 下载代码 ： git clone https://github.com/wenzouquan/phpDAS.git phpDAS


二、 运行服务 ： 

1、cd phpDAS/phpDAS_service 

2、安装依赖 composer install

3、运行服务： php server.php 



三、服务调用示例代码

1、 cd phpDAS/client_php 

2、安装依赖 composer install

3、php client.php


四、微服务目录结构
├── Swoole
│   ├── Server.php
│   └── Socket.php
├── app
│   ├── models
│   │   └── Users.php
│   └── services
│       └── HiService.php
├── composer.json
├── composer.lock
├── config
│   ├── database.php
│   ├── server.php
│   ├── setting.php
│   └── web3.json
├── log
│   └── swoole.log
├── server.php
├── sql
│   └── users.sql
└── vendor
    ├── autoload.php
    ├── bin
    ├── composer
    │   ├── ClassLoader.php
    │   ├── LICENSE
    │   ├── autoload_classmap.php
    │   ├── autoload_files.php
    │   ├── autoload_namespaces.php
    │   ├── autoload_psr4.php
    │   ├── autoload_real.php
    │   ├── autoload_static.php
    │   └── installed.json
    └── phpkit
        ├── base
        │   ├── AdminController.php
        │   ├── BaseController.php
        │   ├── BaseModel.php
        │   ├── README.md
        │   └── composer.json
        ├── config
        │   ├── README.md
        │   ├── composer.json
        │   └── src
        │       ├── Config.php
        │       ├── dict.php
        │       └── views
        │           └── set-cache.phtml
        ├── core
        │   ├── README.md
        │   ├── composer.json
        │   └── src
        │       ├── Phpkit.php
        │       ├── apc.php
        │       └── functions.php
        ├── redis
        │   ├── README.md
        │   ├── composer.json
        │   └── src
        │       └── Redis.php
        └── thriftrpc
            ├── Client.php
            ├── README.md
            ├── Thrift
            │   ├── Base
            │   │   └── TBase.php
            │   ├── ClassLoader
            │   │   └── ThriftClassLoader.php
            │   ├── Exception
            │   │   ├── TApplicationException.php
            │   │   ├── TException.php
            │   │   ├── TProtocolException.php
            │   │   └── TTransportException.php
            │   ├── Factory
            │   │   ├── TBinaryProtocolFactory.php
            │   │   ├── TCompactProtocolFactory.php
            │   │   ├── TJSONProtocolFactory.php
            │   │   ├── TProtocolFactory.php
            │   │   ├── TStringFuncFactory.php
            │   │   └── TTransportFactory.php
            │   ├── Protocol
            │   │   ├── JSON
            │   │   │   ├── BaseContext.php
            │   │   │   ├── ListContext.php
            │   │   │   ├── LookaheadReader.php
            │   │   │   └── PairContext.php
            │   │   ├── TBinaryProtocol.php
            │   │   ├── TBinaryProtocolAccelerated.php
            │   │   ├── TCompactProtocol.php
            │   │   ├── TJSONProtocol.php
            │   │   ├── TMultiplexedProtocol.php
            │   │   ├── TProtocol.php
            │   │   └── TProtocolDecorator.php
            │   ├── Serializer
            │   │   └── TBinarySerializer.php
            │   ├── Server
            │   │   ├── TForkingServer.php
            │   │   ├── TServer.php
            │   │   ├── TServerSocket.php
            │   │   ├── TServerTransport.php
            │   │   └── TSimpleServer.php
            │   ├── StringFunc
            │   │   ├── Core.php
            │   │   ├── Mbstring.php
            │   │   └── TStringFunc.php
            │   ├── TMultiplexedProcessor.php
            │   ├── Transport
            │   │   ├── TBufferedTransport.php
            │   │   ├── TCurlClient.php
            │   │   ├── TFramedTransport.php
            │   │   ├── THttpClient.php
            │   │   ├── TMemoryBuffer.php
            │   │   ├── TNullTransport.php
            │   │   ├── TPhpStream.php
            │   │   ├── TSocket.php
            │   │   ├── TSocketPool.php
            │   │   └── TTransport.php
            │   └── Type
            │       ├── TConstant.php
            │       ├── TMessageType.php
            │       └── TType.php
            ├── apc.php
            └── composer.json











