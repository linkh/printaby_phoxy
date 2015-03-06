<?php
include_once 'db.php';

class dataBase extends db
{

/*
#inside function
*/
  function __construct() {
    parent::__construct();
   }
  
  function __destruct() {
    parent::__destruct();
  }
  
  private function unique_salt() 
  {  
    return substr(sha1(mt_rand()), 0, 22);
  } 

  public function hashPass($pass, $salt)
  {
    return crypt($pass, '$2a$10$'.$salt);
  }
  
/*
#-inside function
#feedback function
*/  
  public function dbAddFeedback($userId, $text)
  {
    //экранируй, блеать!!!
    
    $time = date("Y-m-d H:i:s");

    $this->execute("INSERT INTO feedback (userId, text, status, dateModify, dateCreate) values ('$userId', '$text', 'NOT_RESOLVE', '$time', '$time')");    
  }

/*
#-feedback function
#item function
*/  
  public function dbAddCategoryMarket($val)
  {
    $catArr = explode('/', $val);
	error_log('$val:');
	error_log($val);
	$num = count($catArr);	
	/*
	$catId = $this->execute("SELECT id  FROM category WHERE name='$catArr[0]'");
	$catId = $catId[0]['id'];
	if ($catId != NULL)
		$this->execute("INSERT INTO category (name, parent_id) values ('$catArr[0]', '0')");
	*/
	$prevCatId = 0;
	for($i = 0; $i < $num; $i++)
	{
		$catId = $this->execute("SELECT id  FROM category WHERE name='$catArr[$i]'"); 	 
		$catId = $catId[0]['id'];
		error_log($catId);
		if ( !is_numeric($catId) )
		{
			$prevCatId = $this->execute("INSERT INTO category (name, parent_id) values ('$catArr[$i]', '$prevCatId')");
			$prevCatId = $this->execute("SELECT id  FROM category WHERE name='$catArr[$i]'");
			$prevCatId = $prevCatId[0]['id'];
		}
		else
			$prevCatId = $catId;
		//error_log('$prevCatId:');
		//print_R($prevCatId)
		//error_log($prevCatId[0]);
	}
  }

  public function dbAddImage($type, $src, $productId)
  {
    $time = date("Y-m-d H:i:s");
    
    $this->execute("INSERT INTO productsImages (type, srcPath, productId) values ('$type', '$src', '$productId')");
  }

  public function dbAddProductImage($type, $productId, $src)
  {
    $this->execute("INSERT INTO productsImages (productId, type, srcPath) values ('$productId', '$type', '$src')");
  }
  
  public function dbAddProduct($name, $price, $src, $type = 'item', $typeImg = 'front')
  {
    $time = date("Y-m-d H:i:s");
    
    //$db->dbAddProduct('wilka-ubiytsa', 30, '/img/items/test.png');
    
    $productId = $this->execute("INSERT INTO products (type, name, price, dateModify, dateCreate) values ('$type', '$name', '$price', '$time', '$time')");
    //error_log('!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
    //error_log($productId);
    $productId = $this->execute("SELECT id FROM products WHERE dateCreate='$time' AND name='$name'");
    $this->dbAddProductImage($typeImg, $productId[0]['id'], $src);
  }

  
  public function dbGetItemById($ItemId)
  {
  error_log('try get item');
    if ($ItemId == NULL)
      return NULL;
    error_log('real try get item');
    $ItemId = $this->execute("SELECT *  FROM Items WHERE id='$ItemId'"); //li or phone    
    return $ItemId;
  }
  
  public function dbGetItemByIdMerlion($ItemId)
  {
  error_log('try get item');
    if ($ItemId == NULL)
      return NULL;
    error_log('real try get item');
    $Item = $this->execute("SELECT *  FROM provider_merlion WHERE prov_id='$ItemId'"); //li or phone    
	$itemDescr = $this->execute("SELECT *  FROM provider_merlion_items WHERE prov_id='$ItemId'"); //li or phone    
	$itemImg = $this->execute("SELECT *  FROM provider_merlion_img WHERE prov_id='$ItemId'"); //li or phone    
	$Item[0]['descr'] = $itemDescr;
	$Item[0]['img'] = $itemImg;
    return $Item;
  }
  
  public function dbGetItemByCategory($category)
  {
	error_log('try get item');
    //if ($ItemId == NULL)
      //return NULL;
    error_log('real try get item');
    $Item = $this->execute("SELECT *  FROM provider_merlion WHERE brand='$category'"); //li or phone    
    return $Item;
  }

   public function dbAddProvItemMrilion($item)
   {
	  $item[4] = substr_count($item[4], '*');
	  $this->execute("INSERT INTO provider_merlion (category, prov_id, consignment, name, brand, availible, rrc) values ('$item[0]', '$item[1]', '$item[2]', '$item[3]', '$item[4]', '$item[5], '$item[6]')");
   }
   
