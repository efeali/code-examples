<?php 
	include("includes/functions.php");
	$manage = new management();
	
if($manage->authCheck())
{
	include("header.php");
	
	if(isset($_POST['pass1']) && isset($_POST['pass2']))
	{
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
		if($pass1 != "" && $pass2 != "")
		{
			if($pass1 == $pass2)
			{
				$manage->connect_db();
				$pass_data = $manage->encrypt($pass1);
				$pass = $pass_data[0];
				$pass_key = $pass_data[1];
				
				$sql = "UPDATE admin SET `admin_pass` = '".$pass."', `admin_key` = '".$pass_key."' WHERE admin_email = '".$_SESSION['rid']."' LIMIT 1";
				if($manage->query($sql))
				{
					echo "<div id='main'><div class='normal_message'>Your password changed successfully.</div></div>";
				}
				else
				{
					echo "<div id='main'><div class='error_message'>Database error : Your password couldn't update.<br/>Please try again later. If you receive same error again then please contact with administrator.</div></div>";
				}
			}
			else
			{
				echo "<div id='main'><div class='error_message'>Passwords are not matching. Try again.</div></div>";
			}
		}
		else
		{
			echo "<div id='main'><div class='error_message'>Password box(es) were empty</div></div>";
		}
	}
	else
	{
	
	?>
	<script type="text/javascript">
    	function doubleCheck()
		{
			var x = document.getElementById('pass1').value;
			var y = document.getElementById('pass2').value;
			
			if( x != "" && y !="")
			{
				if(x!= y)
				{
					document.getElementById('err_msg').innerHTML = "Passwords are not matching";
					document.getElementById('err_msg').style.display = "inline";
					return false;
				}
				else
				{
					document.getElementById('err_msg').style.display = "none";
					return true;
				}
			}
			else
			{
				document.getElementById('err_msg').innerHTML = "You should enter password into both box";
				document.getElementById('err_msg').style.display = "inline";
				return false;
			}
		}
	
    </script>
	<div id="main">
     Change your password <Br/>
     <form action="changekey.php" method="post" onsubmit="return doubleCheck();">
     <ul style="list-style:none">
     	<li><span class="err_msg" id="err_msg"></span></li>
        <li>&nbsp;</li>
     	<li><label>Please type your new password</label></li>
        <li><input type="password" name="pass1" id="pass1" /> &nbsp;&nbsp;&nbsp; <span style="font-size:12px">(Max. 24 characters)</span></li>
        <li><label>Confirm your password</label></li>
        <li><input type="password" name="pass2" id="pass2" /></li>
        <li>&nbsp;</li>
        <li><input type="submit" value="Change" />
        <li>&nbsp;</li>
        <li><b style="color:red">Note:</b> Generating the new secure password might take 10-20 seconds.<br/>Please wait until you see the result message.</li>
     </ul>
     </form>
    </div>
    
    
<?php
	}
	
	include("footer.php"); 
}
else
{
	$_SESSION['error'] = "You should log in by using your Registrant ID and Password";
	header("location:../error.php");
}
?>
