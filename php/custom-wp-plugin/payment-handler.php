<?php


define("DEBUG", 1);

// Set to 0 once you're ready to go live
define("USE_SANDBOX", 1);


define("LOG_FILE", "./ipn.log.ali");



$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
$keyval = explode ('=', $keyval);
if (count($keyval) == 2)
$myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
$get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
$value = urlencode(stripslashes($value));
} else {
$value = urlencode($value);
}
$req .= "&$key=$value";
}

// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data

if(USE_SANDBOX == true) {
$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
} else {
$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
return FALSE;
}

curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

if(DEBUG == true) {
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}



// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));


curl_setopt($ch, CURLOPT_CAINFO, getcwd()."/cacert.pem");

$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
{
if(DEBUG == true) {	
	error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
	}
	curl_close($ch);
	exit;

} 
else 
{
	// Log the entire HTTP response if debug is switched on.
	if(DEBUG == true) {
		error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
		error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
		
		// Split response headers and payload
		list($headers, $res) = explode("\r\n\r\n", $res, 2);
	}
	curl_close($ch);
}



if (strcmp ($res, "VERIFIED") == 0) {

	
	if(DEBUG == true) {
	error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
	}
	
	#################  CODE FOR SAVING TRANSACTION INTO DATABASE
	if($_POST['payment_status']== "Completed" or $_POST['payment_status'] == "Pending")
		{
		
			if($dblink = @mysqli_connect("localhost","USER","PASS","DB"))
			{
				$name = mysqli_real_escape_string($dblink, $_POST['first_name']." ".$_POST['last_name']);
				$status = mysqli_real_escape_string($dblink, $_POST['payment_status']);
				$date = date("Y-m-d h:i:s",strtotime($_POST['payment_date']));
				$gross = $_POST['mc_gross'];
				$fee = $_POST['mc_fee'];
				$email = mysqli_real_escape_string($dblink, $_POST['payer_email']);
				$txn_id = $_POST['txn_id'];
				$currency = $_POST['mc_currency'];		
				$address = mysqli_real_escape_string($dblink, $_POST['address_street']." ".$_POST['address_zip']." ".$_POST['address_city']);
				$province = mysqli_real_escape_string($dblink, $_POST['address_state']);
				$country = mysqli_real_escape_string($dblink, $_POST['address_country']);
				if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) 
					$ip = $_SERVER['REMOTE_ADDR'];
				else 
					$ip = NULL ;
				
				
				$sql = "INSERT INTO `donations`(`payer_email`, `payer_name`, `payment_date`, `payment_gross`, `payment_fee`, `payment_status`, `transaction_id`, `payment_currency`,`payer_address`,`payer_province`,`payer_country`,`payer_ip`) VALUES ('".$email."','".$name."','".$date."',".$gross.",".$fee." ,'".$status."','".$txn_id."','".$currency."','".$address."','".$province."','".$country."','".$ip."')";
				
				if(!mysqli_query($dblink, $sql))
				{
					$file = fopen("error-log.txt","a");
					$content = "--------------\r\n";
					$content .= " Date : ".date("d-m-Y h:i:s")."\r\n";
					$content .= " Txn id : ".$txn_id."\r\n";
					$content .= " Payer name : ".$name."\r\n Email : ".$email."\r\n";
					$content .= " Gross : ".$gross."\r\n Fee : ".$fee."\r\n Status : ".$status."\r\n";
					$content .= " Error : ".mysqli_error($dblink)."\r\n";
					$c¨ontent .= "--------------\r\n";
					fwrite($file, $content);
					fclose($file);	
				}
				//header("location:../thank-you/");
				die();
				
			}
			
		}
		else
		{
			$file = fopen("error-log.txt","a");
			$content = "--------------\r\n";
			$content .= " Date : ".date("d-m-Y h:i:s")."\r\n";
			$content .= " Txn id : ".$txn_id."\r\n";
			$content .= " Payer name : ".$name."\r\n Email : ".$email."\r\n";
			$content .= " Gross : ".$gross."\r\n Fee : ".$fee."\r\n Status : ".$status."\r\n";
			$content .= " Error : payment is not completed nor pending\r\n";
			$content .= "--------------\r\n";
			fwrite($file, $content);
			fclose($file);
			
			//header("location:../payment-not-completed/");
			die();
		}
	
	#################
	
} 
else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
	// Add business logic here which deals with invalid IPN messages
	if(DEBUG == true) {
	error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
	}
}

?>