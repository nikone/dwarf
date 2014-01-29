<?php

define('DS', DIRECTORY_SEPARATOR);

require __DIR__.DS.'paths.php';
require __DIR__.DS.'autoloader.php';
spl_autoload_register(array('Autoloader', 'load'));

/**
 * 1. Load shortcuts to be able to access Some classes without using there
 * full namespaces.
 * 2. load application classes ( they are considered global namespace )
 * To be accessible in all the application
 */
Autoloader::loadGlobal();

/**
 * Load user configuration options
 */
core\Config::load();

/**
 * Get a router instance and route giving the PHP_INFO if
 * is set then call the launch method to get an instance of
 * the Controller and call the specified method with the args
 */

$requestUrl = $_SERVER['REQUEST_URI'];

if(($pos = strpos($requestUrl, '?')) !== false) {
  $requestUrl =  substr($requestUrl, 0, $pos);
}

$router = new core\Router();
$router->findRoute($_SERVER['REQUEST_URI']);
$router->launch();


// If view instance is null that means the user
// didn't specify any view
if(!is_null($view = core\mvc\View::getInstance()))
  $view->launch();