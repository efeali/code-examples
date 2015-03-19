<?php
session_start();

$_SESSION = array();
session_destroy();
header("location:http://www.id-registry.org");

?>