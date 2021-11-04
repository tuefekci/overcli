<?php
/**
 *
 * @copyright       Copyright (c) 2021. Giacomo Tüfekci (https://www.tuefekci.de)
 * @github          https://github.com/tuefekci
 * @license         https://www.tuefekci.de/LICENSE.md
 *
 */
require_once(realpath(__DIR__ . '/../vendor/autoload.php'));

// ==============================================================
// TEST DATA
class Test {


	public function __construct()
	{
		return $this;
	}

	public function test()
	{
		return new \Exception('Test', 200);
	}

}

$exception = new Test();
$exception = $exception->test();
// ==============================================================

// Create Main Container
$container = new \tuefekci\overcli\Block("container", ['height'=>'auto', 'width' => 100, 'border'=>true, 'padding-left'=>1, 'padding-right'=>1]);
$container->setWidth(100);

// Create Block Header
$header = new \tuefekci\overcli\Block("header", ['width'=>'100%', 'border-bottom'=>1]);
$container->add($header);
$header->add("Exception(" .$exception->getCode()."): ". $exception->getMessage());

// Create Block Content
$content = new \tuefekci\overcli\Block("content", ['height'=>'auto', 'width'=>'100%']);
$container->add($content);

$content->add("Location:");
$content->add($exception->getFile()."(".$exception->getLine().")");
$content->add("");
$content->add("Trace:");
$content->add($exception->getTraceAsString());
if(!empty($exception->getPrevious())) {
	$content->add("");
	$content->add("Previous:");
	$content->add($exception->getPrevious()->getMessage());
}

// Create Block Footer
$footer = new \tuefekci\overcli\Block("footer", ['width'=>'100%', 'border-top'=>1]);
$container->add($footer);

$footer->add(
	"Time: ".date('Y-m-d H:i:s')." | OS: ".\tuefekci\helpers\System::getOS().
	" | Load: ".number_format(\tuefekci\helpers\System::getCpuUsage(), 2).
	" | Memory: ".\tuefekci\helpers\Strings::filesizeFormatted(memory_get_usage()).
	" | Memory Peak: ".\tuefekci\helpers\Strings::filesizeFormatted(memory_get_peak_usage())
);

// Draw the Content
echo $container->draw();