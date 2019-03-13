<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Manufacturer";
	$pageURL = 'manufacturer-master.php';
	$addURL = 'manufacturer-add.php';
	$deleteURL = 'manufacturer-delete.php';
	$tableName = 'manufacturer_master';
	
	$sql = "SELECT * FROM ".PREFIX.$tableName." where is_deleted = '0' order by created DESC";
	$results = $admin->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE; ?> | Manufacturer Master</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">

	<link href="css/font-awesome.min.css" rel="stylesheet">
	<!--<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">-->

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/plugins/charts/sparkline.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uniform.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/select2.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputmask.js"></script>
	<script type="text/javascript" src="js/plugins/forms/autosize.js"></script>
	<script type="text/javascript" src="js/plugins/forms/inputlimit.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/listbox.js"></script>
	<script type="text/javascript" src="js/plugins/forms/multiselect.js"></script>
	<script type="text/javascript" src="js/plugins/forms/validate.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/tags.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/switch.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.full.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/uploader/plupload.queue.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/wysihtml5.min.js"></script>
	<script type="text/javascript" src="js/plugins/forms/wysihtml5/toolbar.js"></script>
	<script type="text/javascript" src="js/plugins/interface/daterangepicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fancybox.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/moment.js"></script>
	<script type="text/javascript" src="js/plugins/interface/jgrowl.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/datatables.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/colorpicker.js"></script>
	<script type="text/javascript" src="js/plugins/interface/fullcalendar.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/timepicker.min.js"></script>
	<script type="text/javascript" src="js/plugins/interface/collapsible.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/application.js"></script>
</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php'; 
	?>

	<div class="page-container">

		<?php include 'include/sidebar.php'; 
		?>

 		<div class="page-content">
    
		

			<div class="breadcrumb-line">
				<div class="page-ttle hidden-xs" style="float:left;"><?php echo $pageName; ?></div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li class="active"><?php echo $pageName; ?></li>
				</ul>
			</div>

			<a href="<?php echo $addURL; ?>" class="label label-primary">Add <?php echo $pageName; ?></a><br/><br/>
	<?php
		if(isset($_GET['deletesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark"></i> <?php echo $pageName; ?> successfully deleted.
			</div><br/>
	<?php	} ?>
	
	<?php
		if(isset($_GET['deletefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not deleted.</strong> Invalid Details.
			</div><br/>
	<?php	} ?>

			<br/>
			<div class="panel panel-default">
				<div class="datatable-selectable">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Manufacturer Name</th>								
								<th>Active</th>	
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = 1;
						while($row = $admin->fetch($results)){
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['manufacturer_name']; ?></td>
								<td>
								<?php
									if($row['active'] == '0') {
										$active_msg = 'Are you sure you want to make it active?';
										$active_status = 'No';
									} else if($row['active'] == '1') {
										$active_msg = 'Are you sure you want to make it inactive?';
										$active_status = 'Yes';
									}
								?>
									<a class="" href="manufacturer-status.php?id=<?php echo $row['id']; ?>" onclick="return confirm('<?php echo $active_msg;?>');" title="Click to change manufacturer status."><?php echo $active_status; ?></a>
								</td>
								<td>
									<a href="<?php echo $addURL; ?>?edit&id=<?php echo $row['id'] ?>" name="edit"  title="Click to edit this row"><i class="icon-pencil"></i></a>
									<a  href="<?php echo $deleteURL; ?>?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Click to delete this row, this action cannot be undone."><i class="icon-remove3"></i></a>
								</td>
							</tr>
<?php
						}
?>
						</tbody>
				  </table>
				</div>
			</div>

<?php 	include "include/footer.php"; ?>

		</div>

	</div>

</body>
</html>