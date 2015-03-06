<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class cart_menu extends api
{
  protected function reserve()
  {    
	$db = new dataBase();
	error_log('here?');
	$items = $db->dbGetTokenOrder();
	$num_items = count($items);
	error_log($num_items);
	error_log('$items');
	//print_r($items);
	error_log('/$items');
	//$str = implode(",", $num_items);	
	if (is_array($items))
		error_log('array');
	
	error_log('no here?');
	return array("design"  =>  "cart_menu",
            "result"  =>  "body_cart",
			"data"	=>	array(
				'items'	=>	$items,
				'num_items'	=> $num_items));
  }
}