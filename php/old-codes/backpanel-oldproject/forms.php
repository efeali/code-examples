<?php 
	include("includes/functions.php");
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
			$sql = "select form_id,form_url,form_thumb from forms where form_id = '".$id."'";
			if($result = $manage->query($sql))
			{
				if(mysqli_num_rows($result)==1)
				{
					$x = 0;
					$sql_del = "DELETE FROM forms WHERE form_id = '".$id."' LIMIT 1";
					if($manage->query($sql_del))
					{
						$x++;
						$data = mysqli_fetch_array($result);
						if(unlink("../forms/".$data['form_url']))
						{
							$x++;
						}
						else
						{
							$manage->error_message("Form file couldn't deleted.Please remove that manually from forms/ folder.");
						}
						if(unlink("../forms/images/".$data['form_thumb']))
						{
							$x++;
						}
						else
						{
							$manage->error_message("Form's thumbnail couldn't deleted.Please remove that manually from forms/images/ folder");
						}
						if($x==3)
							$manage->normal_message("Form deleted successfully");
						else
							$manage->error_message("Some problem occured during deleting form.");
					}
				}
				else
				{
					$manage->error_message("This form is not existed");
				}
			}
			else
			{
				$manage->error_message("Query error.");
			}
		}
	
		if(isset($_GET['done']))
		{
			$manage->normal_message("A new form uploaded succesfully!.");
			echo "<Br/>";
		}
		elseif(isset($_GET['edit-done']))
		{
			$manage->normal_message("Form edited succesfully!.");
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
			$sql = "SELECT * FROM forms WHERE form_id ='".$id."'";
			if($result = $manage->query($sql))
			{
				if(mysqli_num_rows($result)==1)
				{
					$data = mysqli_fetch_array($result);
					?>
                    <fieldset><legend>Edit Form</legend>
                    <form action="handler.php" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="action" value="form-edit" />
                        <input type="hidden" name="id" value="<?php echo $data['form_id'];?>" />
                        <table>
                            <tr><th>Form name</th><td><input type="text" class="textBox1" name="f_name" maxlength="200" size="40" value="<?php echo $data['form_title']; ?>" /></td></tr>
                            <tr><th>Description</th><td><textarea class="textBox1" name="f_description" cols="40" rows="5"><?php echo $data['form_description'];?></textarea></td></tr>
                            <tr><th>Pick a file</th><td><input type="file" class="textBox1" name="f_document" size="40" /></td></tr>
                            <tr><th>Thumbnail image of file</th><td><img src="../forms/images/<?php echo $data['form_thumb']; ?>" border="1" width="250" /><input type="file" class="textBox1" name="f_thumb" size="40" /></td></tr>
                            <tr><th>Publish this ?</th><td><input type="checkbox" name="publish" value="1" <?php if($data['form_publish']==1) echo "checked='checked'";?> /></td></tr>
                            <tr><td colspan="2"><input type="submit" value="Update the form" /></td></tr>
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
    	<fieldset><legend>Add New Form</legend>
    	<form action="handler.php" enctype="multipart/form-data" method="post">
        	<input type="hidden" name="action" value="form" />
        	<table>
            	<tr><th>Form name</th><td><input type="text" class="textBox1" name="f_name" maxlength="200" size="40" /></td></tr>
                <tr><th>Description</th><td><textarea class="textBox1" name="f_description" cols="40" rows="5"></textarea></td></tr>
                <tr><th>Pick a file</th><td><input type="file" class="textBox1" name="f_document" size="40" /></td></tr>
                <tr><th>Thumbnail image of file</th><td><input type="file" class="textBox1" name="f_thumb" size="40" /></td></tr>
                <tr><th>Publish this ?</th><td><input type="checkbox" name="publish" value="1" /></td></tr>
                <tr><td colspan="2"><input type="submit" value="Upload" /></td></tr>
            </table>
        </form>
    	</fieldset>
        <?php
		}
		?>
        <br/><br/>
        <fieldset><legend>Forms Uploaded</legend>
        <?php
			$manage->connect_db();
			$sql = "SELECT * FROM forms ORDER BY form_title ASC";
			if($result = $manage->query($sql))
			{
				?><table width="600" id="register_table">
    		<tr><th class="thtitle" colspan="4">Saved Forms</th></tr>
			<tr><th>Action</th><th>Title</th><th>Description</th><th>File</th></tr>
			<?php
				while($data = mysqli_fetch_array($result))
				{
					?><tr><td><a href="forms.php?edit=<?php echo $data['form_id']; ?>" style="font-style:italic; text-decoration:none"><img src="images/update.png" border="0" />Update</a><br/><br/><a href="forms.php?del=<?php echo $data['form_id']; ?>" style="font-style:italic;text-decoration:none" onclick="return confirm('Are you sure to delete this form?')"><img src="images/delete.png" border="0" />Delete</a></td><td><?php echo $data['form_title']; ?></td><td><?php echo $data['form_description'];?></td><td><a href="../forms/<?php echo $data['form_url']; ?>"><img src="../forms/images/<?php echo $data['form_thumb']; ?>" border="0" /></a></td></tr>
					<?php
				}
				?></table><?php
			}
			else
			{
				$manage->error_message("Form data couldn't pull from database.<br/>Try again later, if problem continues please contact with admin");
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
