<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class cart extends api
{   
  
  protected function add()
  {  
	$itemId = $_POST['id'];
	error_log('itemId');
	error_log($itemId);
	$db = new dataBase();
	$items = $db->dbAddToUserOrder($itemId);
  }
  
  protected function reserve()
  {
  $db = new dataBase();
  $items = $db->dbGetTokenOrder();
  error_log('$items');
  //error_log($items);
  $img = $db->getImageOrderMerlion();
  error_log('$IMG____items');
  return array("design"  =>  "cart",
            "result"  =>  "body_table_center",
            "data"    =>  array(
			"items"	=>	$items,
			"items_img"	=>	$img));
  }

  protected function finish()
  {
  $db = new dataBase();
  $db->dbSubmitOrder();
  //$items = $db->dbGetTokenOrder();
  //error_log('$items');
  //error_log($items);
  return array("design"  =>  "cart_finish",
            "result"  =>  "body_table_center",
            "data"    =>  array(
			"items"	=>	$items));
  }  
}