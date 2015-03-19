<?php
if($_FILES['picture']['error']==0)
{
	echo "aldim<br/>";
	if(move_uploaded_file($_FILES['picture']['tmp_name'],"images/".$_FILES['picture']['name']))
	{
		echo "kaydettim";
	}
	else
	{
		echo "error";
	}
	
}


?>