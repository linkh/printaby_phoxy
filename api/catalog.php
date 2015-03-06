<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class catalog extends api
{ 
  protected function reserve()
  {
  $db = new dataBase();
 
  $items = $db->dbGetItemByCategory(0);
  //print_r($item);
  return array("design"  =>  "catalog_def",
            "result"  =>  "body_table_center",
			"data"	=>	array("items"	=>	$items));
  }
  
  protected function show( $category)
  {
  $db = new dataBase();
 
  $items = $db->dbGetItemByCategory($category);
  $img =  $db->getImageCatalog($items);
  //print_r($item);
  return array("design"  =>  "catalog",
            "result"  =>  "body_table_center",
			"data"	=>	array("items"	=>	$items,
									"img"	=>$img));
  }
}