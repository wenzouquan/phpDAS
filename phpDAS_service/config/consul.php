<?php

//node的名字，每一个都必须不一样
$config['node_name'] = 'node1';
//consul的data_dir默认放在临时文件下
$config['data_dir'] = "/tmp/consul";
//consul join地址，可以是集群的任何一个，或者多个
$config['start_join'] = ["127.0.0.1"];
//本地网卡地址
$config['bind_addr'] = "127.0.0.1";
return $config;