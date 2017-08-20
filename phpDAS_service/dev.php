<?php
$xhprof_enable = 0; //是否性能跟跟踪
if (function_exists('xhprof_enable') && $xhprof_enable == 1) {
	xhprof_enable();
}
require 'vendor/autoload.php';
require 'Swoole/Server.php';
use phpkit\core\Phpkit as Phpkit;
use \phpkit\base\BaseController;

//项目根目录
define("phpkitRoot", dirname(__FILE__));
$phpkit = new Phpkit();
$setting = require phpkitRoot . '/config/setting.php';

class IndexController extends BaseController {
	public function fire_theme_method($class, $method) {
		$fire_args = array();
		$params = $this->dispatcher->getParams();
		$reflection = new ReflectionMethod($class, $method);
		foreach ($reflection->getParameters() AS $arg) {
			if ($params[$arg->name]) {
				$fire_args[$arg->name] = $params[$arg->name];
			} else {
				$fire_args[$arg->name] = null;
			}

		}
		echo '请求参数：';
		print_r($fire_args);
		return call_user_func_array(array($class, $method), $fire_args);
	}

	public function indexAction() {
		try {
			$this->getDi()->getRpcClient();
			$p = explode("/", trim(parse_url($_SERVER['REQUEST_URI'])['path'], "/"));
			$serverName = "\\app\\services\\" . ucfirst($p[0]);
			$method = $p[1];
			if (!class_exists($serverName)) {
				throw new \Exception($serverName . "  class_not_exists", 1);
			}
			$service = new $serverName();
			//$res = $service->$method("wen");
			$res = $this->fire_theme_method($service, $method);
			echo '请求返回：';
			print_r($res);
		} catch (\Exception $e) {
			throw new \Exception($e->getMessage());
		}
	}
}

//初始化phpkit
$app = $phpkit->run($setting);

if (function_exists('xhprof_enable') && $xhprof_enable == 1) {
	$XHPROF_ROOT = "/Volumes/UNTITLED/www/xhprof/"; //xhprof的根目录
	$dataDir = '/Volumes/UNTITLED/www/xhprof/tmp'; //文件保存目录
	$xhprof_data = xhprof_disable('/tmp');
	include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
	include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
	$xhprof_runs = new XHProfRuns_Default($dataDir);
	$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");
	echo '<script language="javascript" type="text/javascript">console.log("http://debug.com/index.php/index.php?run=' . $run_id . '&source=xhprof_testing\n");</script>';
}
