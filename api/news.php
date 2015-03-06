<?php

class news extends api
{
  protected function reserve()
  {    	
	return array("design"  =>  "news",
            "result"  =>  "body_table_center");
  }
}