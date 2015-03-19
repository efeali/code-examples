<?php
require("functions.php");

### ADDUSER
if(isset($_GET['adduser']))
{
	if(isset($_POST['user']))
	{
		$user = json_decode($_POST['user']);
		$usrObj = new users;
		if($usrObj->addUser($user->uname,$user->upass,$user->fname,$user->lname,$user->email,$user->secQ,$user->secA))
		{
			echo '{"result":true, "msg":null}';
		}
		else
		{
			echo '{"result":false, "msg":"'.$usrObj->error.'"}';
		}
	}
	else
	{
		echo '{"result":false, "msg":"missing parameter"}';
	}
}

### GET USERS 
if(isset($_GET['getUsers']))
{
	$usrObj = new users;
	if($usrObj->getUsers())
	{
		echo json_encode(array("result"=>true, "data"=> $usrObj->results));	
	}
	else
	{
		echo json_encode(array("result"=>false, "msg"=>$usrObj->error));
		//echo '{"result":false, "msg":"'.$usrObj->error.'"}';
	}
}

### GET USER (1 user)
if(isset($_GET['getUser']))
{
	$usrObj = new users;
	if(isset($_POST['uid']) && is_numeric($_POST['uid']))
	{
		if($usrObj->getUserFromId($_POST['uid']))
		{
			echo json_encode(array("result"=>true, "data"=>$usrObj->results));
		}
		else
		{
			echo json_encode(array("result"=>false, "msg"=>$usrObj->error));
			//echo '{"result":false, "msg":"'.$usrObj->error.'"}';
		}
	}
}

### LOGIN
if(isset($_GET['login']))
{
	if(isset($_POST['u'], $_POST['p']))
	{
		$siteEngine = new engine;
		if($siteEngine->login($_POST['u'],$_POST['p'])) // if login successfull
		{
			echo json_encode(array("result"=>"panel.php", "uid"=>$_SESSION['user']['userID']));
		}
		else
		{
			echo json_encode(array("result"=>false));
		}
	}
}

### LOGOUT
if(isset($_GET['logout']))
{
	$_SESSION = array();
	session_destroy();
	echo 1;
}


### ADD A NEW POST
############################# this should be moved into functions.php file as addPost function
if(isset($_GET['addPost']))
{
	if(isset($_POST['postContent'], $_POST['postAuthorID'],$_POST['postLat'],$_POST['postLong']))
	{
		$postObj = new posts;
		$content = $postObj->sqlEscape($_POST['postContent']);
		$authorID = $_POST['postAuthorID'];
		$flag =false;
		$postObj->query("set autocommit=0");
        if($_POST['postLat']==null)
            $postLat = "null";
        else
            $postLat = $_POST['postLat'];
        if($_POST['postLong']==null)
            $postLong = "null";
        else
            $postLong = $_POST['postLong'];

		$sql = "INSERT INTO posts (`content`,`authorID`,`locLatitude`,`locLongitude`) VALUES ('".$content."',
		".$authorID.",".$postLat.",".$postLong.")";
		if($postObj->query($sql))
		{
			$flag = true;
            if(isset($_FILES['postPhoto'])) // if there is a file
            {
                if($_FILES['postPhoto']['error']==0)
                {
                    $filename = $_FILES['postPhoto']['name'];
                    $extension = pathinfo($filename,PATHINFO_EXTENSION);
                    $newFileName = $authorID."-".time().".".$extension;
                    $tmpname = $_FILES['postPhoto']['tmp_name'];
                    $postID = mysqli_insert_id($postObj->dblink);



                    if(move_uploaded_file($tmpname,"../photos/".$newFileName))
                    {
                        if(include_once("pic_resize_class.php"))
                        {
                            $picResize = new pic_resize();
                            $picResize->load("../photos/".$newFileName);
                            if($picResize->getWidth()>=$picResize->getHeight())
                            {
                                if($picResize->getWidth()>1000)
                                    $picResize->resizeToWidth(1000);
                            }
                            else
                            {
                                if($picResize->getHeight()>1000)
                                    $picResize->resizeToHeight(800);
                            }

                            $picResize->save("../photos/".$newFileName,$picResize->image_type);
                        }



                        $sql= "INSERT INTO photos (`filename`,`postID`) VALUES ('".$newFileName."',".$postID." )";
                        if($postObj->query($sql))
                        {
                            $flag = true;
                        }
                        else
                        {
                            $postObj->error = "Photo saving error : ".$postObj->error;
                            $flag = false;
                        }
                    }
                    else
                    {
                        $postObj->error = "Photo couldn't saved";
                        $flag = false;
                    }
                }
                else
                {
                    $postObj->error = "File upload error";
                    $flag = false;
                }
            } // end of if there is a file
		}
        else
        {
            $flag = false;
            $postObj->error = "query error ".$sql;
        }
		if($flag === true)
		{
			$postObj->query("commit");
			echo json_encode(array("result"=>true, "msg"=>"Post was saved"));
		}
		else
		{
			$postObj->query("rollback");
			echo json_encode(array("result"=>false, "msg"=>$postObj->error));
		}
	}
    else
    {
        echo json_encode(array("result"=>false, "msg"=>"missing parameter"));
    }
}

### GET ALL POSTS 
if(isset($_GET['getPosts']) && $_GET['getPosts']==true)
{
	$postObj = new posts;
	if($postObj->getPosts())
	{
		echo json_encode(array("result"=>true, "data"=> $postObj->results));	
	}
	else
	{
		echo json_encode(array("result"=>false,"msg"=> $postObj->error));
	}
}

?>