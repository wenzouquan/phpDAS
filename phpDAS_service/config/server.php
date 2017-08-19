<?php

/**
 * 服务器设置
 */

$config['port'] = 8091;
$config['set'] = [
	'log_file' => phpkitRoot . "/log/swoole.log",
	'log_level' => 5,
	'backlog' => 128, //listen backlog
	'open_tcp_nodelay' => 1,
	'enable_reuse_port' => true,
	'heartbeat_idle_time' => 120, //2分钟后没消息自动释放连接
	'worker_num' => 100, //进程数
	'dispatch_mode' => 1, //1: 轮循, 3: 争抢
	'open_length_check' => true, //打开包长检测
	'package_max_length' => 8192000, //最大的请求包长度,8M
	'package_length_type' => 'N', //长度的类型，参见PHP的pack函数
	'package_length_offset' => 0, //第N个字节是包长度的值
	'package_body_offset' => 4, //从第几个字节计算长度
	'heartbeat_check_interval' => 10, ///每隔多少秒检测一次，单位秒
];

return $config;