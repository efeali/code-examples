<?php











/////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////

//////////////////////      This library is created by Ali EFE (efeali@gmail.com) 

//////////////////////                             

//////////////////////      24/10/2010

//////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(session_id()==""){ session_start();} 





include("class_mysql.php");



class authentication

{

	private $user_name;

	private $user_email;

	private $user_ip;

	private $user_id;

	private $status;		// authentication status

	private $login_date;

	private $tryNum;		// how many times login tried

	private $sec_key;

	private $iv_size;

	private $iv;

	public $ban_period;		// how many minutes will be ban

	public $error;

	





	function __construct()

	{

		$this->sec_key = "@U#r-The?faN";

		$this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);

		$this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);

		

		$ban_period = 3;

		if(isset($_SESSION['tryNum']) && $_SESSION['tryNum'] > 0)

			$this->tryNum = $_SESSION['tryNum'];

		else

			$this->tryNum = 0;

		$user_name = NULL;

		$user_email = NULL;

		$user_id = NULL;

		$user_ip = $_SERVER['REMOTE_ADDR'];

		$status = false;

	}

	

	private function setSession()

	{

		$_SESSION['tryNum'] = $this->tryNum;

		$_SESSION['admin_name'] = $this->user_name;

		$_SESSION['admin_email'] = $this->user_email;

		$_SESSION['admin_id'] = $this->user_id;

		$_SESSION['admin_ip'] = $this->user_ip;

		$_SESSION['auth'] = $this->status;

	

	}

	private function getSession()

	{

		if(isset($_SESSION['tryNum']))  $this->tryNum = $_SESSION['tryNum'];

		if(isset($_SESSION['admin_name'])) $this->user_name = $_SESSION['admin_name']; 

		if(isset($_SESSION['admin_email'])) $this->user_email = $_SESSION['admin_email'];

		if(isset($_SESSION['admin_id'])) $this->user_id = $_SESSION['admin_id'];

		if(isset($_SESSION['auth'])) $this->status = $_SESSION['auth'];

	}

	

	

	function login($email,$pass)

	{

		//ban check lazim

		if($this->tryNum > 5)

		{

			$this->error = "Too many incorrect try";

			return false;

		}

		$db = new MYSQL();

		if(!$db->connect_db())

		{

			$this->error = $db->error();

			return false;

		}

		else

		{

			$name = $db->escape_chars($email);   /// this part will be handle by other library for now

			$pass = $db->escape_chars($pass);

			$pass = $this->encrypt_text($pass);

			$sql = "SELECT * FROM admin WHERE `admin_email` = '".$email."' AND `admin_pass` = '".$pass."'";

			if($result = $db->query($sql))

			{

				if(mysqli_num_rows($result)==1)

				{

					$data = mysqli_fetch_array($result);

					$this->user_name = $data['admin_name'];

					$this->user_email = $email;

					$this->user_id = $data['admin_id'];

					$this->status = true;

					/// this part is just for audit

					$this->user_ip = $_SERVER['REMOTE_ADDR'];

					$this->login_date = date("F j, Y, g:i a");

					/// audit part end

					$this->tryNum = 0;

					

					$this->setSession();

					return true;

				}

				else

				{

					if(mysqli_num_rows($result) == 0)

					{

						$this->error = "User name and/or password wrong";

						

					}

					else

					{

						$this->error = "Duplicated user";

					}

					$this->tryNum++;

					$_SESSION['tryNum'] = $this->tryNum;

					return false;

				}

			}

			else

			{

				$this->error = $db->error();

				return false;

			}

		}

		

		

	}

	

	function authCheck()	// if user authanticated return true , else false

	{

		$this->getSession();

		if($this->status == true && $this->user_id !=NULL && is_numeric($this->user_id))

		{

			return true;

		}

		else

			return false;

		

	}

	

	function register_member($name,$lname,$email,$pass,$guest,$avatar)

	{

		$db = new MYSQL();

		if(!$db->connect_db())

		{

			$this->error = $db->error;

			return $this->error;

		}

		else

		{

			

			$name = $db->escape_chars($name);

			$lname = $db->escape_chars($lname);

			$email = $db->escape_chars($email);

			$pass = $db->escape_chars($pass);
			
			

	

			$pass = $this->encrypt_text($pass);  // maximum 32 chars should be

			$sql_find ="SELECT * FROM members WHERE member_email = '".$email."'";

			$res_find = $db->query($sql_find);



			if(mysqli_num_rows($res_find) > 0)

			{

				$this->error = "<br/><br/>This email is already registered.<br/>Please use another e-mail address.<br/>";

				return false;

			}

			else

			{

				$sql_insert = "INSERT INTO members (`member_name`,`member_lname`,`member_email`,`member_pass`,`member_guest`,`member_avatar`,`member_active`) VALUES ('".$name."','".$lname."','".$email."','".$pass."',".$guest.",'".$avatar."',1)";

				if($result = $db->query($sql_insert))

				{

					return $db->last_insert_id();

				}

				else

				{

					$this->error = $db->error;

					return false;

				}

			}



	

	

		}







	}

	

	protected function encrypt_text($text)

	{

		$crypttext = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->sec_key, $text, MCRYPT_MODE_ECB, $this->iv));

		return $crypttext;

	}

	protected function decrypt_text($text)

	{

		$crypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->sec_key, base64_decode($text), MCRYPT_MODE_ECB, $this->iv);

		return trim($crypttext);

	}

	

	function getPass($text)

	{

		$pass = $this->decrypt_text($text);

		return $pass;

	}

	function setPass($text)

	{

		$pass = $this->encrypt_text($text);

		return $pass;

	}

	

	protected function make_activation_code()

	{

		$code = "";

		$charset = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","_",1,2,3,4,5,6,7,8,9,0);

		$limit = count($charset)-1;

		for($i=0;$i<32;$i++)

		{

			$d = rand(0,$limit);

			$code .= $charset[$d];

		}

		return $code;

	}

	

	

	

	

	function __destruct()

	{

	}





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