   public function dbAddProvItemMrilionDescr($ItemId, $descrName, $descr)
   {
	  //error_log($ItemId);
	  $this->execute("INSERT INTO provider_merlion_items (prov_id, name_descr, descr) values ('$ItemId', '$descrName', '$descr')");
   }
   
   public function dbAddProvItemMrilionImg($url, $itemId)
   {
	  //error_log($ItemId);
	  $this->execute("INSERT INTO provider_merlion_img (prov_id, adr) values ('$itemId', '$url')");
   }
  public function dbGetItemProvMerlionById($ItemId)
  {
  //error_log('try get item');
    if ($ItemId == NULL)
      return NULL;
    //error_log('real try get item');
    $ItemId = $this->execute("SELECT *  FROM provider_merlion WHERE prov_id='$ItemId'"); //li or phone    
    return $ItemId;
  }
 
/*
#-item function
#order function
*/  
  
  public function dbAddProductToOrder($userId, $productId)
  {
    $time = date("Y-m-d H:i:s");
    
    $orderId = $this->execute("SELECT id FROM orders WHERE userId='$userId' AND status='new'"); //li or phone
    
	$orderId = $orderId[0]['id'];
    if($orderId == NULL)
    {
      error_log('no_order');
      $orderId = $this->dbCreateOrder($userId);//li
    }	
    
    $this->execute("INSERT INTO productsOrders (orderId, productId) values ('$orderId', '$productId')");
  }

  public function dbCreateOrder($userId)
  {
    $time = date("Y-m-d H:i:s");
    
    $this->execute("INSERT INTO orders (userId, fullPrice, status, dateCreate) values ('$userId', '0', 'New', '$time')");    
    $orderId = $this->execute("SELECT id FROM orders WHERE userId='$userId' AND status='New'"); //li or phone
    
    return $orderId[0]['id'];
  }

  public function dbCreateOrderByToken($userId, $token)
  {
    $time = date("Y-m-d H:i:s");
    error_log('create_order');
    $this->execute("INSERT INTO orders (userId, fullPrice, status, dateCreate, userToken) values ('$userId', '0', 'New', '$time', '$token')");    
    $orderId = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'"); //li or phone
    error_log($orderId[0]['id']);
    return $orderId[0]['id'];
  }
  
  public function dbSubmitOrder()
  {
	$time = date("Y-m-d H:i:s");
    $token = $_COOKIE['COOKIE_LOGIN'];
	
    $order = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'"); //li or phone
    //error_log($order[0]['id']);
	$orderId = $order[0]['id'];
    if($orderId == NULL ||  $orderId == 0 )
    {
	  error_log($orderId);
      error_log('no_order to submit');	  
      return 0;
    }
    
    $this->execute("UPDATE orders SET status='Submit' WHERE id='$orderId'");
	return 1; 
  }
 
   
  public function dbAddToUserOrder($itemId)
  {
	$time = date("Y-m-d H:i:s");
    $token = $this->dbCreateToken();
	
	$userId = $this->dbGetUserByToken($token);
	
    $order = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'"); //li or phone
	error_log('order');
    error_log($order[0]['id']);
	$orderId = $order[0]['id'];
    if($orderId == NULL || !is_int($orderId) || $orderId == 0 )
    {
      error_log('no_order');
      $orderId = $this->dbCreateOrderbyToken($userId, $token);//li
    }
    
    $this->execute("INSERT INTO productsOrders (orderId, itemId) values ('$orderId', '$itemId')");
  }
  
  public function dbGetUserOrder($userId)
  {
    $orderId = $this->execute("SELECT id FROM orders WHERE userId='$userId' AND status='New'");
	$orderId = $orderId[0]['id'];
    $productsId = $this->execute("SELECT productId, num FROM productsOrders WHERE orderId='$orderId'");
	$productsId = $productsId[0]['id'];
    $products = $this->execute("SELECT id, name, price FROM products WHERE id='$productsId'");	
  }

  public function dbGetTokenOrder()
  {
	$token = $this->dbCreateToken();
    $orderId = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'");
	$orderId = $orderId[0]['id'];
	error_log($orderId);
	error_log('order ID');
	 if($orderId == NULL || !is_int($orderId) || $orderId == 0 )
    {
      error_log('no_order');
      $orderId = $this->dbCreateOrderbyToken($userId, $token);//li
    }
    $productsId = $this->execute("SELECT itemId FROM productsOrders WHERE orderId='$orderId'");
    $products = $this->execute("SELECT name, rrc FROM provider_merlion WHERE prov_id IN (SELECT itemId FROM productsOrders WHERE orderId='$orderId')");	
	//error_log('item ID');
	//error_log($products[0]['id']);
	return $products;
  }
  
