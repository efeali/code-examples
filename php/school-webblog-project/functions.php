<?php
session_start();
require("config.php");

class mydb
{ 
	public $dblink, $dbname, $dbhost, $dbuser, $dbpass, $error;
	public $results = array(), $resultsCount;
	
	public function __construct()
	{
		$this->dbhost = DBHOST;
		$this->dbname = DBNAME;
		$this->dbpass = DBPASS;
		$this->dbuser = DBUSER;
		
		$this->connect();
	
	}
	
	public function connect()
	{
		if($this->dblink = mysqli_connect($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname))
		{
			return true;
		}
		else
		{
			$this->error = "DB ERROR : ".mysqli_error($this->dblink);
			return false;
		}
		
	}
	
	public function query($sql)
	{
		# this function will return TRUE (number) , FALSE. If there is a resultset it will populate results property
		if($result = mysqli_query($this->dblink, $sql))
		{
			if(is_object($result))
			{
				while($row = mysqli_fetch_array($result))
				{
					array_push($this->results, $row);
				}
			}
			return $result;
		}
		else
		{
			$this->error = "query error :".mysqli_error($this->dblink);
			return false;
		}
	}
	
	public function sqlEscape($content)
	{
		return mysqli_real_escape_string($this->dblink, $content);
	}
	
}// end of class mydb

######## engine
class engine extends mydb
{
	public $sessionID;
	
	public function __construct()
	{
		mydb::__construct();
		// setting up sessionID property
		if(session_id()!="")
			$this->sessionID = session_id();
		else
			$this->sessionID = NULL;
		////
		
	}
	
	public function encrypt($content) // encrypt by using sha1
	{
		$result = sha1($content);
		return $result;
	}
	
	public function setSession($arrayName, $content) // you will decide array name to use in session and it's content
	{
		if($this->sessionID != NULL)
		{
			$_SESSION[$arrayName] = $content;
			return true;
		}
		else
		{
			$this->error = "No session exist";
			return false;
		}
	}
	public function getSession($arrayName) // you will provide array name to get it's value from session
	{
		return $_SESSION[$arrayName];
	}
	
	
	public function decrypt($content)
	{
		
	}
	
	public function go2page($url) /// this will redirect user to given page
	{
		header("location:".$url);
		die("redirection failed");
	}
	
	public function userLoggedIn() ## check whether user has logged in already or not
	{
		if(isset($_SESSION['user']) && $_SESSION['user'] != NULL)
			return true;
		else
			return false;
	}
	
	public function login($name, $pass)
	{
		$name = $this->sqlEscape($name);
		$pass = $this->encrypt($this->sqlEscape($pass));
		
		$sql = "SELECT * FROM users WHERE userName = '".$name."' AND userPassword = '".$pass."'";
		if($result = $this->query($sql))
		{
			if(mysqli_num_rows($result)==1)
			{
				// everything went well so lets store 
				
				$user = array('userName' => $this->results[0]['userName'], 
				'userID' => $this->results[0]['userID'], 
				'userFName' => $this->results[0]['userFName'],
				'userLName' => $this->results[0]['userLName'], 
				'photoID'=> $this->results[0]['photoID'],
				'userEmail' => $this->results[0]['userEmail'],
				'photoFile' => $this->results[0]['filename']);
				
				if($this->setSession('user',$user)) ## here setting $_SESSION['user'] array with user details
				{
					return true;
				}
				else
				{
					$this->error = "Session error: Login failed because of session problem";
					return false;
				}
				
			}
			else
			{
				$this->error = "Wrong username and/or password";
				return false;
			}
		}
		else
		{
			$this->error = "Login query failed";
			return false;
		}
	}
	
} // end of class engine

######## users
class users extends engine
{
	public $userID, $email, $username, $fname, $lname, $photoID, $photoFile;
	
