<?php

$card = array();
$item1 = array("id"=>1,"name"=>"toy","price"=>15,"qty"=>3);
$item2 = array("id"=>2,"name"=>"towel","price"=>10,"qty"=>2);
$item3 = array("id"=>1,"name"=>"toy","price"=>25,"qty"=>3);

array_push($card,$item1);
array_push($card,$item2);
array_push($card,$item3);
/*
echo "<pre>";
var_dump($card);
echo "</pre>";*/

$error = -1;
$result = array();

/*  if($key = array_search("toy",$card[0]))*/
for($i=0; $i<count($card); $i++)
{
	if(array_keys($card[$i],"toy"))
	{
		array_push($result,$i);	
		$error = $i;
	}
	
}
if($error !=-1)
{
	echo "<pre>";
			var_dump($card);
			echo "</pre>";
			
	echo "<pre>";
			var_dump($result);
			echo "</pre>";
}

/*if(
	if($key = array_keys($card[0],"toy"))
		{
			echo "<pre>";
			var_dump($key);
			echo "</pre>";
		}
else
	echo "bulamadim mina koyim";*/
?>