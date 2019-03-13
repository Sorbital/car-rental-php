<?php

	/*

	 * CONFIG

	 */

	
	/* TIMEZONE CONFIG */

	$timezone = "Asia/Kolkata";

	if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

	/* TIMEZONE CONFIG */

	error_reporting(0);


	/* BASE CONFIG */

		DEFINE('SITE_NAME','Car Rental');

		DEFINE('TITLE','Administrator Panel | '.SITE_NAME);

		DEFINE('PREFIX','cr_');

		DEFINE('COPYRIGHT','2019');

		DEFINE('LOGO', 'images/logo.png');

	/* BASE CONFIG */

?>