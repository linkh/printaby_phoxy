<?php

class money extends api
{   
  protected function reserve()
  {
  $log = new login();
  $log->logOut();
  return array("design"  =>  "money",
            "result"  =>  "body_table_center");
  }
}