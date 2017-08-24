<?php
//项目根目录
define("phpkitRoot", dirname(__FILE__));
require phpkitRoot.'/vendor/autoload.php';
require phpkitRoot.'/bin/Server.php';
use phpkit\core\Phpkit as Phpkit;
$phpkit = new Phpkit();
$setting = require phpkitRoot . '/config/setting.php';
//初始化phpkit
$app = $phpkit->init($setting);
$server = new \bin\phpkit\Server($app);
$server->serve();
