<?php

include_once '../dataBase.php';

$db = new dataBase();

$db->dbAddFeedback($_POST['user_mail'], $_POST['problem']);

//$db->dbAddProduct('wilka-ubiytsa', 30, '/img/items/test.png');
//$db->dbAddProductToOrder(13, 3);