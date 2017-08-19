<?php
$setting = array(
	'appDir' => phpkitRoot,
	'registerNamespaces' => array(
		'app\\services' => phpkitRoot . '/app/services', //服务层目录
		'app\\models' => phpkitRoot . '/app/models', //Model层实现目录
	),
);

//di 服务注入
//rpcClient 微服务类
$setting['di']['rpcClient'] = function () {
	$servicesApi = array(
		'Services\\Demo' => dirname(phpkitRoot) . '/phpDAS_api/gen-php', //Services的目录
	);
	$rpcClient = new \phpkit\thriftrpc\Client($servicesApi);
	return $rpcClient;
};

//配置读取类
$setting['di']['config'] = function () {
	$params = array(
		'configDir' => phpkitRoot . '/config/',
	);
	$config = new \phpkit\config\Config($params);
	return $config;
};

//数据库
$setting['di']['db'] = function () {
	$params = array(
		'configDir' => phpkitRoot . '/config/',
	);
	$config = new \phpkit\config\Config($params);
	$DbConfig = $config->get("database");
	return new \Phalcon\Db\Adapter\Pdo\Mysql($DbConfig);
};

//缓存注入
$setting['di']['cache'] = function () {
	return new \phpkit\redis\Redis(array(
		"prefix" => 'project-name-data-cache-',
		'host' => '127.0.0.1',
		'port' => 6379,
		'persistent' => true,
	));
};

return $setting;