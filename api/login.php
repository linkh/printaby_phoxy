<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class login extends api
{
 
  private $db = NULL;
  private $tokenLifeTime = 604800;//one week
  
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
      if ( $userId )//li      
      {  
        error_log('user have cookie');
        return $userId;
      }
      else
      {
        $dieTime = time() - 1000;
        //li
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
        $this->auth($log, $pass);
      }
      else
      {
        return -2;
      }
    }
    
    return -1;
  }

  protected function logOut()
  {    
    $dieTime = time() - 3600;
    
    error_log('delete COOKIE');
    
    //$tokenTime = time() + $this->tokenLifeTime;
    
    setcookie('COOKIE_LOGIN', '', $dieTime, '/');
    //setcookie('COOKIE_LOGIN', '', $tokenTime, $path);//, $domain, true, false);//true, true
  }
 
 protected function ejsLoginForm()
 {
	return array("design"  =>  "loginForm",
            "result"  =>  "body_login",
			"data"	=>	["userName"	=> $userName,
								'userId'	=>	$userId,
								'post'	=>	$_POST,
								'Add'	=> true]);
 }
 
 protected function ejsUserInfo($userName)
 {
	return array("design"  =>  "loginUser",
            "result"  =>  "body_login",
			"data"	=>	["userName"	=> $userName,
								'userId'	=>	$userId,
								'post'	=>	$_POST,
								'Add'	=> true]);
 }
 
 
 protected function auth()
  {
	$login = $_POST['login'];
	$pass = $_POST['pass'];
	$this->db = new dataBase();
    error_log('login');
    if (isset($login) && isset($pass) ) 
    {
      /*
      $STH = $DBH->query("SELECT * FROM users WHERE mail='$login'"); //li or phone
      $STH->setFetchMode(PDO::FETCH_OBJ); 
      $userId = $STH->fetch();
      $userId = $userId->id;
      */
      
      $user = $this->db->dbUserExist($login);
      
      if ($user == NULL)
        return -4;
      error_log('user exist');
      $userId = $user[0]['id'];	  

      $this->db->dbGetPassData($userId, $hash, $salt);
	  error_log('hash');
      error_log($hash);
	  error_log($salt);
	  error_log($userId);
	  
      /*
      $STH = $DBH->query("SELECT hash, salt  FROM internalUserData WHERE userId='$userId'"); //li or phone
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
        return 1;
      //  setcookie('COOKIE_LOGIN', $hash, $tokenTime, $path);//, $domain, true, false);//true, true
      //  $this->db->dbAddToken($userId, $hash, $tokenTime);
        error_log('added token');
        
        error_log('set cookie');
        if ( isset($_COOKIE['COOKIE_LOGIN']) )
        {
          
          //echo "cookie's should be enabled";
          //li work with _POST token
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
 
 protected function reserve()
  {
	error_log('try!');

	//error_log( print_R($_POST,TRUE) );
	
	$this->db = new dataBase();	
	
	$userId = $this->isLogIn();
	If ($userId < 1)
	{
		//$this->registration($_POST['login'], $_POST['pass']);
		//return $this->ejsLoginForm();
	}
	else
	{				
		error_log('coockie_login_real');
		return array("redirect"	=>	"#cart/finishOrder");
		//return $this->ejsUserInfo($this->db->dbGetUserById($userId));
	}
	
	//error_log('try2!');
	//	$userId = $this->registration($_POST['login'], $_POST['pass']);  
	/*
	if (isset( $_POST['login_button']) ) 
	{  
	error_log('try1!');
		$userId = $this->auth($_POST['login'], $_POST['pass']);  
	} 
	else if (isset($_POST['register_button'])) 
	{
		error_log('try2!');
		$userId = registration($_POST['login'], $_POST['pass']);  
	}
	else if (isset($_POST['logout_button'])) 
	{
		error_log('try3!');
		logOut();  
	}
	*/
  return array("design"  =>  "login",
            "result"  =>  "body_login_div",
			"data"	=>	["userName"	=> $userName,
								'userId'	=>	$userId,
								'post'	=>	$_POST,
								'Add'	=> true]);
  }
} 