  public function getImageOrderMerlion()
  {
	$token = $this->dbCreateToken();
	$orderId = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'");
	$orderId = $orderId[0]['id'];
	$prod = $this->execute("SELECT itemId FROM productsOrders WHERE orderId='$orderId'");
	$i = 0;
	foreach($prod as $key)
	{		
		$k = $key['itemId'];
		$img = $this->execute("SELECT adr FROM provider_merlion_img WHERE prov_id = $k LIMIT 1");
		$images[$i] = $img[0]['adr'];
		$i++;
		//error_log("$img[0]['adr'];");
		//error_log($img[0]['adr']);
	}		
	return $images;
  }
  
  public function getImageCatalog($prod)
  {
	$i = 0;
	//print_r($prod);
	foreach($prod as $key)
	{				
		$k = $key['prov_id'];
		$img = $this->execute("SELECT adr FROM provider_merlion_img WHERE prov_id = $k LIMIT 1");
		$images[$i] = $img[0]['adr'];
		$i++;
		//error_log("$img[0]['adr'];");
		//error_log($img[0]['adr']);
	}		
	return $images;
	}
  
  public function dbGetOrderByToken($token)
  {
    $orderId = $this->execute("SELECT id FROM orders WHERE userToken='$token' AND status='New'");
	$orderId = $orderId[0]['id'];
    $productsId = $this->execute("SELECT productId, num FROM productsOrders WHERE orderId='$orderId'");
	$productsId = $productsId[0]['productId'];
    $products = $this->execute("SELECT id, name, price FROM products WHERE id='$productsId'");
  }	
  
  public function dbCompleteOrder($description)
  {
    $time = date("Y-m-d H:i:s");
    
    $this->execute("UPDATE orders description=$description, status='complete', dateModify='$time'");    
  }
   
  /*
#-order function
#user function
*/ 
  public function dbCreateToken() 
  { 
	error_log('try create token');
	if ( isset($_COOKIE['COOKIE_LOGIN']) )	
	{
		error_log('token already create');
		error_log($_COOKIE['COOKIE_LOGIN']);
		$dbAnsw = $this->dbGetUserByToken($_COOKIE['COOKIE_LOGIN']);
		error_log($dbAnsw);
		error_log('db token finish');
		if ($dbAnsw != NULL)
		{
			error_log('db token exist');
			return $_COOKIE['COOKIE_LOGIN'];			
		}
	}
	
	do
	{	
		$token = substr(sha1(mt_rand()), 0, 50);
	} while($this->dbGetUserByToken($token) > 0);
	error_log('create token');
	error_log($token);
	$this->dbAddToken(0, $token, $tokenTime);
	setcookie('COOKIE_LOGIN', $token, $tokenTime, '/');//, $domain, true, false);//true, true
	return $token;
  }

  public function dbAddToken($userId, $token, $tokenTime)
  {  
    $this->execute("INSERT INTO serviceTokens (tokenBody, userId, dateCreate) values ('$token', '$userId', '$tokenTime')"); //li or phone    
  }

  public function dbGetUserByToken($token)
  {
    $token = substr($token, 0, 50);//li
    //error_log('db user answer start');
    $user = $this->execute("SELECT userId  FROM serviceTokens WHERE tokenBody='$token'"); //li or phone    
    error_log('db user answer finish');	
	error_log($user[0][userId]);
    return $user[0][userId];
  }
  
  public function dbGetUserById($userId)
  {
    if ($userId == NULL)
      return NULL;
    
    $user = $this->execute("SELECT mail  FROM users WHERE id='$userId'"); //li or phone    
    return $user[0]['mail'];
  }
   
  public function dbGetPassData($userId, &$hash, &$salt)
  {
	$data = $this->execute("SELECT hash, salt FROM users WHERE id='$userId'");
	$hash = $data[0]['hash'];
	$salt = $data[0]['salt'];
  }
    
  public function dbUserExist($login)//changeName
  {
    $user = $this->execute("SELECT * FROM users WHERE mail='$login'"); //li or phone
    
    return $user;
  }

  public function dbCreateUser($login, $pass)
  {
    date_default_timezone_set("Europe/Moscow");
    $time = date("Y-m-d H:i:s");
    //echo $time;
    //echo "<br>";
    try
    { 
      //setUser
	  $salt =$this->unique_salt();
      $hash = $this->hashPass($pass, $salt);
      $this->execute("INSERT INTO users (mail, salt, hash, dateCreate) values ('$login', '$salt', '$hash', '$time')");
      
      /*
      $STH = NULL;
      dbConnect($DBH);
      $STH = $DBH->prepare("SELECT id FROM users");
      $STH->executy();    
      $STH->setFetchMode(PDO::FETCH_CLASS);  
      $userId = $STH->fetch();
      */
      
      //$userId = $this->execute("SELECT id FROM users WHERE mail='$login'"); //li or phone
      
      
      
      //setPass
      //$this->execute("INSERT INTO internalUserData (userId, hash, salt, dateCreate) values ('$userId->id', '$hash', '$salt', '$time')");//'$userId',
    }
    catch(PDOException $e)
    {  
      //li
      //echo $e->getMessage();  
      return -1;
    }
    return 1;
  }
 /*
#-user function
*/
}  