<?php 
	include_once("includes/functions.php");
	include_once("includes/fckeditor/fckeditor.php");
	$manage = new management();
	
if($manage->authCheck())
{
	include("header.php");

	?>

	<div id="main">
    <?php
		if(isset($_GET['del']) && is_numeric($_GET['del']))
		{
			$manage->connect_db();
			$id = $manage->escape_chars($_GET['del']);
			$sql = "select faq_id from faq where faq_id = '".$id."'";
			if($result = $manage->query($sql))
			{
				if(mysqli_num_rows($result)==1)
				{
					$x = 0;
					$sql_del = "DELETE FROM faq WHERE faq_id = '".$id."' LIMIT 1";
					if($manage->query($sql_del))
					{
						
						$manage->normal_message("FAQ deleted successfully");
					}
					else
					{
						$manage->error_message("FAQ couldn't deleted. Query error.");
					}
				}
				else
				{
					$manage->error_message("This faq is not existed");
				}
			}
			else
			{
				$manage->error_message("Query error");
			}
		}
	
		if(isset($_GET['done']))
		{
			$manage->normal_message("A new FAQ saved succesfully!.");
			echo "<Br/>";
		}
		elseif(isset($_GET['edit-done']))
		{
			$manage->normal_message("FAQ edited succesfully!.");
			echo "<br/>";
		}
		elseif(isset($_SESSION['error']))
		{
			$manage->error_message($_SESSION['error']);
			unset($_SESSION['error']);
			echo "<br/>";
		}
		
		if(isset($_GET['edit']) && is_numeric($_GET['edit']))
		{
			$manage->connect_db();
			$id = $manage->escape_chars($_GET['edit']);
			$sql = "SELECT * FROM faq WHERE faq_id ='".$id."'";
			if($result = $manage->query($sql))
			{
				if(mysqli_num_rows($result)==1)
				{
					$data = mysqli_fetch_array($result);
					?>
                    <fieldset><legend>Edit FAQ</legend>
                    <form action="handler.php" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="action" value="faq-edit" />
                        <input type="hidden" name="id" value="<?php echo $data['faq_id'];?>" />
                        <table>
                            <tr><th>Question</th><td><input type="text" class="textBox1" name="question" maxlength="255" size="40" value="<?php echo $data['faq_title']; ?>" /></td></tr>
                            <tr><th>Answer</th><td>
                            	<?php 
										$fckeditor = new FCKeditor('answer') ;
										$fckeditor->BasePath	= "includes/fckeditor/" ;
										$fckeditor->Width = 500;
										$fckeditor->Height = 300;
										$fckeditor->ToolbarSet = "Limited";
										$fckeditor->Value = html_entity_decode($data['faq_content'],ENT_QUOTES);
										$fckeditor->Create() ;
								?>
                            </td></tr>
                            <tr><th>Image</th><td width="250"><?php if($data['faq_image']!=NULL) echo "<img src=\"../images/".$data['faq_image']."\" border=\"0\" />"; else echo "No Image";?><br/><input type="file" name="picture" class="textBox1" /></td></tr>
                            <tr><th>Publish this ?</th><td><input type="checkbox" name="publish" value="1" <?php if($data['faq_publish']==1) echo "checked='checked'";?> /></td></tr>
                            <tr><td colspan="2"><input type="submit" value="Update FAQ" /></td></tr>
                        </table>
                    </form>
                    </fieldset>
                    <?php
				}
				else
				{
					$manage->error_message("There is no such a form existed.");
				}
			}
			else
			{
				$manage->error_message("Query error.Try again later.");
			}
	
		}
		else
		{
	
		?>
    	<fieldset><legend>Add New FAQ</legend>
    	<form action="handler.php" enctype="multipart/form-data" method="post">
        	<input type="hidden" name="action" value="faq" />
        	<table>
            	<tr><th>Question</th><td><input type="text" class="textBox1" name="question" maxlength="255" size="40" /></td></tr>
                <tr><th>Answer</th><td>
                	<?php 
							$fckeditor = new FCKeditor('answer') ;
							$fckeditor->BasePath	= "includes/fckeditor/" ;
							$fckeditor->Width = 500;
							$fckeditor->Height = 300;
							$fckeditor->ToolbarSet = "Limited";
							$fckeditor->Create() ;
					?>
                
               </td></tr>
                <tr><th>Image</th><td><input type="file" name="picture" class="textBox1" /></td></tr>
                <tr><th>Publish this ?</th><td><input type="checkbox" name="publish" value="1" /></td></tr>
                <tr><td colspan="2"><input type="submit" value="Save new FAQ" /></td></tr>
            </table>
        </form>
    	</fieldset>
        <?php
		}
		?>
        <br/><br/>
        <fieldset><legend>Saved FAQs</legend>
        <?php
			$manage->connect_db();
			$sql = "SELECT * FROM faq ORDER BY faq_id DESC";
			if($result = $manage->query($sql))
			{
				?><table width="600" id="register_table">
    		<tr><th class="thtitle" colspan="4">Saved FAQs</th></tr>
			<tr><th style="width:60px">Action</th><th style="width:100px;">Question</th><th>Answer</th></tr>
			<?php
				while($data = mysqli_fetch_array($result))
				{
					?><tr><td width="10"><a href="faq.php?edit=<?php echo $data['faq_id']; ?>" style="font-style:italic; text-decoration:none"><img src="images/update.png" border="0" />Update</a><br/><br/><a href="faq.php?del=<?php echo $data['faq_id']; ?>" style="font-style:italic;text-decoration:none" onclick="return confirm('Are you sure to delete this FAQ?')"><img src="images/delete.png" border="0" />Delete</a></td><td><?php echo $data['faq_title']; ?></td><td><?php echo substr(html_entity_decode($data['faq_content'],ENT_QUOTES),0,100);?></td></tr>
					<?php
				}
				?></table><?php
			}
			else
			{
				$manage->error_message("Data couldn't pull from database.<br/>Try again later, if problem continues please contact with admin");
			}
		?>
        
    	
            
        </fieldset>
    </div>
    
    
<?php
	include("footer.php"); 
}
else
{
	$_SESSION['adminError'] = "You should log in by using your Registrant ID and Password";
	header("location:../error.php");
}
?>
