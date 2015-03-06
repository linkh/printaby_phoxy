<?php

class user_config extends api
{ 
  protected function reserve()
  {
	return array("design"	=>	"user_config",
						"result"	=>	"body_table_center");
  }
}