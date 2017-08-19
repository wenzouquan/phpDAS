<?php
require 'vendor/autoload.php';
require 'Swoole/Server.php';
use phpkit\core\Phpkit as Phpkit;
//项目根目录
define("phpkitRoot", dirname(__FILE__));
$phpkit = new Phpkit();
$setting = require phpkitRoot . '/config/setting.php';
//初始化phpkit
$app = $phpkit->init($setting);
$server = new \Swoole\phpkit\Server($app);
$server->serve();
