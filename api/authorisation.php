<?php

class authorisation extends api
{
  protected function reserve()
  {  
  $log = new login();
  $userId = $log->isLogIn();
  $userName = $userId;//userById($userId);
  
  if (isset( $_POST['login_button']) ) 
  {  
    $log->logIn($_POST['login'], $_POST['pass']);  
  } 
  else if (isset($_POST['register_button'])) 
  {
    $log->registration($_POST['login'], $_POST['pass']);  
  }
  else if (isset($_POST['logout_button'])) 
  {
    $log->logOut();  
  }
  else 
  {
    $userId = 10;
  }
  
  return array("error"  =>  $userId, 
  "design"  =>  "body_page/right",
            "result"  =>  "body_table_right",
            "data" => array("userId" => $userId,
                        "userName" => $userName));
            
  }
}