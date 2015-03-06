<?php

class db
{
  private $DBH;
  private $STH;
  
  private function dbOpen($user = 'mysql', $pass = 'mysql', $db = 'digital812', $host = 'localhost')
  {      
    try//TODO
    { 
      $this->DBH = new PDO("mysql:host=$host;dbname=$db", $user, $pass);      
      return $this->DBH;
    }  
    catch(PDOException $e)
    {  
      error_log('no db');//TODO
      echo $e->getMessage();  
      return -1;
    }
    return 1;
  }
  
  private function dbClose()
  {
    $this->DBH = NULL;
  }

  function __construct() {
    $this->dbOpen();
    //$this->STH = array();
   }

  function __destruct() {
    $this->dbClose();
  }  
  
  public function get_enum_values($table, $field)
  {
    $type = $this->execute( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    foreach( explode(',', $matches[1]) as $value )
    {
       $enum[] = trim( $value, "'" );
    }
    return $enum;
  }
  
  public function prepare($quer)
  {
    $this->STH = $this->DBH->prepare($quer);      
  }
  
  public function run()//think about logic
  {
    foreach($this->STH as $quer)
    {
      $quer->execute();
      unset($this->STH[$quer]);//????
    }  
  }
  
  public function execute($quer)
  {
    //if ($this->$DBH == NULL)
      //$this->dbOpen();
      
    $num = 0;//count($this->STH);
    $this->prepare($quer);
    
    $this->STH->execute();
    //check true/false???
        
    $this->STH->setFetchMode(PDO::FETCH_ASSOC); //TODO cut
    $res = $this->STH->fetchAll();
    if (!is_object($res))
    {
      error_log('fetch_error');
      //error_log($quer);
      //error_log($res);
    }
    //unset($res);
    
    //$output = var_export($this->STH);
    error_log($quer);
    
    return $res;
  }
    
  /* TODO
  private function startTransaction()
  {
  }
  
  private function rollBack()
  {
  }
  */
}