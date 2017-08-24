<?php
require 'vendor/autoload.php';
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
//$client->setHttpDedug(1);//使用http调试
//$client->setXdebugSession(14462);//调试debug连接id
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



