<?php

class defPg extends api
{
  public function loadDefaultPage()
  {    
	return array('cap'	=>	"body_page/cap",
						'top'		=>	"body_page/top",
						'left'		=>	"body_page/left",
						'right'	=>	"body_page/right",
						'body'	=>	"main",
						'footer'	=>	"body_page/footer",
						'head'	=>	"body_page/head");
  }
}