//////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////



class management extends MYSQL

{

	public $table_header = array();

	public $table_content = array();

	public $table_style = NULL;

	var $page;

	

	function __construct()

	{

		$this->dbhost = "localhost";

		$this->dbuser = "";

		$this->dbpass = "";

		$this->dbname = "";

		$this->connect_db();



	}

	

	

	function get_ref_page($ref)

	{

		if($ref != NULL)

		{

			$list = explode("/",$ref);

			$page = $list[count($list)-1];

			$pos = strpos($page,"?");

			if($pos!=false)

				$page = substr($page,0,$pos); //// clear address from parameters

			return $page;

		}

		else

			return false;

	}

	function clean_path($ref)

	{

		if($ref != NULL)

		{

			$pos = strpos($ref,"?");

			if($pos!=false)

			{

				$page = substr($ref,0,$pos);

				return $page;

			}

			else

				return $ref;

		}

		else

			return false;

	}

	

	

	function set_table_header($data)

	{

		$this->table_header = $data;

	}

	function get_table_header()

	{

		return $this->table_header;

	}

	function add_table_content($data)

	{

		array_push($this->table_content,$data);

	}

	function get_table_content()

	{

		return $this->table_content;

	}

	

	function show_table()

	{

		?><table <?php if(!is_null($this->table_style)) echo "class='".$this->table_style."'"; ?>>

        	<tr><?php 

				$i=0;

				foreach($this->table_header as $h)

				{

					?><th <?php if($i==0) echo "width='70'"; ?> ><?php echo $h; ?></th><?php

					$i++;

				}

				?>

             </tr>

             <?php

			 	foreach($this->table_content as $key)

				{

					?><tr>

                    	<?php

							foreach($key as $d)

							{

								?><td><?php echo $d; ?></td><?php

							}

						?>

                    </tr><?php

				}

			 ?>

        </table>

        

        <?php

	}

	function clean_table()

	{

		$this->table_header = array();

		$this->table_content = array();

	}

	

