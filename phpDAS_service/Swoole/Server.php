<?php
namespace Swoole\phpkit;
require 'Swoole/Socket.php';
class Server {
	protected $processor = null;
	protected $ip = "127.0.0.1";
	protected $port = "8091";
	protected $application = null;
	protected $config = array();
	function __construct($app) {
		$this->application = $app;
		$this->application->getDi()->getRpcClient();
		$this->config = $this->application->getDi()->getConfig()->get("server");
		var_dump($this->config);
	}
	function onStart() {
		echo "tcp : " . $this->ip . ":" . $this->config['port'] . "  Start\n";
	}

	function log($log) {
		echo $log . "\n";
	}

	public function onReceive($serv, $fd, $from_id, $data) {
		$serviceName = $this->getServiceName($serv, $fd, $from_id, $data);
		$tMultiplexedProcessor = $this->addService($serviceName);
		try {
			$protocol = $this->getProtocol($serv, $fd, $from_id, $data);
			$tMultiplexedProcessor->process($protocol, $protocol);
		} catch (\Exception $e) {
			$this->log('CODE:' . $e->getCode() . ' MESSAGE:' . $e->getMessage() . "\n" . $e->getTraceAsString());
		}
	}

	function getProtocol($serv, $fd, $from_id, $data) {
		$socket = new Socket();
		$socket->setHandle($fd);
		$socket->buffer = $data;
		$socket->server = $serv;
		$protocol = new \Thrift\Protocol\TBinaryProtocol($socket, false, false);
		return $protocol;
	}

	function getServiceName($serv, $fd, $from_id, $data) {
		$protocol = $this->getProtocol($serv, $fd, $from_id, $data);
		$rseqid = 0;
		$fname = null;
		$mtype = 0;
		$protocol->readMessageBegin($fname, $mtype, $rseqid);
		list($serviceName, $messageName) = explode(':', $fname, 2);
		return $serviceName;
	}

	function addService($serviceName) {
		$arr = explode("\\", $serviceName);
		$name = $arr[count($arr) - 1];
		$processor_class = $serviceName . "\\" . $name . 'Processor';
		$impl_class = "app\\services\\" . $name;
		$handler = new $impl_class();
		$processor = new $processor_class($handler);
		$tMultiplexedProcessor = new \Thrift\TMultiplexedProcessor();
		$tMultiplexedProcessor->registerProcessor($serviceName, $processor);
		return $tMultiplexedProcessor;
	}

	function addServiceToConsul() {
		$serviceName = "Services\Demo\HiService";
		$this->log("register " . $serviceName);
		$consul = new \phpkit\consulapi\Consul();
		$data = array(
			'ID' => md5($this->ip . ":" . $this->port . "_" . $serviceName),
			'Name' => $serviceName,
			'Address' => $this->ip,
			'Port' => intval($this->config['port']),
			'Check' => array(
				'name' => 'status',
				'tpc' => $this->ip . ":" . $this->port,
				'Interval' => '5s',
				'timeout' => '1s',
			),
		);
		$res = $consul->registerServices(json_encode($data));

	}

	function serve() {
		//$serv = new \Swoole\Server($this->ip, $this->port);
		$serv = new \swoole_server($this->ip, $this->config['port']);
		$serv->on('workerStart', [$this, 'onStart']);
		$serv->on('receive', [$this, 'onReceive']);
		$serv->set($this->config['set']);
		// $this->log("注册服务");
		// $this->addServiceToConsul();
		$serv->start();

	}
}
