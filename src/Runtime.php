<?php

namespace tuefekci\overcli;

class Runtime
{

	private static $instance;

	private $windows;

	private $timeStart;
	private $timeSinceStart;

	private $tickCount;

	private bool $state;

	public function __construct()
	{
		if (is_null(self::$instance)) {
			self::$instance = $this;
		}

		if(\tuefekci\helpers\System::isCli()) {
			cli_set_process_title("OverCLI Runtime Version:");
		}

		// =========================================================
		// Shutdown Handler Windows
		// TODO: Make this better and more portable
		$_this = self::$instance;
		if(function_exists("pcntl_async_signals") && function_exists("pcntl_async_signals")) {
			pcntl_async_signals(true);
			pcntl_signal(SIGINT, function() use ($_this) {
				$_this->stop();
				die();
				exit();
			});
		}

		register_shutdown_function(function() use ($_this) {
			$_this->stop();
		});
		// =========================================================

		$this->start();

		return $this;
	}

    private static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	public static function getState() {
		return self::getInstance()->state;
	}

	public static function run() {
		$instance = self::getInstance();

		while(self::getState()) {
			$instance->tick();
			usleep(100);
		}

		return self::getInstance();
	}

	public static function start()
	{
		self::getInstance()->timeStart = self::getInstance()->getMilliseconds();
		self::getInstance()->state = true;
	}

	public static function pause() {
		$instance = self::getInstance();
	}

	public static function stop()
	{
		$instance = self::getInstance();

		if(!empty($instance->windows)) {
			foreach($instance->windows as $window) {
				$window->close();
			}
		}

		$instance->state = false;
	}

	public static function tick()
	{
		$_this = self::getInstance();
		$_this->tickCount++;

		foreach($_this->windows as $window) {
			$window->tick();
		}



	}

	public static function getTimeSinceStart()
	{
		$_this = self::getInstance();
		return $_this->timeSinceStart = $_this->getMilliseconds() - $_this->timeStart;
	}

	public static function getTimeStart()
	{
		$_this = self::getInstance();
		return $_this->timeStart;
	}

	public static function addWindow($title = null)
	{
		return self::getInstance()->windows[] = new Window(self::getInstance(), $title);
	}

	public static function getWindow(int $index)
	{
		$_this = self::getInstance();
		return $_this->windows[$index];
	}

	public static function unsetWindow(int $index)
	{
		$_this = self::getInstance();
		unset($_this->windows[$index]);
	}

	public static function getWindows()
	{
		$_this = self::getInstance();
		return $_this->windows;
	}

	public function getWindowsCount()
	{
		$_this = self::getInstance();
		return count($_this->windows);
	}

	public static function getMilliseconds()
	{
		return round(microtime(true) * 1000);
	}

}
