<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();

	$pageName = "Car";
	$parentPageURL = 'car-master.php';
	$pageURL = 'car-add.php';

	include_once 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	if(isset($_POST['register'])){
		if($csrf->check_valid('post')) {
			$allowed_formats = array("image/jpg", "image/jpeg");
			// print_r($_POST);
			// print_r($_FILES);
			// exit;
			$model_id 				= trim($admin->escape_string($admin->strip_all($_POST['model_id'])));
			$car_registration_no 	= trim($admin->escape_string($admin->strip_all($_POST['car_registration_no'])));
			$car_manufacturing_year	= trim($admin->escape_string($admin->strip_all($_POST['car_manufacturing_year'])));
			$car_color				= trim($admin->escape_string($admin->strip_all($_POST['car_color'])));
			$note					= trim($admin->escape_string($admin->strip_all($_POST['note'])));
			$image_name1			= trim($admin->escape_string($admin->strip_all($_FILES['image_name1']['name'])));
			$image_name2			= trim($admin->escape_string($admin->strip_all($_FILES['image_name2']['name'])));
			
			if(empty($model_id)){
				header("location:".$pageURL."?registerfail&msg=Please select a Car Model");
				exit();
			} else if(empty($car_registration_no)){
				header("location:".$pageURL."?registerfail&msg=Please enter Car Registration No");
				exit();
			} else if(empty($car_manufacturing_year)){
				header("location:".$pageURL."?registerfail&msg=Please enter Car Manufacturing Year");
				exit();
			} else if(empty($car_color)){
				header("location:".$pageURL."?registerfail&msg=Please enter Car Color");
				exit();
			} else if(empty($note)){
				header("location:".$pageURL."?registerfail&msg=Please enter Note about Car");
				exit();
			} else if(empty($image_name1)){
				header("location:".$pageURL."?registerfail&msg=Please upload Image1");
				exit();
			} else if(!empty($image_name1) && !in_array($_FILES['image_name1']['type'], $allowed_formats)){
				header("location:".$pageURL."?registerfail&msg=Please upload only jpg/jpeg file for Image1");
				exit();
			} else if(empty($image_name2)){
				header("location:".$pageURL."?registerfail&msg=Please upload Image2");
				exit();
			} else if(!empty($image_name2) && !in_array($_FILES['image_name2']['type'], $allowed_formats)){
				header("location:".$pageURL."?registerfail&msg=Please upload only jpg/jpeg file for Image2");
				exit();
			} else {
				//add to database
				$result = $admin->addCar($_POST, $_FILES);
				header("location:".$pageURL."?registersuccess");
			}
		}
	}
	if(isset($_GET['edit'])){
		$id = $admin->escape_string($admin->strip_all($_GET['id']));
		$data = $admin->getUniqueCarById($id);
	}
	if(isset($_POST['update'])) {
		if($csrf->check_valid('post')) {
			$allowed_formats = array("image/jpg", "image/jpeg");
			$id 			= trim($admin->escape_string($admin->strip_all($_POST['id'])));
			$model_id 		= trim($admin->escape_string($admin->strip_all($_POST['model_id'])));
			$car_registration_no 	= trim($admin->escape_string($admin->strip_all($_POST['car_registration_no'])));
			$car_manufacturing_year	= trim($admin->escape_string($admin->strip_all($_POST['car_manufacturing_year'])));
			$car_color				= trim($admin->escape_string($admin->strip_all($_POST['car_color'])));
			$note					= trim($admin->escape_string($admin->strip_all($_POST['note'])));
			$image_name1			= trim($admin->escape_string($admin->strip_all($_FILES['image_name1']['name'])));
			$image_name2			= trim($admin->escape_string($admin->strip_all($_FILES['image_name2']['name'])));
			
			if(empty($model_id)){
				header("location:".$pageURL."?updatefail&msg=Please select a Car Model&edit&id=".$id);
				exit();
			} else if(empty($car_registration_no)){
				header("location:".$pageURL."?updatefail&msg=Please enter Car Registration No&edit&id=".$id);
				exit();
			} else if(empty($car_manufacturing_year)){
				header("location:".$pageURL."?updatefail&msg=Please enter Car Manufacturing Year&edit&id=".$id);
				exit();
			} else if(empty($car_color)){
				header("location:".$pageURL."?updatefail&msg=Please enter Car Color&edit&id=".$id);
				exit();
			} else if(empty($note)){
				header("location:".$pageURL."?updatefail&msg=Please enter Note about Car&edit&id=".$id);
				exit();
			} else if(!empty($image_name1) && !in_array($_FILES['image_name1']['type'], $allowed_formats)){
				header("location:".$pageURL."?updatefail&msg=Please upload only jpg/jpeg file for Image1&edit&id=".$id);
				exit();
			} else if(!empty($image_name2) && !in_array($_FILES['image_name2']['type'], $allowed_formats)){
				header("location:".$pageURL."?updatefail&msg=Please upload only jpg/jpeg file for Image2&edit&id=".$id);
				exit();
			} else {
				//update to database
				$result = $admin->updateCar($_POST, $_FILES);
				header("location:".$pageURL."?updatesuccess&edit&id=".$id);
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?> | Add Car</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.min.css" rel="stylesheet" type="text/css">
	<link href="css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="css/bootstrap-timepicker.min.css" rel="stylesheet">
	<link href="css/bootstrap-select.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/nanoscroller.css" rel="stylesheet">
	<link href="css/emoji.css" rel="stylesheet">
	<link href="css/cover.css" rel="stylesheet">
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
	<script type="text/javascript" src="js/additional-methods.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#form").validate({
				rules: {
					model_id:{
						required:true
					},
					car_registration_no:{
						required:true
					},
					car_manufacturing_year:{
						required:true,
						number:true,
						minlength:4,
						maxlength:4
					},
					car_color:{
						required:true
					},
					note:{
						required:true
					},
					image_name1:{
					<?php if(!isset($_GET['edit'])){ ?>
						required:true,
					<?php } ?>
						extension:"jpg|jpeg"
					},
					image_name2:{
					<?php if(!isset($_GET['edit'])){ ?>
						required:true,
					<?php } ?>
						extension:"jpg|jpeg"
					},
					active: {
						required:true
					}
				}
			});
		});
	</script>
