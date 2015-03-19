<?php include("includes/functions.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Panel</title>
<link type="text/css" rel="stylesheet" href="styles/login.css" />
</head>

<body>

<div id="wrapper">

	<div id="login_page">
        <?php
		$manage = new management(); 
		
		if(isset($_SESSION['error']) && $_SESSION['error']!="")
		{
			?><p class="error_msg"><?php echo $_SESSION['error']; ?><br/>
            <?php if($_SESSION['adminTryNum']!=0) echo "Try number : ".$_SESSION['adminTryNum']; ?>
            </p>
			<?php
			unset($_SESSION['error']);
		}
		if(isset($_POST['id']) && isset($_POST['pass']))
		{
			if($_POST['id']=="" || $_POST['pass']=="")
			{
				$_SESSION['error'] = "User name or password is empty";
				header("location:index.php");
			}
			else
			{
				$ip = $_SERVER['REMOTE_ADDR'];
	
				$manage = new management();
				$manage->connect_db();
				$ban_check = $manage->check_ban($ip);
			
				if($ban_check == false)		/// if this ip is not in ban list
				{
					
					if($manage->login($_POST['id'],$_POST['pass']))  /// if user name and password correct
					{
						header("location:home.php");
					}
					else  		//if id and password combination is wrong
					{
						if($_SESSION['adminTryNum']==0)
						{
							$_SESSION['error'] = "You are banned for 15 minutes.<br/>You attempt to login many times with wrong admin id or password.<br/>"; 
						}
						else
						{
							$_SESSION['error'] = "Registrant ID and/or password is wrong";
						}
						header("location:index.php");
						
					}
				
				}
				else			/// if this ip is in ban list
				{
					
					$_SESSION['error'] = "You are banned for 15 minutes.<br/>You attempt to login many times with wrong admin id or password.<br/>"; 
					header("location:index.php");
					
					
				}
			}
		}
		
		?>
    	<form action="https://www.id-registry.org/backpanel/index.php" method="post">
        	<ul>
            	<li>ID</li>
                <li><input type="text" name="id" /></li>
                <li>Pass</li>
                <li><input type="password" name="pass" /></li>
                <li>&nbsp;</li>
                <li><input type="submit" value="Log In" id="buttons" /></li>
            
        	</ul>
        </form>
    </div>

</div>

</body>
</html>