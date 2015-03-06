<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class main extends api
{
  protected function reserve()
  {  
	$db = NULL;
	$db = new dataBase();
	$db->dbCreateToken();
	return array("design"  =>  "body_page/main",
			// "script" => "tools/jshint",
            "data" => array(
							  "pg" => array('cap'  =>  "body_page/cap",
                              'top'    =>  "body_page/top",
                              'right'  =>  "body_page/right",
                              'body'  =>  "d812",
                              'footer'  =>  "body_page/footer",
                              'head'  =>  "body_page/head")
                        ));
            
  }

  protected function home()
  {
    return $this('api', 'd812', true)->Reserve();
  }
}