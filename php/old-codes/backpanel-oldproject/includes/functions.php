<?php
if(session_id()==""){ session_start();}



class management
{
	private $rid;
	private $ip;
	private $status;

	
	public $dbhost ;
	public $dbuser ;
	public $dbpass ;
	public $dbname ;
	public $cnx;
	private $tryNum;				// counter for wrong pass or user id
	private $tryLimit;			// how many times we can enter wrong uid or pass
	private $banTime;			/// time for ban , in minutes
	
	var $error;
	var $sql;
	var $result;
	var $text;
	var $insert_id;
	
	
	
	function __construct()
	{
		$dbhost = "localhost";
		$dbuser = "eunomy";
		$dbpass = "@un0my";
		$dbname = "identity_registry";
		
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
		$this->tryLimit = 2;
		$this->banTime = 15;
		$this->status = false;
		$this->rid = NULL;
		$this->ip = NULL;
		
		if(isset($_SESSION['adminTryNum']) && $_SESSION['adminTryNum'] > 0)
			$this->tryNum = $_SESSION['adminTryNum'];
		else
		{
			$_SESSION['adminTryNum']=0;
			$this->tryNum = 0;
		}
	}

	function connect_db()
	{
		$link = mysqli_init();
		if(!$link)
		{
			die("mysqli_init failed");
		}

		if(!$link->options(MYSQLI_INIT_COMMAND, 'SET storage_engine=INNODB'))
		{
			die('Setting MYSQLI_INIT_COMMAND failed');
		}
		
		if (!$link->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
			die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
		}
		if(!$link->real_connect($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname))
		{
			die("Connect error(".mysqli_connect_errno().")".mysqli_connect_error());
		}
		$this->cnx = $link;
		$this->query("SET CHARACTER SET utf8"); // this part added lately, in case of any unexpected database behaviour you can take this part out
		return $this->cnx;
	}
	
	function query($sql)
	{
		$this->sql = $sql;
		if(!$this->result = $this->cnx->query($this->sql))
		{	
			$this->error = $this->cnx->error;
			
		}
		return $this->result;
	}
	function multi_query($sql)
	{
		$this->sql = $sql;
		if(!$this->result = $this->cnx->multi_query($sql))
		{
			$this->error = $this->cnx->error;
		}
		return $this->result;
	}
	function commit()
	{
		return $this->cnx->commit();
	}
	function rollback()
	{
		return $this->cnx->rollback();
	}
	function last_insert_id()
	{
		return $this->cnx->insert_id;
	}
	
	function escape_chars($text)
	{
		$this->text = $this->cnx->real_escape_string($text);
		return $this->text;
	}



	function setSession()
	{
		$_SESSION['rid'] = $this->rid;
		$_SESSION['ip'] = $this->ip;
		$_SESSION['adminAuth'] = $this->status;
		$_SESSION['adminTryNum'] = $this->tryNum;
	}
	function getSession()
	{
		if(isset($_SESSION['rid'])) $this->rid = $_SESSION['rid'];
		if(isset($_SESSION['ip'])) $this->ip = $_SESSION['ip'];
		if(isset($_SESSION['adminAuth'])) $this->status = $_SESSION['adminAuth'];
		if(isset($_SESSION['adminTryNum'])) $this->tryNum = $_SESSION['adminTryNum'];
	}
	
	function login($id,$pass)
	{
		if($this->tryNum < $this->tryLimit)  /// if I still have chance to try user id and pass
		{
			$id = $this->escape_chars($id);
			$pass = $this->escape_chars($pass);
			$sql = "SELECT * FROM admin WHERE admin_email = '".$id."'";
			
			$result = $this->query($sql);
			if(mysqli_num_rows($result)==1)
			{
				$data = mysqli_fetch_array($result);
				$real_pass = $this->decrypt($data['admin_pass'],$data['admin_key']);
				
				if($real_pass == $pass)
				{
					$this->rid = $data['admin_email'];
					$this->ip = $_SERVER['REMOTE_ADDR'];
					$this->status = true;
					$this->tryNum = 0;
					
					$this->setSession();
				
					return true;
				}
				else
				{
					$this->error = "Login error ";	
					$this->tryNum++;
					$_SESSION['adminTryNum'] = $this->tryNum;
					return false;
				}
				
			}
			else
			{
				$this->error = "Login error ";	
				$this->tryNum++;
				$_SESSION['adminTryNum'] = $this->tryNum;
				return false;
			}
		}
		else		/// if we enter user id and password wrong more than limit
		{
			$this->add_ban($_SERVER['REMOTE_ADDR'],$this->banTime);
		}
		
		
	}
	
	function authCheck()	// if user authanticated return true , else false
	{
		$this->getSession();
		if($this->status == true && $this->rid !=NULL)
		{
			return true;
		}
		else
			return false;
	}
	
