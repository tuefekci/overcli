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

	
	private int $fps = 0;
	private int $fpsCount = 0;

	private string $title = "";

	private bool $clearScreen = true;

	private $runtime;

	public function __construct($runtime, $title=null)
	{
		$_this = $this;
		$this->runtime = $runtime;
		$this->time = microtime(true);

		$this->getTTYSize();
		$this->stdout = fopen('php://stdout', 'w');

		$this->hideCursor();

		if(!empty($title)) {
			$this->setTitle($title);
		}

		$this->frameRate = 60;

		return $this;
	}

	public function close() {
		$this->clearScreen();
		$this->showCursor();
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

	private function showCursor() {
		fprintf($this->stdout, chr(27) . "[?25h"); //show cursor
	}

	private function hideCursor() {
		fprintf($this->stdout, chr(27) . "[?25l"); // hide cursor
	}

	private function clearScreen($stream = STDOUT) {
		if($this->clearScreen) {
			fwrite($stream, chr(27).chr(91).'H'.chr(27).chr(91).'J'); // clear screen
		}
	}

	public function setFrameRate($frameRate) {
		$this->frameRate = $frameRate;
	}

	public function getFPS() {
		return $this->fps;
	}

	public function getDrawCount() {
		return $this->drawCount;
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

		$frameTime = round(1000/$this->frameRate);
		$milliseconds = $this->runtime->getMilliseconds();

		if(($this->lastFPSCount + 1000) <= $milliseconds) {
			$this->fps = $this->fpsCount;
			$this->fpsCount = 0;
			$this->lastFPSCount = $milliseconds;
		}

		if(($this->lastFrameTime + $frameTime) <= $milliseconds) {
			$this->fireEvent("update", $this);

			$this->lastFrameTime = $milliseconds;
			$this->draw();
		}

		/*
		$window->setFrame($buffer);
		$window->draw();
		usleep(round(1000000/24));
		*/

	}

	public function draw() {
		$this->fpsCount += 1;
		$this->fireEvent("draw", $this);

		$this->clearScreen();
		fwrite($this->stdout, $this->frame);
		$this->getTTYSize();
		$this->drawCount += 1;
	}


}