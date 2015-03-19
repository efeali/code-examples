<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

</head>

<body>
<?php
include("card_class.php");
include("mysql.php");



$card = new shopping_card();


if(isset($_POST['action']))
{
	if($_POST['action']=="add")
	{
		if(isset($_POST['chk']))
		{
			$chk = array_keys($_POST['chk']);
			$names = $_POST['prod_name'];
			$prices = $_POST['price'];
			$qtys = $_POST['qty'];
			if(isset($_POST['color'])) $color = $_POST['color']; else $color = NULL;
			if(isset($_POST['size'])) $size = $_POST['size']; else $size = NULL;
			
			if(isset($_POST['color'])) echo "color isset ".$_POST['color']."<br/>";
			$results = "";
			foreach($chk as $a)
			{
				/*if(is_null($color) && is_null($size))
					$add_result = $card->addToCard($a,$names[$a],$prices[$a],$qtys[$a],NULL,NULL);
				elseif(is_null($color) && !is_null($size))
					$add_result = $card->addToCard($a,$names[$a],$prices[$a],$qtys[$a],NULL,$size[$a]);
				elseif(!is_null($color) && is_null($size))
					$add_result = $card->addToCard($a,$names[$a],$prices[$a],$qtys[$a],$color[$a],NULL);
				else*/
					$add_result = $card->addToCard($a,$names[$a],$prices[$a],$qtys[$a],$color[$a],$size[$a]);
					
				if($add_result!=1)
				{
					$results .=$add_result."<br/>";
				}
			}
			if($results == "")
			{
				echo "Item(s) added successfully<br/>";
			}
			else
			{
				echo "Some items couldn't add.<br/>Error : ".$results;
			}
			
		}
		else
		{
			$add_result = $card->addToCard($_POST['prod_id'],$_POST['prod_name'],$_POST['price'],$_POST['qty'],$_POST['color'],$_POST['size']);
			
		}
		
		
	}
	if($_POST['action']=="reset")
	{
		$card->clearCard();
	}
	if($_POST['action']=="delete")
	{
		if($card->deleteItem($_POST['card_id']))
		{
			echo "Product id =".$_POST['card_id']." is deleted from card <br/>";
		}
		else
			echo "Product couldn't delete from card <br/>";
	}
}



$link = mysql_connect("localhost","root","");
mysql_select_db("northwind");

$sql = "SELECT * FROM products LIMIT 0,10";
$result = mysql_query($sql);




?>

<div style="width:500px; float:left">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="action" value="add" />
    <input type="hidden" name="items" value="" />
    <table width="500">
        <tr><th></th><th>Product ID</th><th>Product name</th><th>Price</th><th>Color</th><th>Size</th><th>Quantity</th></tr>
        
        <?php
		$i=0;
        while($row = mysql_fetch_array($result))
        {
            ?>
            <tr>
            	<td><input type="checkbox" name="chk[<?php echo $row['product_id']; ?>]" /></td>
                <td><input type="text" name="prod_id" value="<?php echo $row['product_id']; ?>" readonly="readonly" /></td>
                <td><input type="text" name="prod_name[<?php echo $row['product_id']; ?>]" readonly="readonly" value="<?php echo $row['product_name']; ?>" /></td>
                <td><input type="text" name="price[<?php echo $row['product_id']; ?>]" readonly="readonly" value="<?php echo $row['product_price']; ?>" /></td>
              <!--  <td><select name="color[<?php echo $row['product_id']; ?>]">
                		<option value="red">Red</option>
                   		<option value="blue">Blue</option>
                        <option value="yellow">Yellow</option>
                    </select></td>
                <td><Select name="size[<?php echo $row['product_id']; ?>]">
                		<option value="s">Small</option>
                        <option value="m">Medium</option>
                        <option value="l">Large</option>
                        <option value="xl">X-Large</option>
                	</Select>-->
                <td><input type="text" name="qty[<?php echo $row['product_id']; ?>]" /></td></tr>
            <?php
        }
        
        
        ?>
    
    </table>
    
    <input type="submit" value="add to cart" />
    
    </form>

</div>
<div style="width:400px; float:right">
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <input type="hidden" name="action" value="reset" />
    <input type="submit" value="reset cart" />
    
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
    <input type="submit" value="see the list" />
    </form>

</div>
<div style="float:left; clear:both">
<?php
$data = $card->getCard();
if(is_array($data) && isset($data[0]))
{
	?><table border="1">
		<tr><th>Card id</th><th>Product id</th><th>Product name</th><th>price</th><th>color</th><th>size</th><th>qty</th><th colspan="2">Action</th></tr>
	<?php
	foreach($data as $row)
	{
		?>
		<tr>
        <td><?php echo $row['card_id'];?></td>
        <td><?php echo $row['id'];?></td><td><?php echo $row['name'];?></td><td><?php echo $row['price'];?></td>
        <td><?php echo $row['color']; ?></td><td><?php echo $row['size']; ?></td>
        <td><?php echo $row['qty'];?></td>
			<td><form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
					<input type="hidden" name="action" value="add" />
					<input type="text" name="qty" size="5" />
					<input type="hidden" name="prod_id" value="<?php echo $row['id']; ?>" />
					<input type="hidden" name="prod_name" value="<?php echo $row['name']; ?>" />
					<input type="hidden" name="price" value="<?php echo $row['price']; ?>" />
                    <input type="hidden" name="color" value="<?php echo $row['color'];?>" />
                    <input type="hidden" name="size" value="<?php echo $row['size']; ?>" />
					<input type="submit" value="add" />
				 </form>
			</td>
			<td><form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
					<input type="hidden" name="action" value="delete" />
					<input type="hidden" name="card_id" value="<?php echo $row['card_id']; ?>" />
					<input type="submit" value="delete" />
				</form>
			</td>
		</tr>
		<?php
		
	}
	unset($row);  /// we need to clear row for possible future usage
	?>
    <tr><td colspan="5" align="right">Total Cost</td><td><?php echo $card->getTotalCost(); ?></td></tr>
    </table>
    <?php
}
else
{
	echo "your shopping card is empty<br/>";
	
}
?>

</div>

</body>
</html>