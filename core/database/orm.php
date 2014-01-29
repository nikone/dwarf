<?php
namespace core\database;

abstract class ORM{

  /**
   * Holds the Query object where methods are called
   * on it through this class using two magic methods
   * __call and __callStatic
   *
   * @access private
   * @var Query
   */
  private $query;

  public function __construct()
  {
    $this->query = new Query( $this->table() );
  }

  /**
   * Get first object from the returned objects of the
   * get method
   *
   * @access public
   * @param String $columns
   * @return Object | called class object
   */
  public static function find($id, $columns = "*")
  {
    $static = new static;

    return $static->where('id', '=', $id)->first($columns);
  }

  /**
   * Get first object from the returned objects of the
   * get method
   *
   * @access public
   * @param String $columns
   * @return Object | called class object
   */
  public function first($columns = "*")
  {
    return array_shift( $this->get($columns) );
  }

  /**
   * Get Array of objects of the called class
   *
   * @access public
   * @param String $columns
   * @return Array | array of called class objects
   */
  public function get($columns = "*")
  {
    return $this->query->get(
                    $columns,
                    \PDO::FETCH_CLASS,
                    get_called_class()
                    );
  }

  /**
   * Save all this instance variables in the table
   * but first we have to matchColumns with the
   * instance
   *
   * @access public
   * @return void
   */
  public function save()
  {
    $this->query->save( (array)$this );
  }

  /**
   * If table is not specified, the plural of the class
   * name will be assumed as the table name
   *
   * @access public
   * @return String
   */
  public function table()
  {
    // If isset table attribute means the user
    // has defined the table name
    if(isset($this->table))
      return $this->table;

    return \core\helpers\Word::plural(strtolower(get_called_class()));
  }

  /**
   * Extending methods with standard PDO
   *
   * @access public
   * @param String $method
   * @param Array $args
   * @return $this
   */
  public function __call($method, $args = array())
  {
    call_user_func_array( array( $this->query, $method ), $args );

    return $this;
  }

  /**
   * Extending static methods with standard PDO
   *
   * @access public
   * @param String $method
   * @param Array $args
   * @return Object | called class object
   */
  public static function __callStatic($method, $args = array())
  {
    $static = new Static;

    call_user_func_array( array($static->query, $method) , $args );

    return $static;
  }
}