</head>
<body class="sidebar-wide">
	<?php include 'include/navbar.php' ?>

	<div class="page-container">

		<?php include 'include/sidebar.php' ?>

		<div class="page-content">

		<!--
			<div class="page-header">
				<div class="page-title">
					<h3>Dashboard <small>Welcome Eugene. 12 hours since last visit</small></h3>
				</div>
				<div id="reportrange" class="range">
					<div class="visible-xs header-element-toggle"><a class="btn btn-primary btn-icon"><i class="icon-calendar"></i></a></div>
					<div class="date-range"></div>
					<span class="label label-danger">9</span>
				</div>
			</div>
		-->

		<div class="breadcrumb-line">
			<div class="page-ttle hidden-xs" style="float:left;">
<?php
				if(isset($_GET['edit'])){ ?>
					<?php echo 'Edit '.$pageName; ?>
<?php			} else { ?>
					<?php echo 'Add New '.$pageName; ?>
<?php			} ?>
			</div>
			<ul class="breadcrumb">
				<li><a href="index.php">Home</a></li>
				<li><a href="<?php echo $parentPageURL; ?>"><?php echo $pageName; ?></a></li>
				<li class="active">
<?php
				if(isset($_GET['edit'])){ ?>
					<?php echo 'Edit '.$pageName; ?>
<?php			} else { ?>
					<?php echo 'Add New '.$pageName; ?>
<?php			} ?>
				</li>
			</ul>
		</div>

		<a href="<?php echo $parentPageURL; ?>" class="label label-primary">Back to <?php echo $pageName; ?></a><br/><br/>
