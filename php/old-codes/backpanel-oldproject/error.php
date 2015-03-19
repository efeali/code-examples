<?php
	include("includes/functions.php");
	$manage = new management();
	
if($manage->authCheck())
{

	include("header.php"); ?>
	
    
    
    <div id="middle">
    
    	<h3>There was some problem : </h3>
        <p><?php echo $_SESSION['adminError'];?></p>

    </div>


<?php include("footer.php"); 
}
else
{
	header("location:../error.php");
}
?>