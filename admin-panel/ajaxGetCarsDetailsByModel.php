<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$tableName = 'car_master';
	
	if(isset($_POST['model_id']) && !empty($_POST['model_id'])){
		$model_id	= $admin->escape_string($admin->strip_all($_POST['model_id']));
		$sql = "SELECT * FROM ".PREFIX.$tableName." where model_id = '".$model_id."' order by created DESC";
		$results = $admin->query($sql);
?>
	<div class="panel panel-default">
		<div class="datatable-selectable">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th nowrap>Image 1</th>
						<th nowrap>Image 2</th>
						<th nowrap>Car Registration No</th>
						<th nowrap>Car Manufacturing Year</th>
						<th nowrap>Car Color</th>
						<th>Note</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
<?php
				$x = 1;
				while($row = $admin->fetch($results)){
					$file_name1 = strtolower( pathinfo($row['image_name1'], PATHINFO_FILENAME));
					$ext1 = strtolower(pathinfo($row['image_name1'], PATHINFO_EXTENSION));

					$file_name2 = strtolower( pathinfo($row['image_name2'], PATHINFO_FILENAME));
					$ext2 = strtolower(pathinfo($row['image_name2'], PATHINFO_EXTENSION));
?>
					<tr>
						<td><?php echo $x++; ?></td>
						<td><a href="images/cars/<?php echo $file_name1.'_crop.'.$ext1; ?>" class="show-image1" target="_blank"><img src="images/cars/<?php echo $file_name1.'_crop.'.$ext1; ?>" width="25" height="25"  /></a></td>
						<td><a href="images/cars/<?php echo $file_name2.'_crop.'.$ext2; ?>" class="show-image2" target="_blank"><img src="images/cars/<?php echo $file_name2.'_crop.'.$ext2; ?>" width="25" height="25"  /></a></td>
						<td><?php echo $row['car_registration_no']; ?></td>
						<td><?php echo $row['car_manufacturing_year']; ?></td>
						<td><?php echo $row['car_color']; ?></td>
						<td><?php echo $row['note']; ?></td>
						<td id="car_id<?php echo $row['id']; ?>" nowrap>
						<?php
							if($row["is_sold"] == '0'){
						?>
							<a href="javascript:;" class="green make-sold-out" alt="<?php echo $row['id']; ?>">Sell</a>
						<?php
							}else{
								echo "Sold Out";
							}
						?>
						</td>
					</tr>
<?php
				}
?>
				</tbody>
		  </table>
		</div>
	</div>
<?php
	}
	exit;
?>