	public function addUser($uname, $pass, $fname, $lname, $email, $secQ, $secA, $photoID = NULL)
	{
		$pass = $this->encrypt($this->sqlEscape($pass));
		$this->email = $this->sqlEscape($email);
		$this->username = $this->sqlEscape($uname);
		$this->fname = $this->sqlEscape($fname);
		$this->lname = $this->sqlEscape($lname);
		$secQ = $this->sqlEscape($secQ);
		$secA = $this->sqlEscape($secA);
		
		
		$sql = "INSERT INTO users (`userPassword`,`userEmail`,`userName`,`userFName`,`userLName`,`secQuestion`,`secAnswer`,`photoID`) VALUES ('".$pass."', '".$this->email."', '".$this->username."', '".$this->fname."', '".$this->lname."', '".$secQ."', '".$secA."',";
		if($photoID == NULL)
		{
			$sql .= " NULL)";
		}
		else
		{
			$sql .= "'".$this->sqlEscape($photoID)."')";
		}
		
		
		if($this->query($sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function getUser($option, $val=NULL)
	{
		$sql = "SELECT userID,userName,userFName,userLName,userEmail,users.photoID,filename FROM users LEFT JOIN photos ON users.userID = photos.photoID";
		switch($option)
		{
			case 'all':
				break;
			case 'name':
				$sql .= " WHERE users.userName = '".$val."'";
				break;
			case 'id':
				$sql .= " WHERE users.userID = ".$val;
				break;
			
		}
		
		if($result = $this->query($sql))
		{
			$this->resultsCount = mysqli_num_rows($result);
			if($this->resultsCount ==1)
			{
				$this->userID = $this->results['userID'];
				$this->username = $this->results['userName'];
				$this->fname = $this->results['userFName'];
				$this->lname = $this->results['userLName'];
				$this->photoID = $this->results['photoID'];
				$this->email = $this->results['userEmail'];
				$this->photoFile = $this->results['filename'];
				
				return true;
			}
			else if($this->resultsCount >1)
			{
				return true; // in this case all results will be stored in $this->results , an array with records 
			}
			else
			{
				$this->error = "no record returned";
				return false;
			}
		}
		else
		{
			$this->error = "getUser query failed";
			return false;
		}
	}
	
	
	public function getUserFromId($id)
	{
		$id = $this->sqlEscape($id);
		return $this->getUser('id', $id);
		
	}
	
	public function getUserFromName($name)
	{
		$name = $this->sqlEscape($name);
		return $this->getUser('name', $name);
	}
	
	public function getUsers()
	{
		return $this->getUser('all',NULL);
	}
} // end of class users


class posts extends engine
{
	public $postID, $postContent, $postDate, $postAuthorID, $postNoComments, $postLatitude, $postLongitude, $postPhotos = array();
	
	public function addPost($content,$authorID,$latitude=NULL, $longitude=NULL) // to add a new post
	{
		$this->postContent = $this->sqlEscape($content);
		$this->postAuthorID = intval($authorID);
		$this->postLatitude = floatval($latitude);
		$this->postLongitude = floatval($longitude);
		$flag =0;
		
		$this->query("SET AUTOCOMMIT=0"); // start transaction
		
		$sql= "INSERT INTO posts (`content`,`authorID`,`locLatitude`,`locLongitude`) VALUES ('".$this->postContent."',".$this->postLatitude.",".$this->postLongitude.")";
		if($this->query($sql))
		{
			$flag++;
		}
		if($this->postID = mysqli_insert_id($this->dblink))
		{
			$sql = "INSERT INTO photos (`filename`,`postID`) VALUES ('',)";
		}
			
	}
	
	public function getPost($option,$value=NULL) // this funciton can get 'all' posts or post by id etc..
	{
		$sql = "SELECT posts.*,photos.photoID,photos.filename,photos.postID AS photoPostID,users.userName,users.userID FROM `posts` LEFT JOIN photos ON posts.postID = photos.postID LEFT JOIN users on posts.authorID = users.userID ORDER BY posts.postID DESC";
		switch($option)
		{
			case 'all': break;
		}
		if($result = $this->query($sql)) // in this case all results will be fetched into $this->results
		{
			$this->resultsCount = mysqli_num_rows($result);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getPosts()
	{
		return $this->getPost('all');
	}
	
}

?>