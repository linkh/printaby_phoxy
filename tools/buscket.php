<?php



function order($img, $size, $num, $price)
{
$img = '/img/2.jpg';
$size = 'L';
$num = 1;
$price = 350;
echo"
	<tr>
		<td><img src = $img></td>
		<td><p>$size</p></td>
		<td><input type = 'number' value = '$num' min='0' max='100' step='1'></td>
		<td>$price</td>
		<td>Х</td>
	</tr>
";
}

function buscket()
{
echo"
	<table class = 'cart_table'>
	<tr>
		<td>
			<p>Картинка</p>
		</td>
		<td>
			<p>Размер</p>
		</td>
		<td>
			<p>Количество</p>
		</td>
		<td>
			<p>Цена</p>
		</td>
	</tr>
";
	order($img, $size, $num, $price);
echo"
</table>
	<div class = 'menu_div'><p>Отправить заказ</p></div>
";
}