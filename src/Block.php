<?php

namespace tuefekci\overcli;

class Block
{

	private string $id;

	private $width = 0;
	private $height = 0;

	private $calculatedWidth = 0;
	private $calculatedHeight = 0;

	private string $borderColor = '#000';
	private string $backgroundColor = '#fff';

	private string $textColor = '#000';

	private bool $borderTop = false;
	private bool $borderBottom = false;
	private bool $borderLeft = false;
	private bool $borderRight = false;


	private int $paddingTop = 0;
	private int $paddingBottom = 0;
	private int $paddingLeft = 0;
	private int $paddingRight = 0;

	private int $marginTop = 0;
	private int $marginBottom = 0;
	private int $marginLeft = 0;
	private int $marginRight = 0;

	private int $borderWidth = 1;

	private Block $parent;



	private array $content;

	public function __construct(string $id, array $options = [], array $content = [])
	{
		$this->id = $id;
		$this->setOptions($options);
		$this->content = $content;
	}

	public function setOptions(array $options)
	{
		foreach($options as $key => $value) {
			$this->setOption($key, $value);
		}
	}

	public function setOption(string $key, $value)
	{
		switch($key) {
			case 'width':
				$this->width = $value;
				break;
			case 'height':
				$this->height = $value;
				break;
			case 'border-color':
				$this->borderColor = $value;
				break;
			case 'background-color':
				$this->backgroundColor = $value;
				break;
			case 'text-color':
				$this->textColor = $value;
				break;
			case 'border':
				$this->borderTop = $value;
				$this->borderBottom = $value;
				$this->borderLeft = $value;
				$this->borderRight = $value;
				break;
			case 'border-top':
				$this->borderTop = $value;
				break;
			case 'border-bottom':
				$this->borderBottom = $value;
				break;
			case 'border-left':
				$this->borderLeft = $value;
				break;
			case 'border-right':
				$this->borderRight = $value;
				break;
			case 'padding-top':
				$this->paddingTop = $value;
				break;
			case 'padding-bottom':
				$this->paddingBottom = $value;
				break;
			case 'padding-left':
				$this->paddingLeft = $value;
				break;
			case 'padding-right':
				$this->paddingRight = $value;
				break;
			case 'margin-top':
				$this->marginTop = $value;
				break;
			case 'margin-bottom':
				$this->marginBottom = $value;
				break;
			case 'margin-left':
				$this->marginLeft = $value;
				break;
			case 'margin-right':
				$this->marginRight = $value;
				break;
			case 'border-width':
				$this->borderWidth = $value;
				break;
		}
	}


	public function setWidth(int $width)
	{
		$this->width = $width;
	}

	public function setHeight(int $height)
	{
		$this->height = $height;
	}

	private function generateTopBorder() {
		$border = '';

		if ($this->borderLeft) {
			$border .= '┌';
		}

		if ($this->borderTop) {

			$width = $this->width;

			if($this->borderLeft) {
				$width -= 1;
			}

			if($this->borderRight) {
				$width -= 1;
			}

			$border .= str_repeat("─", $width);
		}

		if ($this->borderRight) {
			$border .= '┐';
		}

		return $border.PHP_EOL;
	}

	private function generateBottomBorder() {
		$border = '';

		if ($this->borderLeft) {
			$border .= '└';
		}

		if ($this->borderBottom) {

			$width = $this->width;

			if($this->borderLeft) {
				$width -= 1;
			}

			if($this->borderRight) {
				$width -= 1;
			}

			$border .= str_repeat("─", $width);
		}

		if ($this->borderRight) {
			$border .= '┘';
		}

		return $border.PHP_EOL;
	}


	private function generateLine($content) {

		$line = '';

		if ($this->borderLeft) {
			$line .= '│';
		}

		if($this->paddingLeft) {
			$line .= str_repeat(' ', $this->paddingLeft);
		}

		//$line .= \tuefekci\helpers\Strings::truncate($content, $this->width, "");
		$line .= $content;


		if($this->paddingRight) {
			$line .= str_repeat(' ', $this->paddingRight);
		}

		if ($this->borderRight) {

			$spacing = ($this->width+1)-strlen($line);
			if($spacing < 1) {
				$spacing = 0;
			}

			$line .= str_repeat(" ", $spacing );
			$line .= '│';
		}

		$line .= "".PHP_EOL;

		return $line;
	}

	public function draw()
	{

		if($this->hasParent()) {
			//$this->calculateWidth();
			//$this->calculateHeight();
		}

		$canvas = "";

		if($this->borderTop) {
			$canvas .= $this->generateTopBorder();
		}

		//$height = $this->height-10;

		foreach($this->content as $key => $content) {

			if(is_object($content) && $content instanceof Block) {

				$width = $this->width;

				if($this->borderLeft) {
					$width -= 1;
				}
	
				if($this->borderRight) {
					$width -= 1;
				}
	
				if($this->paddingLeft) {
					$width -= $this->paddingLeft;
				}
	
				if($this->paddingRight) {
					$width -= $this->paddingRight;
				}
	
				$content->setWidth($width);

				$contentLines = $content->draw();
				$contentLines = explode(PHP_EOL, $contentLines);
				unset($contentLines[array_key_last($contentLines)]);

				foreach($contentLines as $contentLine) {
					$canvas .= $this->generateLine($contentLine);
				}

			} elseif(is_string($content)) {
				$canvas .= $this->generateLine($content);
			}


		}

		if($this->borderBottom) {
			$canvas .= $this->generateBottomBorder();
		}



		return $canvas;

	}

	private function hasParent()
	{
		return !empty($this->parent);
	}

	public function hasContent()
	{
		return !empty($this->content);
	}

	public function setParent(Block $parent)
	{
		$this->parent = $parent;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function setContent(array $content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;	
	}

	public function add($content) {

		if(is_object($content) && $content instanceof Block) {
			$content->setParent($this);
			$this->content[] = $content;
		} else {
			$this->content[] = $content;
		}
	}

	public function clear() {
		$this->content = [];
	}


}