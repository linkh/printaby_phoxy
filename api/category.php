<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/phpExcel/PHPExcel.php');

class category extends api
{
  protected function add($st, $fin)
  { 
	$db = new dataBase();
	error_log('start parsing category');
	$file_adr = $_SERVER['DOCUMENT_ROOT'].'/archive/market_categories.xls';
	$PHPExcel_file = PHPExcel_IOFactory::load($file_adr);
	
	$PHPExcel_file->setActiveSheetIndex(0);
	$worksheet = $PHPExcel_file->getActiveSheet();
	
	$highestRow = $worksheet->getHighestRow();
	
	$st = max($st, 0);
	$fin = min($fin, $highestRow);
	
	for ($row = $st; $row <= $fin; ++ $row)		
	{
		$cell = $worksheet->getCellByColumnAndRow($col, $row);
		$val = $cell->getValue();
		//$val = iconv ('utf-8', 'windows-1251', $val);	
		$db->dbAddCategoryMarket($val);
	}	
	
	error_log('end parsing category');
	
	return array("design"  =>  "cart_menu",
            "result"  =>  "body_cart",
			"data"	=>	array(
				'items'	=>	$items,
				'num_items'	=> $num_items));
  }
}