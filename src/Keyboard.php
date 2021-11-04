<?php

namespace tuefekci\overcli;

class Keyboard {

    public $stream;
    public $keyPressing = null;
    public $started = false;

    public function __construct()
    {
        exec('stty -icanon -echo');
        $this->stream = fopen('php://stdin', 'r');
        stream_set_blocking($this->stream, false);
    }	

    public function check()
    {
        $key = fread($this->file, 1);

        if (!empty($key)) {
            $this->keyDown($key);
        } elseif (!empty($this->keyPressing)) {
            $this->keyUp($this->keyPressing);
        }

        $this->keyPressing = $key;
    }

    public function matchKey($key)
    {
        //Maps a keyboard key to a gameboy key.
        //Order: Right, Left, Up, Down, A, B, Select, Start

        $keyIndex = array_search($key, Settings::$keyboardButtonMap);

        if ($keyIndex === false) {
            return -1;
        }

        return $keyIndex;
    }

    public function keyDown($key)
    {
        $keyCode = $this->matchKey($key);

        if ($keyCode > -1) {
            $this->core->joyPadEvent($keyCode, true);
        }
    }

    public function keyUp($key)
    {
        $keyCode = $this->matchKey($key);

        if ($keyCode > -1) {
            $this->core->joyPadEvent($keyCode, false);
        }
    }


	public static function getInput() {
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		fclose($handle);
		return trim($line);
	}

	public static function getInputWithDefault($default) {
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		fclose($handle);
		return trim($line) ?: $default;
	}

	public static function getInputWithDefaultAndValidation($default, $validation) {
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		fclose($handle);
		$line = trim($line) ?: $default;
		if ($validation($line)) {
			return $line;
		} else {
			return self::getInputWithDefaultAndValidation($default, $validation);
		}
	}

	public static function getInputWithValidation($validation) {
		$handle = fopen("php://stdin", "r");
		$line = fgets($handle);
		fclose($handle);
		if ($validation($line)) {
			return $line;
		} else {
			return self::getInputWithValidation($validation);
		}
	}



