<?php


namespace tuefekci\overcli;

class LineBuffer
{
	private $buffer = [];

	public function add($line)
	{
		$this->buffer[] = $line;
	}

	public function get()
	{
		return $this->buffer;
	}

	public function set(array $lines)
	{
		$this->buffer = $lines;
	}

	public function getFirstAndRemove()
	{
		$return = $this->buffer[array_key_first($this->buffer)];
        unset($this->buffer[array_key_first($this->buffer)]);
		return $return;
	}

	public function clear()
	{
		$this->buffer = [];
	}

	public function isEmpty()
	{
		return empty($this->buffer);
	}
}