<?php
	include 'include/config.php';
	include 'include/admin-functions.php';
	$admin = new AdminFunctions();
	if($admin->sessionExists()){
		header("location: index.php");
		exit();
	}

	include 'csrf.class.php';
	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	if(isset($_POST['signin'])){
		if($csrf->check_valid('post')) {
			$admin->adminlogin($_POST, "manufacturer-master.php");
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">

		<title><?php echo TITLE ?></title><link rel="shortcut icon" href="favicon.ico" type="image/x-icon"><link rel="icon" href="favicon.ico" type="image/x-icon">

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="signin.css" rel="stylesheet">
		<link href="css/londinium-theme.min.css" rel="stylesheet" type="text/css">
		<link href="css/styles.min.css" rel="stylesheet" type="text/css">
		<link href="css/icons.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
		<!-- <script type="text/javascript" src="js/jquery.1.10.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.1.10.2.min.js"></script> -->


		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<br/><br/><br/><br/>
					<h1 class="form-signin-heading">Administrator</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<form class="form-signin" role="form" action="" method="post">

						<?php if(isset($_GET['failed'])){ ?>
								<h3 class="alert alert-danger">Login failed</h3>
						<?php	} ?>

						<br/>
						<h4 class="form-signin-heading">Please sign in to access</h4>

						<div class="form-group">
							<label for="inputEmail" class="sr-only">User Name</label>
							<input type="text" id="inputEmail" class="form-control" placeholder="User Name" name="username" required autofocus/>
							<input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
						</div>
						<div class="form-group">
							<label for="inputPassword" class="sr-only">Password</label>
							<input type="password" id="inputPassword" class="form-control" placeholder="Password" autocomplete="off" name="password" required>
						</div>

						<button class="btn btn-lg btn-primary btn-block" name="signin" type="submit">Sign in</button>

					</form>
				</div>
			</div>
		
			<br/><br/><br/>
			<!-- Footer -->
			<div class="row">
				<div class="col-sm-12">
					<div class="footer clearfix">
					  <div class="pull-left">
						<p>Copyright &copy; <?php echo COPYRIGHT; ?> <?php echo SITE_NAME; ?>. All rights Reserved. Developed by - Saurabh</p>
					  </div>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
	</body>
</html>
