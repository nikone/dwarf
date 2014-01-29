<?php

class Autoloader {

  /**
   * Used in the loadGlobal method
   *
   * @var array
   */
  public static $directories = array('controllers', 'models');

  /**
   * load class file by it's full name
   *
   * @param string $class
   * @return void
   */
  public static function load($class)
  {
    $filename = __DIR__.DS.strtolower($class).'.php';
    $filename = str_replace("\\", '/', $filename);
    if(file_exists($filename))
      require $filename;
  }

  /**
   * Must be called before using any class in the application folder
   *
   * @return void
   */
  public static function loadGlobal()
  {
    if(!empty(self::$directories))
    {
      foreach (self::$directories as $directory) {
        $handle = opendir(path('app').'/'.$directory);
        while (false !== ($entry = readdir($handle))) {
          if($entry != "." && $entry != "..")
          {
            require path('app').'/'.$directory.'/'.$entry;
          }
        }
      }
      self::$directories = array();
    }
  }
}