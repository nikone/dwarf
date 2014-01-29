<?php
namespace core\database;

use \core\Config;

class DB{

  /**
   * The only DB object
   *
   * @access private
   * @var DB
   */
  private static $db;

  /**
   * The only PDO object
   *
   * @access private
   * @var PDO
   */
  private $dbh;

  /**
   * @access private
   */
  private function __construct()
  {
    try{

      $info = Config::$database;
      $this->dbh = new \PDO( $info['driver'].":dbname=".
                            $info['database'].";host=".
                            $info['host'],
                            $info['user'], $info['password'] );

    }catch(\PDOException $e){
      echo 'Connection failed: '. $e->getMessage()."\n";
      self::$db = null;
    }

  }

  /**
   * Allow no body to clone the instance
   *
   * @access private
   */
  private function __clone(){}

  /**
   * Get the singleton object. Must be called at least once
   * before using any static PDO methods BUT
   * Magic methods call it for the client
   *
   * @access public
   * @return DB
   */
  public static function & instance()
  {
    if(!self::$db)
    {
        self::$db = new DB;
    }
    return self::$db;
  }

  /**
   * Change database you are working on, it has to be
   * on the same server and the same user can access it
   *
   * @access public
   * @param String $database
   * @return void
   */
  public static function changeDatabase($database)
  {
    self::instance()->exec('USE '.$database);
  }

  /**
   * Magic methods to call PDO methods directly through this object
   *
   * @access public
   * @param String $method
   * @param Array $args
   */
  public function __call($method, $args = array())
  {
    return call_user_func_array(array($this->dbh, $method), $args);
  }

  /**
   * Magic methods to call PDO methods statically through this class
   *
   * @access public
   * @param String $method
   * @param Array $args
   */
  public static function __callStatic($method, $args = array())
  {
    $db = self::instance();

    return call_user_func_array(array($db->dbh, $method), $args);
  }

}