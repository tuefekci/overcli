<?php

namespace tuefekci\overcli;

class Block
{

	private string $id;

	private $styleWidth = 0;
	private $styleHeight = 0;

	private $width = 0;
	private $height = 0;

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
				$this->styleWidth = $value;
				break;
			case 'height':
				$this->styleHeight = $value;
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

	public function getWidth()
	{
		return $this->width;
	}

	public function getStyleWidth()
	{
		return $this->styleWidth;
	}

	public function setHeight(int $height)
	{
		$this->height = $height;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function getStyleHeight()
	{
		return $this->styleHeight;
	}

	private function generateTopBorder() {
		$border = '';

		if ($this->borderLeft) {
			$border .= '┌';
		}

		if ($this->borderTop) {

			$width = $this->width;

			if(empty($width)) {
				$width = 1;
			}

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

		return $border;
	}

	private function generateBottomBorder() {
		$border = '';

		if ($this->borderLeft) {
			$border .= '└';
		}

		if ($this->borderBottom) {

			$width = $this->width;

			if(empty($width)) {
				$width = 1;
			}

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

		return $border;
	}

	private function getLineWidth() {
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

		return $width;
	}

	private function renderLine($content) {

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

		$line .= "";

		return $line;
	}

	public function getContentHeight()
	{
		return count($this->render());
	}

	public function getMaxContentHeight()
	{
		$max = $this->height;

		if($this->borderTop) {
			$max -= 1;
		}

		if($this->borderBottom) {
			$max -= 1;
		}

		if($this->paddingTop) {
			$max -= $this->paddingTop;
		}

		if($this->paddingBottom) {
			$max -= $this->paddingBottom;
		}

		return $max;
	}

	private function calculateContent() {

		$contentInfo = array();

		foreach($this->content as $index => $content) {

			$type = "fixed";

			if(is_object($content) && $content instanceof Block) {

				$content->setWidth($this->getLineWidth());

				$styleHeight = $content->getStyleHeight();
				$contentHeight = $content->getContentHeight();
				$height = 0;

				if(empty($styleHeight) OR $styleHeight == "auto") {
					$height = $contentHeight;
				} elseif(is_numeric($styleHeight)) {
					$height = $styleHeight;
				} elseif(\tuefekci\helpers\Strings::contains($styleHeight, '%')) {
					$type = "dynamic";
					$height = 0;
				} else {
					$height = 1;
				}

			} else {
				$styleHeight = 1;
				$contentHeight = 1;
				$height = 1;
			}

			$contentInfo[$type][] = array(
				'index' => $index,
				'styleHeight' => $styleHeight,
				'contentHeight' =>  $contentHeight,
				'height' => $height,
			);

		}

		$availableHeight = $this->getMaxContentHeight();

		$result = array();
		foreach($contentInfo['fixed'] as $info) {
			$availableHeight -= $info['height'];
			$result[$info['index']] = $info;
		}

		foreach($contentInfo['dynamic'] as $key => $info) {
			if(\tuefekci\helpers\Strings::contains($info['styleHeight'], '%')) {
				$contentInfo['dynamic'][$key]['height'] = floor($availableHeight * (intval($info['styleHeight']) / 100));
			}
			$result[$info['index']] = $contentInfo['dynamic'][$key];
		}

		return $result;


	}

	private function render() {

		$block = array();

		if($this->borderTop) {
			$block[] = $this->generateTopBorder();
		}

		foreach($this->content as $index => $content) {

			if(is_object($content) && $content instanceof Block) {

				$content->setWidth($this->getLineWidth());
				$content->setHeight($this->getLineWidth());

				$contentLines = $content->render();

				foreach($contentLines as $line => $contentLine) {
					$block[] = $this->renderLine($contentLine);
				}

			} elseif(is_string($content)) {
				$block[] = $this->renderLine($content);
			}


		}

		if($this->borderBottom) {
			$block[] = $this->generateBottomBorder();
		}

		return $block;

	}

	public function draw()
	{



		return print_r($this->calculateContent(), true).PHP_EOL.print_r($this->render(), true);

		$remainingHeight = $this->height;
		$remainingWidth = $this->getLineWidth();

		if($this->hasParent()) {
			//$this->calculateWidth();
			//$this->calculateHeight();
		}

		$borderTop = "";
		if($this->borderTop) {
			$borderTop = $this->generateTopBorder();
			$remainingHeight -= 1;
		}

		$borderBottom = "";
		if($this->borderBottom) {
			$borderBottom = $this->generateBottomBorder();
			$remainingHeight -= 1;
		}
		
		$contentHeights = array();
		foreach($this->content as $key => $content) {
			if(is_object($content) && $content instanceof Block) {
				$contentHeights[$key] = substr_count($content->draw(), PHP_EOL)+1;
			} elseif(is_string($content)) {
				$contentHeights[$key] = 1;
			}
		}

		$renderedLines = array();
		foreach($this->content as $key => $content) {

			//$renderedLines[] = $this->generateLine($key." - ".$contentHeights[$key]);

			if(is_object($content) && $content instanceof Block) {


				$width = 0;
				$height = 0;

				$content->setWidth($this->getLineWidth());
				//$content->setHeight($contentHeights[$key]);

				$contentLines = $content->draw();
				$contentLines = explode(PHP_EOL, $contentLines);

				// Remove last line
				unset($contentLines[array_key_last($contentLines)]);

				// Hide Overflow
				$contentLines = array_slice($contentLines, 0, $height);

				foreach($contentLines as $contentLine) {
					$renderedLines[] = $this->generateLine($contentLine);
				}

			} elseif(is_string($content)) {
				$renderedLines[] = $this->generateLine($content);
			}


		}


		$canvas = "";

		$canvasContent = array_slice($renderedLines, 0, $remainingHeight);

		if(count($canvasContent) < $remainingHeight) {
			//$canvasContent = array_pad($canvasContent, $remainingHeight, PHP_EOL);
		}

		$canvas .= implode($canvasContent, null);

		return $borderTop.$canvas.$borderBottom;

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