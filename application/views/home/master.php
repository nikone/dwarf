<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>

	<h2>Seach Products</h2>
	<form action="" method="POST">
		<input type="text" value="" name="product_name" />
		<input type="submit" value="Search" />
	</form>
	
	<br /><br />
	

	<?php

		foreach (isset($products) ? $products:array() as $product)
		{
			echo '<b>Name: </b>'.$product->title.'<br />';
			echo '<b>Description: </b>'.$product->description.'<Br /><Hr />';
		}
	
	?>

</body>
</html>