	function check_ban($ip)
	{
		$this->query("SET storage_engine = MYISAM");
		
		$ban_sql = "SELECT * FROM banned_users WHERE user_ip = '".$ip."'";
		if($result = $this->query($ban_sql))
		{
			if(mysqli_num_rows($result) >0)
			{
				$row = mysqli_fetch_array($result);
				return $row;
			}
			else
			{
				return false;
			}
		}
	}
	function add_ban($ip,$limit)
	{
		$now = time();
		$end = $now+($limit*60);
		$_SESSION['adminTryNum'] = 0;
	
		
		$sql = "INSERT INTO banned_users (`user_ip`,`ban_date`,`end_date`) VALUES ('".$ip."','".$now."','".$end."')";
		if($this->query($sql))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	///////until here from class mysql
	
	function encrypt($text)
	{
		$secret_key = "aliefe";
		$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
	
		/* Create the IV and determine the keysize length, use MCRYPT_RAND
		 * on Windows instead */
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
		$ks = mcrypt_enc_get_key_size($td);
	
		/* Create key */
		$key = substr(md5($secret_key), 0, $ks);
		
		/*$key = $secret_key;*/
	
		/* Intialize encryption */
		mcrypt_generic_init($td, $key, $iv);
	
		/* Encrypt data */
		$encrypted = mcrypt_generic($td, $text);
	
		/* Terminate encryption handler */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = array(base64_encode($encrypted),base64_encode($iv));
		return $data;
	}
	
	function decrypt($text,$iv)
	{
		$text = base64_decode($text);
		$iv = base64_decode($iv);
		$secret_key = "aliefe";
		$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
	
		/* Create the IV and determine the keysize length, use MCRYPT_RAND
		 * on Windows instead */
		
		$ks = mcrypt_enc_get_key_size($td);
	
		/* Create key */
		$key = substr(md5($secret_key), 0, $ks);
	
		/*$key = $secret_key;*/
		/* Initialize encryption module for decryption */
		mcrypt_generic_init($td, $key, $iv);
	
		/* Decrypt encrypted string */
		$decrypted = mdecrypt_generic($td, $text);
	
		/* Terminate decryption handle and close module */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	
		/* Show string */
		return trim($decrypted);
	}
	
	function random_pass()
	{
		$code = "";
		$charset = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","_",1,2,3,4,5,6,7,8,9,0);
		$limit = count($charset)-1;
		for($i=0;$i<9;$i++)
		{
			$d = rand(0,$limit);
			$code .= $charset[$d];
		}
		return $code;
	}
	
	
	function get_ref_page($ref)
	{
		$list= explode("/",$ref);
		$page = $list[count($list)-1];
		$pos = strpos($page,"?");
		if($pos!=false)
			$page = substr($page,0,$pos); //// clear address from parameters
		return $page;
	}
	
	function pagination($page_num,$total,$limit)
	{
		
		
		$last_page = ceil($total/$limit);
		/*$page = filter_var($_SERVER['REQUEST_URI'],FILTER_SANITIZE_STRING);*/
	
		/*if(strpos($page,"?")== false)
			$page = $page."?p=";
		else
		{
			if(!$pos = strpos($page,"&p"))
				$page = $page."&p=";
			else
			{
				$page = substr($page,0,$pos);
				$page = $page."&p=";
			}
		}*/
	
		$start = $page_num-5;
		$end = $page_num +5;
	
		?>
		<div class="pagination_box">
		<ul >
		<?php
		/*echo "veriler :  page_num = ".$page_num." , total = ".$total." , last page = ".$last_page." , start = ".$start." , end = ".$end."<br/>";*/
		for($i= $start; $i <= $end; $i++)
		{
			if($i>0 && $i <= $last_page)
			{
				if($i == $page_num)
				{
					echo "<li><a href='search.php?p=".$i."'>[".$i."]</a></li>";
				}
				else
					echo "<li><a href=search.php?p='".$i."'>-".$i."-</a></li>";
				
			}
			
		}
		?>
		</ul>
		</div>
		<?php
		
	}
	
	function sendMail($from,$to,$subject,$message)
	{
		if(isset($to) && isset($subject) && isset($message))
		{
		
			if($from == NULL)
			{
				$from = "info@id-registry.org";
			}
			
										
							// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							
							// Additional headers
			$headers .= 'To: <'.$to.'>' . "\r\n";
			$headers .= 'From: <'.$from.'>' . "\r\n";
							
			
			if(mail($to, "$subject", $message,"From: $from\nContent-Type: text/html; charset=iso-8859-1"))
			{
								
				return true;

			}
			else
			{
				unset($_SESSION['adminError']);
				$_SESSION['adminError'] = "Registration email couldn't send.Please contact with system admin.<br/>";
				return false;
			}
		}
		else
		{
			unset($_SESSION['mail_error']);
			$_SESSION['mail_error'] = "Missing data.All fields should be filled";
			return false;
		}
	}
	
	function error_message($message)
	{
		echo "<div class='error_message'>".$message."</div>";
	}
	function normal_message($message)
	{
		echo "<div class='normal_message'>".$message."</div>";
	}
	
	
	
	
	function close()
	{
		/*mysqli::close();*/
		$this->cnx->close();
	}
	

	function __destruct()
	{
		/*$this->cnx->close();*/
	}
}


?>