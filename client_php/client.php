<?php
require 'vendor/autoload.php';
//定义服务接口
$servicesApi = array(
	'Services\\Demo' => dirname(dirname(__FILE__)) . '/phpDAS_api/gen-php', //Services的目录
);
//实例化客户端
$client = new \phpkit\thriftrpc\Client($servicesApi);
//实例化服务
$hiService = $client->getRPCService("Services\\Demo\\HiService", "127.0.0.1", "8091");
//服务调用
echo $hiService->say("wen");