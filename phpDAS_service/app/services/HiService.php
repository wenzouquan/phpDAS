<?php
namespace app\services;
use \phpkit\base\BaseController;

class HiService extends BaseController implements \Services\Demo\HiService\HiServiceIf {

	public function say($name) {
		$users = new \app\models\Users();
		$data = $users->load(1);
		return json_encode($data) . "\n";
		//return "hi," . $name;
	}
}