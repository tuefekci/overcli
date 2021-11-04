# OverCLI

## Status: WIP
Be aware its WIP but you can already copy stuff you need from the repo and then use it. There are some functions which i personally really like for example the exception renderer.

## What is it?
OverCLI is a PHP CLI / TUI, Helper / Framework Library for PHP. It is a collection of useful functions and classes that can be used to build CLI and TUI applications. It also has a Render Loop which can be used to render stuff in a loop with events, like Game Engines handle the Application rendering.

## Why?
Mostly for fun. I wanted to create a library that I could use in my PHP CLI and PHP TUI applications, which has everything i need, and also combines the best of the other PHP CLI libraries i regularly use, under one namespace. 

Also it was important for me that is easy to render the results, to strings or arrays for use in other contexts like files or databases.

## How?	

## Features

## Requirements
- PHP 7.2+
- Composer

## Installation

#### Composer

---

## Examples
See the /examples folder.

---

### examples/PIN_IDENTIFICATION_PROGRAM.php 
implements a Demo Application of the T2 Movie Prop PIN IDENTIFICATION PROGRAM.
<img src="https://github.com/tuefekci/overcli/raw/main/demo/PIN_IDENTIFICATION_PROGRAM.gif" />
All media is in /demo the video is /demo/PIN_IDENTIFICATION_PROGRAM.mp4 and shows the whole example.

---

### examples/exception.php 
implements a static renderer for exceptions which can be used in other projects and logfiles.

<img src="https://github.com/tuefekci/overcli/raw/main/demo/exception.png" />

---

## Goals/TODO!
- find a proper name!
- some mini games in the examples to show how it works. 
- some more examples of how to use it.
- more animation support.
- more charts.
- better window handling and multi window support.
- support for every OS.





## License
MIT

## Contributing
Just step in, i will be happy for any contribution. I am really not a ANSI/Escape Code Guru so help me out if you can.

## Tests


## Benchmarks
- Fully dependent on the Runtime, framerate is suprisingly stable and memory usage is fine.
- Example mini games should probably solve it also porting of https://github.com/gabrielrcouto/php-terminal-gameboy-emulator would probably be a good idea.