<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$tableName = 'car_master';
	
	$responseArr = array();
	if(isset($_POST['car_id']) && !empty($_POST['car_id'])){
		$car_id	= $admin->escape_string($admin->strip_all($_POST['car_id']));
		$sold_datetime = date("Y-m-d H:i:s");
		$sql = "update ".PREFIX.$tableName." set is_sold = '1', sold_datetime = '".$sold_datetime."' where id = '".$car_id."'";
		$results = $admin->query($sql);

		$carDetails = $admin->getUniqueCarById($car_id);

		$carCountDetail = $admin->fetch($admin->query("select count(id) as carCount from ".PREFIX."car_master where model_id = '".$carDetails['model_id']."' and is_sold = '0' and active = '1' and is_deleted = '0' group by model_id"));

		$responseArr['model_id'] = $carDetails['model_id'];
		if(!empty($carCountDetail['carCount'])){
			$responseArr['carCount'] = $carCountDetail['carCount'];			
		}else{
			$responseArr['carCount'] = 0;
		}
	}
	echo json_encode($responseArr);
	exit;
?>