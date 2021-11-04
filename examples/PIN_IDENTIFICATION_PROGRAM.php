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
$header = new \tuefekci\overcli\Block("header", ['width'=>'100%', 'border-bottom'=>1]);
$container->add($header);
$header->add($window->getTitle());

// Create Block Content
$content = new \tuefekci\overcli\Block("content", ['height'=>'100%', 'width'=>'100%']);
$container->add($content);

// Create Block Footer
$footer = new \tuefekci\overcli\Block("footer", ['width'=>'100%', 'border-top'=>1]);
$container->add($footer);

 

$logo = new \tuefekci\overcli\LineBuffer();
$logo->add("");
$logo->add("PPPPP   IIIIIII   N    N");
$logo->add("P   PP     I      NN   N IDENTIFICATION");
$logo->add("P   PP     I      N N  N");
$logo->add("PPPPP      I      N  N N   PROGRAM");
$logo->add("P          I      N   NN");
$logo->add("P       IIIIIII   N    N");
$logo->add("");
$logo->add("Strike a key when ready ...");
$logo->add("");

$logoKeyPressed = false;
$codeLength = 0;
$codeRepeat = 0;

// ============================================================================
// Before each frame gets rendered lets change its content
$window->onEvent("update", function($event) use ($runtime, $window, $container, $header, $content, $footer, $logo, &$logoKeyPressed, &$codeLength, &$codeRepeat) {

	// =============================================================
	// Set window size for the case that it changed (e.g. window resized)
	$container->setHeight($window->getHeight());
	$container->setWidth($window->getWidth());
	// =============================================================

	//$content->setContent($logo->get());
	if(!$logo->isEmpty()) {
		$content->add($logo->getFirstAndRemove());
	} else {


		if(($runtime->getTimeStart() + 5000) <= $runtime->getMilliseconds()) {
			$logoKeyPressed = true;
		}

		if(!$logoKeyPressed) {

			$tmpBuffer = $content->getContent();

			//$tmpBuffer[count($tmpBuffer)-2] = "Strike a key when ready ...";

			if (($window->getDrawCount() % 30) == 0) {
				$tmpBuffer[count($tmpBuffer)-2] = "Strike a key when ready .";
			}

			if (($window->getDrawCount() % 60) == 0) {
				$tmpBuffer[count($tmpBuffer)-2] = "Strike a key when ready ..";
			}

			if (($window->getDrawCount() % 90) == 0) {
				$tmpBuffer[count($tmpBuffer)-2] = "Strike a key when ready ...";
			}


			$tmpBuffer[count($tmpBuffer)-1] = "";

			$content->setContent($tmpBuffer);

		} else {

			if(!$codeLength) {
				$codeLength = $content->getLineWidth();
			}

			if($codeLength < 3) {
				sleep(15);
				die();
			} elseif($codeLength == 3) {

				$tmpBuffer = $content->getContent();
				$pin = $tmpBuffer[count($tmpBuffer)-1];
	
				$tmpBuffer[] = $pin;
				$tmpBuffer[] = $pin;
				$tmpBuffer[] = "";
				$tmpBuffer[] = "PIN IDENTIFICATION NUMBER: ".$pin;
				$tmpBuffer[] = "";

				$content->setContent($tmpBuffer);

				$codeLength = 2;

			} else {
				if (($window->getDrawCount() % 1) == 0) {

					$content->add(\tuefekci\helpers\Strings::random_int($codeLength));
					
					if($codeRepeat < 2) {
						$codeRepeat++;
					} else {
						$codeRepeat = 0;
						$codeLength--;
					}
				}
			}



		}

		//$content->add(\tuefekci\helpers\Strings::random_int(10));

	}

	// =============================================================
	// Clear the footer because we want to set new content. We could also set it directly via $footer->setContent(['this is the new content on the first line of footer...']);
	$footer->clear();
	$footer->add(
		"Time: ".date('Y-m-d H:i:s')." | OS: ".\tuefekci\helpers\System::getOS().
		" | CPU: ".number_format(\tuefekci\helpers\System::getCpuUsage(), 2).
		" | RAM: (".\tuefekci\helpers\Strings::filesizeFormatted(\tuefekci\helpers\System::getMemoryUsage())."/".\tuefekci\helpers\Strings::filesizeFormatted(\tuefekci\helpers\System::getMemory()).")".
		" | FPS: ".$window->getFPS()
	);
	// =============================================================


	// =============================================================
	// Set the content of the active frame (TODO: this should be changed when the real code for frames is implemented)
	$window->setFrame($container->draw());
	// =============================================================
});
// ============================================================================

// ============================================================================
// Application main loop
$runtime->run();

// Run only a single tick.
// $runtime->tick();
// ============================================================================