<?php
/**
 *
 * @copyright       Copyright (c) 2021. Giacomo Tüfekci (https://www.tuefekci.de)
 * @github          https://github.com/tuefekci
 * @license         https://www.tuefekci.de/LICENSE.md
 *
 */
require_once(realpath(__DIR__ . '/../vendor/autoload.php'));


// Create Runtime
$runtime = new \tuefekci\overcli\Runtime();

// Create Window
$window = $runtime->addWindow('PIN IDENTIFICATION PROGRAM');


// Create Main Container
$container = new \tuefekci\overcli\Block("container", ['height'=>$window->getHeight(), 'width' => $window->getWidth(), 'border'=>true, 'padding-left'=>1, 'padding-right'=>1]);

// Create Block Header
$header = new \tuefekci\overcli\Block("header", ['width'=>'100%', 'top'=>0, 'border-bottom'=>1]);
$container->add($header);
$header->add($window->getTitle());

// Create Block Content
$content = new \tuefekci\overcli\Block("content", ['height'=>'100%', 'width'=>'100%']);

// Create Block Footer
$footer = new \tuefekci\overcli\Block("footer", ['width'=>'100%', 'bottom'=>0]);
$container->add($footer);




$logoBuffer = array();
$logoBuffer[] = "";
$logoBuffer[] = "PPPPP   IIIIIII   N    N";
$logoBuffer[] = "P   PP     I      NN   N IDENTIFICATION";
$logoBuffer[] = "P   PP     I      N N  N";
$logoBuffer[] = "PPPPP      I      N  N N   PROGRAM";
$logoBuffer[] = "P          I      N   NN";
$logoBuffer[] = "P       IIIIIII   N    N";
$logoBuffer[] = "";
$logoBuffer[] = "Strike a key when ready ...";
$logoBuffer[] = "";

$logo = new \tuefekci\overcli\LineBuffer();
$logo->set($logoBuffer);

$logoKeyPressed = false;

// ============================================================================
// Before each frame gets rendered lets change its content
$window->onEvent("update", function($event) use ($window, $container, $header, $content, $footer) {

	// =============================================================
	// Set window size for the case that it changed (e.g. window resized)
	$container->setHeight($window->getHeight());
	$container->setWidth($window->getWidth());
	// =============================================================



	// =============================================================
	// Clear the footer because we want to set new content. We could also set it directly via $footer->setContent(['this is the new content on the first line of footer...']);
	$footer->clear();
	$footer->add(date('Y-m-d H:i:s'));
	// =============================================================


	// =============================================================
	// Set the content of the active frame (this should be changed when the real code for frames is implemented)
	$window->setFrame($container->draw());
	// =============================================================
});
// ============================================================================

// ============================================================================
// Application main loop
// This example is raw php, but an async library like amp or react should be used if possible. 

// This var is only needed if you want to stop your process in code.

while($runtime->getState()) {
	$runtime->tick();
}
// ============================================================================

die();






$time = time();
$lastTime = time();


$outputBuffer = array();

$i = $window->getWidth()-4;


$logoBuffer = array();
$logoBuffer[] = "";
$logoBuffer[] = "PPPPP   IIIIIII   N    N";
$logoBuffer[] = "P   PP     I      NN   N IDENTIFICATION";
$logoBuffer[] = "P   PP     I      N N  N";
$logoBuffer[] = "PPPPP      I      N  N N   PROGRAM";
$logoBuffer[] = "P          I      N   NN";
$logoBuffer[] = "P       IIIIIII   N    N";
$logoBuffer[] = "";
$logoBuffer[] = "Strike a key when ready ...";
$logoBuffer[] = "";

$logo = new \tuefekci\overcli\LineBuffer();
$logo->set($logoBuffer);

//echo $buffer;
while(true) {

	if($time  <= time() - (5)){
		if($i < 3) {
		} elseif($i == 3) {
			$pin = $outputBuffer[count($outputBuffer)-1];
	
			$outputBuffer[] = $pin;
			$outputBuffer[] = $pin;
			$outputBuffer[] = "";
			$outputBuffer[] = "PIN IDENTIFICATION NUMBER: ".$pin;
			$outputBuffer[] = "";
			$i = 0;
		} else {
			$outputBuffer[] = \tuefekci\helpers\Strings::random_int($i);
			$outputBuffer[] = \tuefekci\helpers\Strings::random_int($i);
			$outputBuffer[] = \tuefekci\helpers\Strings::random_int($i);
			$i--;
		}
	} else {
		if(!$logo->isEmpty()) {
			$outputBuffer[] = $logo->getFirstAndRemove();
		}
	}

	if($time  <= time() - (30)){

		if($lastTime  <= time() - (5)){

			/*

			$quote = file_get_contents("https://animechan.vercel.app/api/random");
			$quote = json_decode($quote);

			$outputBuffer[] = $quote->quote;
			$outputBuffer[] = $quote->character." / ".$quote->anime;

			*/
			$lastTime = time();
		}
	}



	$header = '';
	$header .= '┌';
	$header .= str_repeat("─", $window->getWidth()-2);
	$header .= '┐'.PHP_EOL;
	
	$text = " ".$window->getTitle();
	
	$header .= '│'.$text;
	$header .= str_repeat(" ", ($window->getWidth()-2)-strlen($text));
	$header .= '│'.PHP_EOL;
	
	
	$header .= '├';
	$header .= str_repeat("─", $window->getWidth()-2);
	$header .= '┤'.PHP_EOL;

	$headerHeight = substr_count( $header , PHP_EOL );



	$footer = '';

	$footer .= '├';
	$footer .= str_repeat("─", $window->getWidth()-2);
	$footer .= '┤'.PHP_EOL;
	
	$textFooter = " ".date('Y-m-d H:i:s');
	
	$footer .= '│'.$textFooter;
	$footer .= str_repeat(" ", ($window->getWidth()-2)-strlen($textFooter));
	$footer .= '│'.PHP_EOL;
	
	
	$footer .= '└';
	$footer .= str_repeat("─", $window->getWidth()-2);
	$footer .= '┘';

	$buffer = "";
	$buffer .= $header;

	$contentHeight = (int) (($window->getHeight())-$headerHeight*2);
	$contentArray = array_slice($outputBuffer, -$contentHeight);

	if(count($contentArray) < $contentHeight) {
		$contentArray = array_pad($contentArray, $contentHeight, "");
	}

	$contentArray = array_values($contentArray);

	foreach($contentArray as $line) {
		$buffer .= '│ '.$line;
		$buffer .= str_repeat(" ", ($window->getWidth()-4)-strlen($line));
		$buffer .= ' │'.PHP_EOL;
	}



	//$buffer .= str_repeat("│".PHP_EOL, ($window->getHeight()-1)-$headerHeight*2);

	$buffer .= $footer;




	$window->setFrame($buffer);
	$window->draw();
	usleep(round(1000000/24));
}

