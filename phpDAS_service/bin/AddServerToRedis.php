<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 17/8/24
 * Time: 上午11:41
 */

global $argv;

$serviceInfo = explode("@",trim($argv[1]));
if(is_array($serviceInfo) && count($serviceInfo)==4){
$input="";
while ($line = fopen('php://stdin', 'r')) {
    $input = fgets($line);
    break;
}
$redis =  new \Redis();
$redis->connect("127.0.0.1", "6379");
$serviceName = $serviceInfo[0];
$nodes = json_decode($input, true);
//代表服务不可用,全部移除//删除服务
    $serviceInfoString = $serviceInfo[1]."@".$serviceInfo[2]."@".$serviceInfo[3];
    if (empty($nodes) || count($nodes) == 0) {
        $redis->sRem($serviceName,$serviceInfoString);
    }else{
        //添加服务
        $r =  $redis->sAdd($serviceName,$serviceInfoString);
    }
}









