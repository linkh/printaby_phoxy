<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/phpExcel/PHPExcel.php');

class xls_parser extends api
{
  protected function reserve()
  {    
	$db = new dataBase();
	error_log('parsing price merlion');
	
	$file_adr = $_SERVER['DOCUMENT_ROOT'].'/archive/test.xlsx';
	$PHPExcel_file = PHPExcel_IOFactory::load($file_adr);
	
	$PHPExcel_file->setActiveSheetIndex(0);
	$worksheet = $PHPExcel_file->getActiveSheet();
	
	 $highestRow = $worksheet->getHighestRow();
	
	for ($row = 2; $row <= $highestRow; ++ $row)		
	{
		for ($col = 0; $col <= 6; ++ $col)		
		{
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
			$val = $cell->getValue();
			$item[$col] = $val;
		}
		$db->dbAddProvItemMrilion($item);
	}	
	
	error_log('end parsing price merlion');
	
	return array("design"  =>  "cart_menu",
            "result"  =>  "body_cart",
			"data"	=>	array(
				'items'	=>	$items,
				'num_items'	=> $num_items));
  }
}