<?php

$GLOBALS['sys'] = __DIR__.'/system';
$GLOBALS['app'] = __DIR__.'/application';

function path($path) {
  return $GLOBALS[$path];
}