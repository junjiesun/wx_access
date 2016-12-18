<?php
/**
 * Kerisy Framework
 *
 * PHP Version 7
 *
 * @author          Jiaqing Zou <zoujiaqing@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package         kerisy/framework
 * @subpackage      Server
 * @since           2015/11/11
 * @version         2.0.0
 */

namespace Kerisy\Server;

/**
 * A Swoole based server implementation.
 *
 * @package Kerisy\Server
 */
class Task extends Base
{
    /**
     * The number of requests each process should execute before respawning, This can be useful to work around
     * with possible memory leaks.
     *
     * @var int
     */
    public $maxRequests = 65535;

    /**
     * The number of workers should be started to serve requests.
     *
     * @var int
     */
    public $numWorkers;

    /**
     * Detach the server process and run as daemon.
     *
     * @var bool
     */
    public $asDaemon = false;

    /**
     * Specifies the path where logs should be stored in.
     *
     * @var string
     */
    public $logFile;


    private function normalizedConfig()
    {
        $config = [];

        $config['max_request'] = $this->maxRequests;
        $config['daemonize'] = $this->asDaemon;

        if ($this->numWorkers) {
            $config['worker_num'] = $this->numWorkers;
        }

        if ($this->logFile) {
            $config['log_file'] = $this->logFile;
        }

        return $config;
    }

    private function createServer()
    {
    	
        $server = new \swoole_server($this->host, $this->port);
		$server->set($this->normalizedConfig());
		
		if (method_exists($this, 'onReceive'))
		{
			$server->on('Receive', [$this, 'onReceive']);
		}

		if (method_exists($this, 'onTask'))
		{
			$server->on('Task', [$this, 'onTask']);
		}
		
		if (method_exists($this, 'onFinish'))
		{
			$server->on('Finish', [$this, 'onFinish']);
		}
		
		return $server;
        
    }
	
	public function onReceive()
	{
		
	}
	
	public function onTask()
	{
		
	}
	
	public function onFinish()
	{
		
	}
	
    public function run()
    {
        $server = $this->createServer();
        $server->start();
    }
}
