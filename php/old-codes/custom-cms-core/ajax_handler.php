<?php
include("class_mysql.php");
$db = new MYSQL();

//// GET SUBCATEGORIES
if(isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) 	
{
	if($db->connect_db())
	{
		$cat_id = $db->escape_chars($_GET['cat_id']);
		$sql = "SELECT * FROM subcategories WHERE cat_id ='".$cat_id."' ORDER BY subcat_name";
		$result = $db->query($sql);
		if(mysqli_num_rows($result)==0)
		{
			echo "None";
		}
		else
		{
			$data = "";
			while($row = mysqli_fetch_array($result))
			{
				$data .="<subcategory>";
				$data .="<name>".$row['subcat_name']."</name>";
				$data .="<id>".$row['subcat_id']."</id>";
				$data .="</subcategory>";
			}
			header('Content-Type: application/xml; charset=ISO-8859-1');
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
			echo "<answer>";
			echo $data;
			echo "</answer>";
		}
	}
	else
	{
		echo "";
	}
}
elseif(isset($_GET['getCat']) && is_numeric($_GET['getCat']) && isset($_GET['last']) && is_numeric($_GET['last']))
{
	if($db->connect_db())
	{
		$cat_id = $db->escape_chars($_GET['getCat']);
		$sql_posts = "SELECT * FROM posts";
		if($cat_id!=0)
			$sql_posts .=" WHERE post_cat_id = ".$cat_id." AND post_id > ".$_GET['last'];
		
		if(isset($_GET['getSub']) && is_numeric($_GET['getSub']) && $_GET['getSub']!=0)
			{
				$subcat_id = $db->escape_chars($_GET['getSub']);
				$sql_posts .=" AND post_subcat_id =".$subcat_id;
			}
			
		$sql_posts .=" ORDER BY post_date DESC LIMIT 0,10";
		
		if($result = $db->query($sql_posts))
		{
			$data = "";
			$i = 0;
			while($row = mysqli_fetch_array($result))
			{
				$data .= "<message>";
				$data .= "<postid>".$row['post_id']."</postid>";
				$data .= "<title>".$row['post_title']."</title>";
				if($i==0)
					$data .= "<message>".$row['post_content']."</message>";
				else
					$data .= "<message>".strip_tags(substr($row['post_content'],0,150))."</message>";
				$data .= "<seotitle>".$row['post_page_link']."</seotitle>";
				$data .= "<authorid>".$row['post_author_id']."</authorid>";
				$data .= "<authorname>".$row['post_author_name']."</authorname>";
				$data .= "<date>".$row['post_date']."</date>";
				$data .="</message>";
				$i++;
			}
			header('Content-Type: application/xml; charset=ISO-8859-1');
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
			echo "<posts>";
			echo "<number>".mysqli_num_rows($result)."</number>";
			echo $data;
			echo "</posts>";
		}
		else
		{
			echo "";
		}
	}
	else 
	{
		echo "Server connection problem.";
	}
}

?>