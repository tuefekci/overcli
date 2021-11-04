<?php

// Include autoloader
include __DIR__."/vendor/autoload.php";

// Parse options from command line
$opts = array_merge([
    'd' => 1,    // Dithering mode : 0 = DITHER_NONE, 1 = DITHER_ERROR
    'f' => false,
    'r' => 1.0,  // Resize factor 1.0 = 100%
    'w' => 0.75, // Dither treshold weight
], getopt("f:r:w:d:ib"));

// An image file/url is required.
$opts['f'] || die("Must specify an image file.\n");

// The -i option inverts the image
$image = Pixeler\Pixeler::image($opts['f'], $opts['r'], isset($opts['i']), $opts['w'], $opts['d']);

// No colors if "-b" is passed
isset($opts['b']) && $image->clearColors();

// The Pixeler\Image instance render itself if casted to a string
echo $image;
