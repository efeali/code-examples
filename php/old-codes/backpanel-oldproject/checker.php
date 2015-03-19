<?php
include("includes/functions.php");
$manage = new management();

## this file is created for checking bans periodically

$sql = "SELECT * FROM banned_users";
$manage->connect_db();
if($result = $manage->query($sql))
{
	if(mysqli_num_rows($result)>0)
	{
		$now = time();
		$sql = "";
		$i = 0;
		while($data = mysqli_fetch_array($result))
		{
			if($now >= $data['end_date'])
			{
				$sql .="DELETE FROM banned_users WHERE user_ip = '".$data['user_ip']."' LIMIT 1;";
				$i++;
				
				if($del_result = $manage->multi_query($sql))
				{ 
					$message = date("F j,Y, g:i: a")." => ".$i." user(s) ban removed".PHP_EOL;
				}
				else
				{
					$message = date("F j,Y, g:i: a")." => Multi query error".PHP_EOL;
				}
			}
		}
		
	
	}
	else
	{
		$message = date("F j,Y, g:i: a")." => There is no banned user.".PHP_EOL;
	}
}
else
{
	$message = date("F j,Y, g:i: a")." => Query error".PHP_EOL;
}

if(isset($message))
{
	$fp = fopen("banlog.txt","a");
	fwrite($fp,$message);
	fclose($fp);
}
?>