<?php
include_once 'dataBase.php';

class login
{
  private $db = NULL;
  private $tokenLifeTime = 604800;//one week
  
  function __construct() {  
    $this->db = new dataBase();
  }
  
  function __destruct() {  
  }

  public function checkPass($hash, $salt, $pass)
  {
    if ( $this->db->hashPass($pass, $salt) == $hash)
      return 1;

    return 0;
  }

  public function isLogIn()
  {
    error_log('check cookie');
    if ( isset($_COOKIE['COOKIE_LOGIN']) )
    {
      error_log('we have some cookie');
      $userId = $this->db->dbGetUserByToken($_COOKIE['COOKIE_LOGIN']);
      if ( $userId )//TODO      
      {  
        error_log('user have cookie');
        return $userId;
      }
      else
      {
        $dieTime = time() - 1000;
        //TODO
        setcookie('COOKIE_LOGIN', '', $dieTime);
        return -2;
        //echo "sorry, your time is over";
      }
    }
    error_log('no cookie');
    return -1;
  }  

  public function registration($log, $pass)
  {  
    error_log('registration');
    if (isset($log) && isset($pass) ) 
    {    
      if ( !($this->db->dbUserExist($pass)) )
      {
        error_log('no user');
        $this->db->dbCreateUser($log, $pass);    
        $this->logIn($log, $pass);
      }
      else
      {
        return -2;
      }
    }
    
    return -1;
  }

  public function logIn($login, $pass)
  {
    error_log('login');
    if (isset($login) && isset($pass) ) 
    {
      /*
      $STH = $DBH->query("SELECT * FROM users WHERE mail='$login'"); //TODO or phone
      $STH->setFetchMode(PDO::FETCH_OBJ); 
      $userId = $STH->fetch();
      $userId = $userId->id;
      */
      
      $user = $this->db->dbUserExist($login);
      
      if ($user == NULL)
        return -4;
      error_log('user exist');
      $userId = $user->id;

      $this->db->dbGetPassData($userId, $hash, $salt);
      
      /*
      $STH = $DBH->query("SELECT hash, salt  FROM internalUserData WHERE userId='$userId'"); //TODO or phone
      $STH->setFetchMode(PDO::FETCH_OBJ); 
      $user = $STH->fetch();
      $hash = $user->hash;
      $salt = $user->salt;
      */
      
      if ($this->checkPass($hash, $salt, $pass))  
      {
        error_log('pass checked');
        $path = '/';
        $domain = '';// 'www.printabu.ru'
        $tokenTime = time() + $this->tokenLifeTime;
        
        setcookie('COOKIE_LOGIN', $hash, $tokenTime, $path);//, $domain, true, false);//true, true
        $this->db->dbAddToken($userId, $hash, $tokenTime);
        error_log('added token');
        
        error_log('set cookie');
        if ( isset($_COOKIE['COOKIE_LOGIN']) )
        {
          
          //echo "cookie's should be enabled";
          //TODO work with _POST token
          return -2;
        }
        error_log('NO FUCKING COOKIE');
        return 1;
      }
      else
      {
        return -3;
      }
    }
    else
      return -1;
  }

  public function logOut()
  {    
    $dieTime = time() + 3600;
    
    error_log('delete COOKIE');
    
    $tokenTime = time() + $this->tokenLifeTime;
    
    setcookie('COOKIE_LOGIN', '', $tokenTime, '/');
    //setcookie('COOKIE_LOGIN', '', $tokenTime, $path);//, $domain, true, false);//true, true
  }
}