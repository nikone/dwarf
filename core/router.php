<?php
namespace core;

class Router {

  public $controller;

  /**
   * @var string
   */
  public $method;

  /**
   * @var array
   */
  public $routes;

  /**
   * @var array
   */
  public $args = array();

  public function __construct()
  {
    $this->routes = Config::$routes;
  }


  /**
   * Sets the default controller and method defined in the config
   *
   * @return void
   */
  public function defaultRoute()
  {
    $this->controller = Config::$routes['default_route']['controller'];
    $this->method     = Config::$routes['default_route']['method'];
  }

  /**
   * Finds the reoute for request if no route found sets the default
   *
   * @return void
   */
  public function findRoute($requestUrl)
  {
    $this->defaultRoute();

    $url = parse_url($requestUrl);

    foreach ($this->routes as $key => $value) {
      if ('/'.$key == $url['path']) {
        $this->controller = $value['controller'];
        $this->method     = $value['method'];
        $this->args       = $url['query'];
      }
    }
  }

  /**
   * This method creates new controller from the url and return
   * whatever the method specified by the url returns
   *
   * @return void
   */
  public function launch()
  {
    // Fix Controller name and append the '_Controller'
    $controller = ucfirst($this->controller).'_Controller';

    // Check if class exists
    if(class_exists($controller)) {
      $controller = new $controller;
    } else {
      // Controller doesn't exist
      // Default error controller is created instead
      $controller = new \Error_Controller;

      // Call the index method
      return $controller->index();
    }

    // Check if the method exists in the controller
    if(method_exists($controller, $this->method)) {
      $controller->processParams($this->args);
      // Call the method giving the args array
      return call_user_func_array(array($controller, $this->method), array());
    } else {
      // Method doesn't exist
      // Default error controller is created instead
      $controller = new \Error_Controller;

      // Call the index method
      return $controller->index();
    }
  }

  private function processArgs() {
  }
}