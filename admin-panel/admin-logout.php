<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$admin->logoutSession();
	header("location:admin-login.php");
?>
