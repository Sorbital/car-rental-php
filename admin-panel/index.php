<?php
	include_once 'include/config.php';
	include_once 'include/admin-functions.php';
	$admin = new AdminFunctions();
	$pageName = "Car Inventory";
	$pageURL = 'index.php';
	
	$sql = "SELECT mo.*, ma.manufacturer_name FROM ".PREFIX."model_master as mo left join ".PREFIX."manufacturer_master as ma on mo.manufacturer_id = ma.id where ma.is_deleted = '0' and ma.active = '1' and mo.is_deleted = '0' and mo.active = '1' order by ma.manufacturer_name asc, mo.model_name asc, mo.created DESC";
	$results = $admin->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo TITLE ?></title>
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
					<li><a href="Index.php">Home</a></li>
					<li class="active"><?php echo $pageName; ?></li>
				</ul>
			</div>
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
								<th>Model Name</th>
								<th>Count</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
<?php
						$x = 1;
						while($row = $admin->fetch($results)){
							$carDetail = $admin->fetch($admin->query("select count(id) as carCount from ".PREFIX."car_master where model_id = '".$row['id']."' and is_sold = '0' and active = '1' and is_deleted = '0' group by model_id"));
							$carCount = 0;
							if(!empty($carDetail['carCount'])){
								$carCount = $carDetail['carCount'];
							}
?>
							<tr>
								<td><?php echo $x++; ?></td>
								<td><?php echo $row['manufacturer_name']; ?></td>
								<td><?php echo $row['model_name']; ?></td>
								<td id="carCount<?php echo $row['id']; ?>"><?php echo $carCount; ?></td>
								<td>
									<a href="javascript:;" class="green view-details" alt="<?php echo $row['id']; ?>"><i class="icon-eye"></i></a>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".view-details").on("click", function(){
				var model_id = $(this).attr("alt");
				$.ajax({
					url:"ajaxGetCarsDetailsByModel.php",
					type:"POST",
					data:{model_id:model_id},
					success: function(response) {
						$.fancybox.open(response);
					},
					error:function() {
						alert("Unable to get content, please try again");
					},
					complete: function(response) {
						
					}	
				});		
			});

			$(".show-image1").fancybox().trigger('click');
			$(".show-image2").fancybox().trigger('click');

			$(document).on("click", ".make-sold-out", function(){
				var car_id = $(this).attr("alt");
				$.ajax({
					url:"ajaxGetCarsCountByModel.php",
					type:"POST",
					data:{car_id:car_id},
					success: function(response) {
						if(response != ''){
							var response = JSON.parse(response);
							$("#car_id"+car_id).html("Sold Out");
							$("#carCount"+response.model_id).html(response.carCount);
						}
					},
					error:function() {
						alert("Unable to get content, please try again");
					},
					complete: function(response) {
						
					}	
				});		
			});	
		});
	</script>

</body>
</html>