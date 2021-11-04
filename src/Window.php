<?php

namespace tuefekci\overcli;

class Window
{

	private int $height = 0;
	private int $width = 0;
	private $frame;
	private int $frameRate;

	private $stdout;
	
	private array $events;

	private int $lastFrameTime = 0;
	private int $drawCount = 0;
	private int $tick = 0;

	private string $title = "";

	public function __construct($title=null)
	{
		$_this = $this;
		$this->time = microtime(true);

		$this->getTTYSize();
		$stream = $this->stdout = fopen('php://stdout', 'w');

		$this->hideCursor($this->stdout);

		register_shutdown_function(function() use ($stream) {
			//fwrite($stream, chr(27).chr(91).'H'.chr(27).chr(91).'J'); // clear screen
		});

		if(!empty($title)) {
			$this->setTitle($title);
		}

		$this->frameRate = 60;

		return $this;
	}

	public function getHeight() {
		return $this->height;
	}

	public function getWidth() {
		return $this->width;
	}

	public function setTitle($title) {

		$this->title = $title;

		if(\tuefekci\helpers\System::isCli()) {
			fwrite($this->stdout, "\033]0;{$title}\007");
		}

	}

	public function getTitle() {
		return $this->title;
	}

	private function getTTYSize() {

		if(\tuefekci\helpers\System::isWin()) {

		} else {
			$return = shell_exec("stty size");
			$return = explode(" ", $return);

			$this->width = (int) $return[1];	
			$this->height = (int) $return[0];
		}

	}

	public function getFrame() {
		return $this->frame;
	}

	public function setFrame($frame) {
		$this->frame = $frame;
	}

	private function hideCursor($stream = STDOUT) {
		fprintf($stream, "\033[?25l"); // hide cursor
		register_shutdown_function(function() use($stream) {
			fprintf($stream, "\033[?25h"); //show cursor
		});
	}

	private function clearScreen($stream = STDOUT) {
		//fwrite($stream, chr(27).chr(91).'H'.chr(27).chr(91).'J'); // clear screen
	}

	public function setFrameRate($frameRate) {
		$this->frameRate = $frameRate;
	}

	public function onEvent(string $event, $callback) {
		$event = strtolower($event);
		$eventId = uniqid($event."_");
		$this->events[$event][$eventId] = $callback;
	}

	public function cancelEvent(string $event, $eventId) {
		$event = strtolower($event);
		unset($this->events[$event][$eventId]);
	}

	private function fireEvent(string $event, $data) {
		$event = strtolower($event);
		if(isset($this->events[$event])) {
			foreach($this->events[$event] as $eventId => $callback) {
				$callback($data);
			}
		}
	}

	public function tick() {
		$this->tick++;

		$this->fireEvent("tick", $this);

		$frameTime = round(1000000/$this->frameRate);

		if($this->lastFrameTime + $frameTime <= microtime(true)) {

			$this->fireEvent("update", $this);

			$this->lastFrameTime = microtime(true);
			$this->draw();
		}

		/*
		$window->setFrame($buffer);
		$window->draw();
		usleep(round(1000000/24));
		*/

	}

	public function draw() {

		$this->fireEvent("draw", $this);

		$this->clearScreen();
		fwrite($this->stdout, $this->frame);
		$this->getTTYSize();
		$this->drawCount++;
	}


}