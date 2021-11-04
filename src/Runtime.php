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

	public static function start()
	{
		self::getInstance()->timeStart = microtime(true);
		self::getInstance()->state = true;
	}

	public static function pause() {
		$instance = self::getInstance();
	}

	public static function stop()
	{
		$instance = self::getInstance();
		self::getInstance()->state = false;
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
		return $_this->timeSinceStart = microtime() - $_this->timeStart;
	}

	public static function getTimeStart()
	{
		$_this = self::getInstance();
		return $_this->timeStart;
	}

	public static function addWindow($title = null)
	{
		$_this = self::getInstance();
		return $_this->windows[] = new Window($title);
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

}
