<?php
namespace core\mvc;

class View{

  /**
   * @var string
   */
  public $view_file;

  /**
   * data to be used in the view
   *
   * @var array
   */
  public $data;

  /**
   *
   * @var string
   */
  private static $view = null;

  private function __construct(){  }

  /**
   * Singleton
   *
   * @return View
   */
  public static function getInstance()
  {
    if(self::$view === null) {
      self::$view == new self();
    }

    return self::$view;
  }

  /**
   * @param string $view_file
   * @param array $data
   * @return void
   */
  public static function make($view_file, $data = array())
  {
    if(is_null(self::$view)) {
      self::$view = new View;
    }

    self::$view->view_file = $view_file;
    self::$view->data      = $data;
  }

  /**
   * That's where the actual view is required
   *
   * @return void
   */
  public function launch()
  {
    // Extract data to be usable inside the view file
    extract($this->data);

    // Expected view file format is
    // viewfolder.viewfile
    $view_file = str_replace(".", "/", $this->view_file);

    // Require view
    require path('app').'/views/'.$view_file.'.php';
  }

}