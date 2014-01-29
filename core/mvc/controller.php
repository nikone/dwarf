<?php

namespace core\mvc;

abstract class Controller {

  /**
   * @var array
   */
  public $params  = array();


	public function __construct(){ }

  /**
   * Recives params as raw url and process them before
   * sending them to addParams
   *
   * @return void
   * @param String $params
   */
  public function processParams($params)
  {
    $url_parts = explode("&", $params);
    foreach ($url_parts as $url_part) {
      $raw_param = explode("=", $url_part);
      $parameter = $raw_param[0];
      $value     = $raw_param[1];

      $this->addParam($parameter, $value);
    }
  }

  /**
   * Appends params to the params associative array
   *
   * @return void
   * @param String $param_name
   * @param String $param_value
   */
  private function addParam($param_name, $param_value)
  {
    $this->params[$param_name] = $param_value;
  }

}