	function random_password()

	{

		$pass = "";

		$charset = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","_",1,2,3,4,5,6,7,8,9,0);

		$limit = count($charset)-1;

		for($i=0;$i<8;$i++)

		{

			$d = rand(0,$limit);

			$pass .= $charset[$d];

		}

		return $pass;

	}

	

	

	function modify_member($name,$lname,$pass,$sec_q,$sec_a)

	{

		

		if($name !=NULL || $lname !=NULL || $pass !=NULL || $sec_q !=NULL || $sec_a !=NULL)

		{

			if($name!=NULL) $name = $this->escape_chars($name);

			if($lname!=NULL) $lname = $this->escape_chars($lname);

			if($pass!=NULL)

			{

				$pass = $this->escape_chars($pass);

				$pass = $this->encrypt_text($pass);  // maximum 32 chars should be

			}

			if($sec_q!=NULL) $sec_q = $this->escape_chars($sec_q);

			if($sec_a!=NULL) $sec_a = $this->escape_chars($sec_a);

			$member_id = $_SESSION['admin_id'];	

			

			

			$sql_edit = "UPDATE members SET";

			

			if($name!=NULL) 

				$sql_edit .=" `member_name` = '".$name."'";

			if($lname!=NULL)

				$sql_edit .=", `member_lname` = '".$lname."'";

			if($pass!=NULL)

				$sql_edit .=", `member_pass` = '".$pass."'";

			if($sec_q!=NULL)

				$sql_edit .=", `member_sec_ques` ='".$sec_q."'";

			if($sec_a!=NULL)

				$sql_edit .=", `member_sec_ans` = '".$sec_a."'";

			

			$sql_edit .=" WHERE member_id = ".$member_id." LIMIT 1";

			/*return $sql_edit;*/

			

			if($result = $this->query($sql_edit))

			{

				return true;

			}

			else

			{

				/*$_SESSION['error'] = $this->error;*/

				return false;

			}

		}

		else

		{

			$this->error = "You haven't update any information";

			return false;

		}

	}

	

	

	function sendMail($from,$to,$subject,$message)

	{

		

		if(isset($to) && isset($subject) && isset($message))

		{

		

			if($from == NULL)

			{

				$from = "admin@abc.com";

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

				unset($_SESSION['error']);

				$_SESSION['error'] = "Registration email couldn't send.Please contact with system admin.<br/>";

				return false;

			}

		}

		else

		{

			unset($_SESSION['error']);

			$_SESSION['error'] = "Missing data.All fields should be filled";

			return false;

		}

	}

	

	function __destruct()

	{

	}

	

	//////////////////// ADMIN FUNCTIONS ///////////////////////



	function create_index_file($path,$cat_id,$cat_name)

	{

		$result = false;

		$path = $path."/index.php";

		$content = "<?php \n \$cat_id = ".$cat_id."; \n \$current_category = ".$cat_name."; \n include(\"../../index.php\"); \n ?>";

		if(!$handle = fopen($path,"wb"))

		{

			$result = "file couldn't open";

		}

		else

		{

			if(fwrite($handle,$content)=== false)

			{

				$result = "couldn't write in file";

			}

			else

			{

				$result = 1;

				fclose($handle);

			}

		}

		return $result;

		

	}





}

//////////////////////// end of class





function pagination($page_num,$total,$limit)

{



	$last_page = ceil($total/$limit);

	$page = $_SERVER['REQUEST_URI'];



	if(strpos($page,"?")== false)

		$page = $page."?page=";

	else

	{

		if(!$pos = strpos($page,"&page"))

			$page = $page."&page=";

		else

		{

			$page = substr($page,0,$pos);

			$page = $page."&page=";

		}

	}



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

				echo "<li><a href='".$page.$i."'>[".$i."]</a></li>";

			}

			else

				echo "<li><a href='".$page.$i."'>-".$i."-</a></li>";

			

		}

		

	}

	?>

    </ul>

    </div>

    <?php



}



?>