<?php
		if(isset($_GET['registersuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully added.
			</div><br/>
<?php	} ?>
	
<?php
		if(isset($_GET['registerfail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not added.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
			</div><br/>
<?php	} ?>

<?php
		if(isset($_GET['updatesuccess'])){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-checkmark3"></i> <?php echo $pageName; ?> successfully updated.
			</div><br/>
<?php	} ?>
	
<?php
		if(isset($_GET['updatefail'])){ ?>
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<i class="icon-close"></i> <strong><?php echo $pageName; ?> not updated.</strong> <?php echo $admin->escape_string($admin->strip_all($_GET['msg'])); ?>.
			</div><br/>
<?php	} ?>
			<form role="form" action="" method="post" id="form" enctype="multipart/form-data">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h6 class="panel-title"><i class="icon-library"></i>Car Details</h6>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="row">								
								<div class="col-sm-4">
									<label>Model<span style="color:red;">*</span></label>
									<select name="model_id" id="model_id" class="form-control">
										<option value="">Select Model</option>
									<?php
										$modelSQL = $admin->getAllModels();
										while($model_fetch = $admin->fetch($modelSQL)) {
									?>
										<option value="<?php echo $model_fetch['id']; ?>" <?php if(isset($_GET['edit']) and $data['model_id']==$model_fetch['id']) { echo 'selected'; } ?>><?php echo $model_fetch['model_name']; ?></option>
									<?php
										}
									?>
									</select>
								</div>
								
								<div class="col-sm-4">
									<label>Active<span style="color:red;">*</span></label>
									<select name="active" id="active" class="form-control">
										<option value="0" <?php if(isset($_GET['edit']) && $data['active']=='0'){ echo "selected"; } ?>>No</option>
										<option value="1" <?php if(isset($_GET['edit']) && $data['active']=='1'){ echo "selected"; } ?>>Yes</option>
									</select>
								</div>
								
																
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label>Car Registration No<span style="color:red;">*</span></label>
									<input type="text" class="form-control" name="car_registration_no" id="car_registration_no" value="<?php if(isset($_GET['edit'])){ echo $data['car_registration_no']; }?>"/>
								</div>
								
								<div class="col-sm-4">
									<label>Car Manufacturing Year<span style="color:red;">*</span></label>
									<input type="text" class="form-control" name="car_manufacturing_year" id="car_manufacturing_year" value="<?php if(isset($_GET['edit'])){ echo $data['car_manufacturing_year']; }?>"/>
								</div>

								<div class="col-sm-4">
									<label>Car Color<span style="color:red;">*</span></label>
									<input type="text" class="form-control" name="car_color" id="car_color" value="<?php if(isset($_GET['edit'])){ echo $data['car_color']; }?>"/>
								</div>						
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Note<span style="color:red;">*</span></label>
									<textarea class="form-control" name="note" id="note"><?php if(isset($_GET['edit'])){ echo $data['note']; }?></textarea>
								</div>					
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<label>Image 1<span style="color:red;">*</span></label>
									<input type="file" class="form-control file" name="image_name1" />
									<?php if(isset($_GET['edit']) and !empty($data['image_name1'])) {
										$file_name1 = strtolower( pathinfo($data['image_name1'], PATHINFO_FILENAME));
										$ext1 = strtolower(pathinfo($data['image_name1'], PATHINFO_EXTENSION));
									?>
										<img src="images/cars/<?php echo $file_name1.'_crop.'.$ext1; ?>" width="200"  />
									<?php
									} ?>
								</div>
								<div class="col-sm-6">
									<label>Image 2<span style="color:red;">*</span></label>
									<input type="file" class="form-control file" name="image_name2" />
									<?php if(isset($_GET['edit']) and !empty($data['image_name2'])) {
										$file_name2 = strtolower( pathinfo($data['image_name2'], PATHINFO_FILENAME));
										$ext2 = strtolower(pathinfo($data['image_name2'], PATHINFO_EXTENSION));
									?>
										<img src="images/cars/<?php echo $file_name2.'_crop.'.$ext2; ?>" width="200"  />
									<?php
									} ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions text-right">
				<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
<?php
			if(isset($_GET['edit'])){ ?>
					<input type="hidden" class="form-control" name="id" required="required" value="<?php echo $id ?>"/>
					<button type="submit" name="update" class="btn btn-warning"><i class="icon-pencil"></i>Update <?php echo $pageName; ?></button>
<?php		} else { ?>
					<button type="submit" name="register" class="btn btn-danger"><i class="icon-signup"></i>Add <?php echo $pageName; ?></button>
<?php		} ?>
				</div>
			</form>

<?php 	include "include/footer.php"; ?>
    
		</div>
	</div>
	<script src="js/jquery-ui.js"></script>
	<script src="js/bootstrap-timepicker.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
</body>
</html>