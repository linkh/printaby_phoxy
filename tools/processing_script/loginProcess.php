<?php

include_once '../login.php';
$log = new login();
/*
if ($log->isLogIn() > 0 )
{
  return;
}
*/
//register_button

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
    return;
}