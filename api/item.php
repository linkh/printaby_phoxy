<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/tools/dataBase.php');

class item extends api
{ 	
	protected function get_page($url,$post='',$ref='',$cookie='',$ua="Opera 9.64 (compatible; MSIE 6.0; Windows NT 5.1; ru)",$proxy='') 
	{
    $ch = curl_init();
 
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$ua);
    curl_setopt($ch, CURLOPT_REFERER,$ref);
    curl_setopt($ch, CURLOPT_PROXY , $proxy);
 
    if($post!==''){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
 
    $headers [] = "Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/jpeg, image/gif, image/x-xbitmap, */*;q=0.1";
    $headers [] = "Accept-Language: ru,en;q=0.9,ru-RU;q=0.8";
    $headers [] = "Connection: close";
    $headers [] = "Cache-Control: no-store, no-cache, must-revalidate";
 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 1); // тут лучше поставить 0, если куки не нужны
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    @curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    if($result)return $result; else    return false;
}
 
	protected function get_cookie($page) 
	{ // и бонус, для парсинга кукисов
		if(preg_match("|Set-Cookie: (.*)\n|Uis",$page,$rnd)) return $rnd[1];	
		else return false;
	} 
	
  protected function getImages($html, $itemId) 
  {
		$db = new dataBase();
		$matches = array();
		$regex = '@http:\/\/img.merlion.ru\/items\/(.*?).jpg@';
		preg_match_all($regex, $html, $matches);
		echo("matches:");
		print_r($matches);
		foreach ($matches[1] as $img) {
			$this->saveImg($img);
			$url = 'http://img.merlion.ru/items/'.$img.'.jpg';
			$db->dbAddProvItemMrilionImg($url, $itemId);
		}
	}

	protected function saveImg($name) {
		$url = 'http://img.merlion.ru/items/'.$name.'.jpg';
		error_log('url:');
		error_log($url);
		//$data = $this->get_page($url);
		//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/img/items/'.$name.'.jpg', $data);

		$ch = curl_init($url);
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].'/img/items/'.$name.'.jpg', 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
}
	
  protected function addMerlion($itemId = 980609)
  {
  $db = new dataBase();
  error_log('add itemID:'.$itemId);
  //$ItemId = 2342;
 
 /*
  $ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://merlion.com/catalog/product/".$itemId);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$str = curl_exec ($ch);
	curl_close ($ch);
	*/
	error_log('start curl');
	
	$str = $this->get_page("http://merlion.com/catalog/product/".$itemId);
	
	//print_r($str); 
	//$u = 'http://merlion.com/catalog/product/'.$itemId.'/photolist/';
	//echo($u);
	$html = $this->get_page('http://merlion.com/catalog/product/'.$itemId.'/photolist/');
	error_log('get img Page');
    $this->getImages($html, $itemId);
	
	error_log('finish curl');
	$str = iconv ('windows-1251', 'utf-8', $str);	
	preg_match("@<div class='product_titel'>.*<\/div>@siU", $str, $name);
	$name = str_replace("<div class='product_titel'>", '', $name);
	$name = str_replace("</div>", '', $name);
	if($name != NULL)
	{
	
		preg_match('@<table cellpadding="4" cellspacing="0" border="0" class="properties">.*<\/table>@si', $str, $table);	
		preg_match_all('@<td.*>.*</td>@isU', $table[0], $arr);	
		
		foreach ($arr as &$key)
		{
			$key = str_replace('<td style="border-left:1px solid #ffffff;"><div style="margin: 0 0 0 20px;">', '', $key);
			$key = str_replace('<td width="40%">', '', $key);
			$key = str_replace('</td>', '', $key);
			$key = str_replace('</div>', '', $key);
			$key = str_replace('<td>', '', $key);
			$key = str_replace(array("\r\n", "\r", "\n"), '', $key); 
			//$key = strip_tags($key);
			$key = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $key);
			//$key = str_replace('  ',' ', $key);
			//$key = ltrim($key);		
			//$table = trim($table[0]);
			//$table = strip_tags($table);
		};		
		$arr = array_map("trim", $arr[0]);
		
		$num = count($arr);
		
		for($i = 0; $i < $num; $i = $i + 2)
		{
			$db->dbAddProvItemMrilionDescr($itemId, $arr[$i], $arr[$i + 1]);
		}
	}
	//$name = iconv ('windows-1251', 'utf-8', $name[0]);
	//$n = ord($arr[0][0]);
	//print($n);
	//print_r($arr);
	
	error_log('finish preg 1');
    $item = $db->dbGetItemProvMerlionById($itemId);
	//print_r($item);
	//echo($item[0]['name']);
	//echo(substr($name[0], 0, 121));
	
	//if(strcasecmp(substr($name[0], 0, 121), $item[0]['name']) == 0)
	
	
    return array("design"  =>  "item_add",
            "result"  =>  "body_table_center",
			"data"	=>	array("item"	=>	$name));
  }
  
  protected function addAll()
  {
	$db = new dataBase();
	$items = $db->dbGetItemByCategory(0);
	//print_R($items);
	
	foreach($items as $item)
	{
		error_log($item['prov_id']);
		$this->addMerlion($item['prov_id']);
	}
	return array("design"  =>  "item",
            "result"  =>  "body_table_center");
  }
  
  protected function showimg($img, $itemId)
  {
	$db = new dataBase();
	$item = $db->dbGetItemByIdMerlion($itemId);
	error_log("ITEMIMG:".$itemId);
	return array("design"  =>  "itemImg",
            "result"  =>  "item_cur_img",
			"data"	=>	array("img"	=>	$img,
										"item"	=>	$item[0]));
  }

  protected function reserve($itemId = 980609)
  {
  $db = new dataBase();
  error_log('itemID:'.$itemId);
  //$ItemId = 2342;
 
  $item = $db->dbGetItemByIdMerlion($itemId);
  //print_r($item);
  return array("design"  =>  "item",
            "result"  =>  "body_table_center",
			"data"	=>	array("item"	=>	$item[0],
											"itemId"	=>	$itemId));
  }
}