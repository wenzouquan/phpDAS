<?php
namespace bin\phpkit;
require phpkitRoot . '/bin/Socket.php';

class Server
{
    protected $processor = null;
    protected $ip;
    protected $port;
    protected $application = null;
    protected $config = array();

    function __construct($app)
    {
        $this->application = $app;
        $this->application->getDi()->getRpcClient();
        $this->config = $this->application->getDi()->getConfig()->get("server");
        $this->ip = $this->config['ip'] ? $this->config['ip'] : \phpkit\helper\getLocalIP();
    }

    function onStart()
    {
        echo "tcp : " . $this->ip . ":" . $this->config['port'] . "  Start\n";
    }

    function log($log)
    {
        echo $log . "\n";
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        if ( json_decode($data, true)) {//如果接收的是json数据,直接调用服务
             $serv->task($data);
        } else {//通thrifft调用
            $serviceName = $this->getServiceName($serv, $fd, $from_id, $data);
            $tMultiplexedProcessor = $this->addService($serviceName);
            try {
                $protocol = $this->getProtocol($serv, $fd, $from_id, $data);
                $tMultiplexedProcessor->process($protocol, $protocol);
            } catch (\Exception $e) {
                $this->log('CODE:' . $e->getCode() . ' MESSAGE:' . $e->getMessage() . "\n" . $e->getTraceAsString());
            }
        }

    }
    public function onTask($serv, $task_id, $from_id, $data){
        $this->log('task_id:'.$task_id."\n"."DATA:".$data);
        try {
            $params = json_decode($data, true);
            $className = $params['className'];
            if (!class_exists($className)) {
                throw new \Exception($className . "  class_not_exists", 1);
            }
            if(is_array($params['constructorArgs']) && !empty($params['constructorArgs'])){
                $constructorArgs=$this->getConstructorParameters($className,$params['constructorArgs']);
                $class = new $className($constructorArgs);
            }else{
                $class = new $className();
            }
            $ret = call_user_func_array(array($class, $params['method']), $this->methodParameters($class,$params['method'],$params['args']));

            $serv->finish(json_encode($ret));
        } catch (\Exception $e) {
            $this->log('CODE:' . $e->getCode() . ' MESSAGE:' . $e->getMessage() . "\n" . $e->getTraceAsString().' PARAMS:'.$data);
        }
    }

    public function onFinish($serv, $task_id, $data){
        $this->log('task_id:'.$task_id."\n"."Finish:".$data);
    }

//获得类某一个函数的参数
    public function methodParameters($class, $method,$params) {
        $fire_args = array();
        $reflection = new \ReflectionMethod($class, $method);
        foreach ($reflection->getParameters() AS $k=>$arg) {
            if ($params[$k]) {
                $fire_args[$arg->name] = $params[$k];
            } else {
                $fire_args[$arg->name] = null;
            }

        }
        return $fire_args;
    }

//获得类构造函数参数
    function getConstructorParameters($className,$params){
        $class = new \ReflectionClass($className);
        $constructor = $class->getConstructor();
        $args = [];
        if ($constructor->getParameters()) {
            foreach ($constructor->getParameters() as $key => $value) {
                $args[] = $params[$value->name];
            }
        }
        return $args;
    }


    function getProtocol($serv, $fd, $from_id, $data)
    {
        $socket = new Socket();
        $socket->setHandle($fd);
        $socket->buffer = $data;
        $socket->server = $serv;
        $protocol = new \Thrift\Protocol\TBinaryProtocol($socket, false, false);
        return $protocol;
    }

    function getServiceName($serv, $fd, $from_id, $data)
    {
        $protocol = $this->getProtocol($serv, $fd, $from_id, $data);
        $rseqid = 0;
        $fname = null;
        $mtype = 0;
        $protocol->readMessageBegin($fname, $mtype, $rseqid);
        list($serviceName, $messageName) = explode(':', $fname, 2);
        return $serviceName;
    }

    function addService($serviceName)
    {
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

//本项目中的所有服务
    function getAllServices()
    {
        $serviceDir = phpkitRoot . "/app/services/";
        $files = scandir($serviceDir);
        $serviceNames = [];
        foreach ($files as $file) {
            if (strpos($file, ".php")) {
                $classname = "\\app\\services\\" . str_replace(".php", "", $file);
                $reflection = new \ReflectionClass($classname);
                foreach ($reflection->getInterfaceNames() as $interface) {
                    if (strpos($interface, "Services") === 0) {
                        $reflection = new \ReflectionClass($interface);
                        $serviceNames[] = $reflection->getNamespaceName();
                    }
                }
            }
        }
        return $serviceNames;
    }

    /**
     * @return null
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param null $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }


//生成服务配置
    function addServiceToConsul()
    {
        $serviceNames = $this->getAllServices();
        $config = $this->application->getDi()->getConfig()->get("consul");
        $data['node_name'] = $config['node_name'];
        $data['start_join'] = $config['start_join'];
        $data['data_dir'] = $config['data_dir'];
        $data['bind_addr'] = $config['bind_addr'];
        foreach ($serviceNames as $serviceName) {
            $data['watches'][] = ['type' => 'service', 'passingonly' => true, 'service' => $serviceName, 'handler' => "php  " . phpkitRoot . "/bin/AddServerToRedis.php " . str_replace('\\', '\\\\', $serviceName) . "@" . $this->ip . "@" . $this->config['port'] . "@" . $this->config['http']];
            var_dump("php  " . phpkitRoot . "/bin/AddServerToRedis.php " . str_replace('\\', '\\\\', $serviceName) . "@" . $this->ip . "@" . $this->config['port'] . "@" . $this->config['http']);
            $data['services'][] = array(
                'ID' => md5($this->ip . ":" . $this->config['port'] . "_" . $serviceName),
                'Name' => $serviceName,
                'Address' => $this->ip,
                'Port' => intval($this->config['port']),
                'tags' => [$serviceName, $this->ip, (string)$this->config['port'], $this->config['http']],
                'Check' => array(
                    'name' => 'status',
                    // 'tpc' => $this->ip . ":" . $this->port,
                    'tcp' => "$this->ip:" . $this->config['port'],
                    //  'http'=>'www.text.je',
                    'Interval' => '5s',
                    'timeout' => '1s',
                ),
            );
        }
        file_put_contents(dirname(phpkitRoot) . "/consul/config/" . $this->ip . ":" . $this->config['port'] . ".json", json_encode($data));
        $this->log("生成服务配置文件:consul agent -dev -config-dir=" . dirname(phpkitRoot) . "/consul/config");

    }

    function serve()
    {
        $serv = new \Swoole\Server($this->ip, $this->config['port']);
        //$serv = new \swoole_server($this->ip, $this->config['port']);
        $serv->on('workerStart', [$this, 'onStart']);
        $serv->on('receive', [$this, 'onReceive']);
        $serv->on('Task', [$this, 'onTask']);
        $serv->on('Finish', [$this, 'onFinish']);
        $serv->set($this->config['set']);
        $this->log("生成服务配置");
        $this->addServiceToConsul();
        $serv->start();
    }

}
