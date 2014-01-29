<?php
namespace core\database;

class Query{

  /**
   * @access private
   * @var String $table
   */
  private $table;

  /**
   * @access private
   * @var Array $where
   */
  private $where = array();

  /**
   * @access private
   * @var int $limit
   */
  private $limit;

  /**
   * Bindings for where sql statement
   *
   * @access private
   * @var Array $bindings
   */
  private $bindings = array();

  /**
   * Constructor
   *
   * @access public
   * @param String $table
   */
  public function __construct($table)
  {
    $this->table = $table;
  }

  /**
   * Adding an element in the where array with the value
   * to the bindings
   *
   * @access public
   * @param String $key
   * @param String $oper
   * @param String $value
   * @return void
   */
  public function where($key, $oper, $value)
  {
    $this->where[]    = "AND ".$key.' '.$oper.' '."?";
    $this->bindings[] = $value;
  }

  /**
   * Adding an element in the where array with the value
   * to the bindings
   *
   * @access public
   * @param String $key
   * @param String $oper
   * @param String $value
   * @return void
   */
  public function or_where($key, $oper, $value)
  {
    $this->where[]    = "OR ".$key.' '.$oper.' '."?";
    $this->bindings[] = $value;
  }

  /**
   * Setting the limit value
   *
   * @access public
   * @param String $key
   * @param String $oper
   * @param String $value
   * @return void
   */
  public function limit($limit = 10)
  {
    $this->limit = $limit;
  }

  /**
   * Getting PDOStatement for this query
   *
   * @access public
   * @param String $columns
   * @return Array of mixed depend on fetch mode
   */
  public function get($columns = "*", $fetch_mode = PDO::FETCH_ASSOC, $class_name = '')
  {

    $sql = "SELECT ".$columns." FROM ".$this->table.
                $this->getWhere().
                $this->getLimit();

      // Prepare to be executed
    $sth = DB::prepare( $sql );

    // Set fetch mode
    if($fetch_mode == \PDO::FETCH_CLASS)

      $sth->setFetchMode( $fetch_mode, $class_name );
    else

      $sth->setFetchMode( $fetch_mode );

    // Bind parameters
    for($i = 1; $i <= count($this->bindings); $i ++)
    {
      $sth->bindParam( $i, $this->bindings[ $i - 1 ] );
    }

    // Execute the sql statement
    $sth->execute();

    // return all fetched
    return $sth->fetchAll();

  }

  /**
   * Get first element
   *
   * @access public
   * @param String $columns
   * @param int $fetch_mode use PDO Fetch constants
   * @param String $class_name
   * @return mixed depend on fetch mode
   */
  public function first($columns = '*',$fetch_mode = PDO::FETCH_ASSOC, $class_name = '')
  {
    return array_shift(
        $this->get( $columns, $fetch_mode, $class_name )
        );
  }

  /**
   * Get first element
   *
   * @access public
   * @param Array $attributes
   * @param String $id_name
   * @return void
   */
  public function save( $attributes = array(), $id_name = "id" )
  {
    // Match columns with attributes
    $attributes = $this->matchColumns( $attributes );

    // If id is given then the user request an update to an
    // existing row
    if(isset($attributes[$id_name]))

      $sql = $this->getUpdate($attributes, $id_name);

    // Else then new row will be added
    else

      $sql = $this->getInsert($attributes);

    // Prepare sql statement
    $sth = DB::prepare( $sql );

    // Execute the DB
    $sth->execute( $attributes );

  }

  /**
   * Get insert sql statement
   *
   * @access private
   * @param Array $attributes
   * @return String
   */
  private function getInsert($attributes = array())
  {
    $keys = array_keys($attributes);

    return "INSERT INTO ". $this->table .
                 "(". implode("," , $keys  ) .")".
            " VALUES(:". implode(",:", $keys  ) .")";
  }

  /**
   * Get update sql statement
   *
   * @access private
   * @param Array $attributes
   * @return String
   */
  private function getUpdate($attributes, $id_name)
  {
    $updates = '';
    foreach ($attributes as $key => $value)
    {
      $updates .= $key.'=:'.$key.',';
    }
    $updates = rtrim($updates, ",");

    return "UPDATE ". $this->table .
               " SET ".$updates.
             " WHERE ".$id_name."=:".$id_name;
  }

  /**
   * Get where statement
   *
   * @access private
   * @return String
   */
  private function getWhere()
  {
    // If where is empty return empty string
    if(empty($this->where)) return '';

    // Implode where pecices then remove the first AND or OR
    return ' WHERE '.ltrim( implode(" ", $this->where), "ANDOR" );
  }

  /**
   * Get Limit statement
   *
   * @access private
   * @return String
   */
  private function getLimit()
  {
    if(isset($limit))
      return " Limit ".$limit;
  }

  /**
   * Match the given array with the table columns
   *
   * @access private
   * @param Array $attributes
   * @return Array
   */
  private function matchColumns($attributes)
  {
    // Make new array to hold all matching between
    // table columns names and attributes keys
    $new = array();

    // Describe the table to get columns name
    $statement = DB::query('DESCRIBE '.$this->table);

    // Go through all columns
    foreach($statement->fetchAll( \PDO::FETCH_ASSOC ) as $row)
    {
      // If this column name exist in attributes array
      if(isset( $attributes[ $row['Field'] ] ))
        // put this value to the new array with its key
        $new[ $row['Field'] ] = $attributes[ $row['Field'] ];
    }

    return $new;
  }

}