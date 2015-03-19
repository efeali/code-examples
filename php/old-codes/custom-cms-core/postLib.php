<?php

function postFilter()
{
	return true;
}

function sanitizePost($title,$msg)
{
	$msg = htmlentities($msg,ENT_QUOTES);
	if($title != NULL)
		$title = addslashes($title);
	$data = array($title,$msg);
	return $data;
}

function seoTitle($phrase, $maxLength)
{
	$result = strtolower($phrase); 
	$result = preg_replace("/[^a-z0-9\s-]/", "", $result); 
	$result = trim(preg_replace("/[\s-]+/", " ", $result)); 
	$result = trim(substr($result, 0, $maxLength));     
	$result = preg_replace("/\s/", "-", $result); 
		
	return $result;
}

function publish()
{
	return 1;
}

?>