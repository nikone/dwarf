<?php
namespace core;

class Config {

  public static $application = array();
  public static $database    = array();
  public static $routes      = array();

  /**
   * Load user Configurations
   *
   * @return void
   */
  public static function load()
  {
    self::$application = require path('app').'/config/application.php';
    self::$database    = require path('app').'/config/database.php';
    self::$routes      = require path('app').'/config/routes.php';
  }

}