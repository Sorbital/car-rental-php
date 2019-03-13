<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$currentDateTime = date("Y-m-d H:i:s");
	$soldCarDetails = $admin->query("select id, model_id from ".PREFIX."car_master where is_sold = '1' and active = '1' and is_deleted = '0' and TIMEDIFF('".$currentDateTime."',sold_datetime) < '00:01:00.000000'");

	$responseArr	= array();
	$carIdArr 		= array();
	$modelIdArr 	= array();

	while($soldCars = $admin->fetch($soldCarDetails)){
		$carIdArr[]		= $soldCars['id'];
		$modelIdArr[]	= $soldCars['model_id'];
	}

	$responseArr['carIdArr']	= $carIdArr;
	$responseArr['modelIdArr']	= $modelIdArr;
	echo json_encode($responseArr);
	exit;
?>