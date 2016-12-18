<?php
/**
 * Kerisy Framework
 * 
 * PHP Version 7
 * 
 * @author          Jiaqing Zou <zoujiaqing@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package         kerisy/framework
 * @subpackage      Console
 * @since           2015/11/11
 * @version         2.0.0
 */

namespace Kerisy\Console;

use Kerisy\Core\Console\Command;
use Kerisy\Core\InvalidParamException;
use Kerisy\Core\InvalidValueException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class TaskCommand
 *
 * @package Kerisy\Console
 */
class TaskCommand extends Command
{
    public $name = 'task';
    public $description = 'Kerisy task management';

    protected function configure()
    {
        $this->addArgument('operation', InputArgument::REQUIRED, 'the operation: serve, start, restart or stop');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $operation = $input->getArgument('operation');

        if (!in_array($operation, ['run', 'start', 'restart', 'stop'])) {
            throw new InvalidParamException('The <operation> argument is invalid');
        }

        return call_user_func([$this, 'handle' . $operation]);

    }

    protected function handleRun()
    {
		//$server = config('service')->all();
	    $server = $this->getService();
		$server['asDaemon'] = false;
	    
		return make($server)->run();
    }

    protected function handleStart()
    {
        $pidFile = APPLICATION_PATH . 'runtime/task.pid';

        if (file_exists($pidFile)) {
            throw new InvalidValueException('The pidfile exists, it seems the server is already started');
        }

        //$server = config('service')->all();
        $server = $this->getService();
        $server['asDaemon'] = true;
        $server['pidFile'] = APPLICATION_PATH . 'runtime/task.pid';

        return make($server)->run();
    }

    protected function handleRestart()
    {
        $this->handleStop();

        return $this->handleStart();
    }

    protected function handleStop()
    {
        $pidFile = APPLICATION_PATH . 'runtime/task.pid';
        if (file_exists($pidFile) && posix_kill(file_get_contents($pidFile), 15)) {
            do {
                usleep(100000);
            } while(file_exists($pidFile));
            return 0;
        }

        return 1;
    }

    private function getService()
    {
		$server = config('service')->all();
		
		if ( array_key_exists('task', $server) )
		{
			return $server['task'];
		}
		else
		{
			throw new InvalidValueException('Error: task configuration parameter not found');
		}
    }
}
