<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	if(isset($_GET['id']))
	{
		$carId = $admin->escape_string($admin->strip_all($_GET['id']));
		$sql_query = $admin->getUniqueCarById($carId);
		$result = $admin->updateCarStatus($carId, $sql_query['active']);
	}
?>
<script type="text/javascript">
	window.history.back();
</script>