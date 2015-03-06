<?php

class user_orders extends api
{ 
  protected function reserve()
  {
  return array("design"  =>  "user_orders",
            "result"  =>  "body_table_center");
  }
}