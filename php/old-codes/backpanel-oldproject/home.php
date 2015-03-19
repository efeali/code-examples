<?php 
	include("includes/functions.php");
	$manage = new management();
	
if($manage->authCheck())
{
	include("header.php");
	?>

	<div id="main">
     Welcome to .... Admin Panel.<Br/>
     By using this panel you can create a new registrant, Modify an existed registrant, Manage published forms or add/edit/delete FAQ's.<br/><br/>
     If you are having any problem please contact with site admin or web master.
    <br/><br/><br/>
    <fieldset><legend>Change Password?</legend>
    <a href="changekey.php">Click here to change your password</a>
    </fieldset>
    </div>
    
    
<?php
	include("footer.php"); 
}
else
{
	$_SESSION['error'] = "You should log in by using your Registrant ID and Password";
	header("location:../error.php");
}
?>
