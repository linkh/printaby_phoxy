<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class d812 extends api
{
  protected function reserve()
  {    	
  $db = new dataBase();
  $items = $db->dbGetItemByCategory('ASUS');
  $img =  $db->getImageCatalog($items);
	return array("design"  =>  "d812",
            "result"  =>  "body_table_center",
					"data"	=>	array("items"	=>	$items,
									"img"	=>$img));
  }
}