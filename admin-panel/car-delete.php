<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$parentPageURL = 'car-master.php';

	if(isset($_GET['id'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		if(!isset($id) || empty($id)){
			header("location:".$parentPageURL."?deletefail");
			exit;
		}

		//delete from database
		$result = $admin->deleteCar($id);
		header("location:".$parentPageURL."?deletesuccess");
	}
?>