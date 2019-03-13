<?php
	include('database.php');
	include('SaveImage.class.php');
	/*
	 * AdminFunctions
	 * v1 - updated loginSession(), logoutSession(), adminLogin()
	 */
	class AdminFunctions extends Database {
		private $userType = 'admin';

		// === LOGIN BEGINS ===
		function loginSession($userId, $userFirstName, $userLastName, $userType,$role) {
			/* DEPRECATED $_SESSION[SITE_NAME] = array(
				$this->userType."UserId" => $userId,
				$this->userType."UserFirstName" => $userFirstName,
				$this->userType."UserLastName" => $userLastName,
				$this->userType."UserType" => $this->userType
			); DEPRECATED */
			$_SESSION[SITE_NAME][$this->userType."UserId"] = $userId;
			$_SESSION[SITE_NAME][$this->userType."UserFirstName"] = $userFirstName;
			$_SESSION[SITE_NAME][$this->userType."UserLastName"] = $userLastName;
			$_SESSION[SITE_NAME][$this->userType."UserType"] = $this->userType;
			$_SESSION[SITE_NAME][$this->userType."role"] = $role;
			/*switch($userType){
				case:'admin'{
					break;
				}
				case:'supplier'{
					break;
				}
				case:'warehouse'{
					break;
				}
				
			}*/
		}
		
		
		function logoutSession() {
			if(isset($_SESSION[SITE_NAME])){
				if(isset($_SESSION[SITE_NAME][$this->userType."UserId"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserId"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserFirstName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserFirstName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserLastName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserLastName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserType"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserType"]);
				}
				return true;
			} else {
				return false;
			}
		}
		function adminLogin($data, $successURL, $failURL = "admin-login.php?failed") {
			$username = $this->escape_string($this->strip_all($data['username']));
			$password = $this->escape_string($this->strip_all($data['password']));
			$query = "select * from ".PREFIX."admin where username='".$username."'";
			$result = $this->query($query);

			if($this->num_rows($result) == 1) { // only one unique user should be present in the system
				$row = $this->fetch($result);
				if(password_verify($password, $row['password'])) {
					$this->loginSession($row['id'], $row['fname'], $row['lname'], $this->userType,$row['role']);
					$this->close_connection();
					header("location: ".$successURL);
					exit;
				} else {
					$this->close_connection();
					header("location: ".$failURL);
					exit;
				}
			} else {
				$this->close_connection();
				header("location: ".$failURL);
				exit;
			}
		}
		function sessionExists(){
			if($this->isUserLoggedIn()){
				return $loggedInUserDetailsArr = $this->getLoggedInUserDetails();
				// return true; // DEPRECATED
			} else {
				return false;
			}
		}
		function isUserLoggedIn(){
			if( isset($_SESSION[SITE_NAME]) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserId']) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserType']) && 
				!empty($_SESSION[SITE_NAME][$this->userType.'UserId']) &&
				$_SESSION[SITE_NAME][$this->userType.'UserType']==$this->userType){
				return true;
			} else {
				return false;
			}
		}
		function getSystemUserType() {
			return $this->userType;
		}
		function getLoggedInUserDetails(){
			$loggedInID = $this->escape_string($this->strip_all($_SESSION[SITE_NAME][$this->userType.'UserId']));
			$loggedInUserDetailsArr = $this->getUniqueUserById($loggedInID);
			return $loggedInUserDetailsArr;
		}
		function getUniqueUserById($userId) {
			$userId = $this->escape_string($this->strip_all($userId));
			$query = "select * from ".PREFIX."admin where id='".$userId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		// === LOGIN ENDS ====

		// == EXTRA FUNCTIONS STARTS ==
		function getValidatedPermalink($permalink){ // v2
			$permalink = trim($permalink, '()');
			$replace_keywords = array("-:-", "-:", ":-", " : ", " :", ": ", ":",
				"-@-", "-@", "@-", " @ ", " @", "@ ", "@", 
				"-.-", "-.", ".-", " . ", " .", ". ", ".", 
				"-\\-", "-\\", "\\-", " \\ ", " \\", "\\ ", "\\",
				"-/-", "-/", "/-", " / ", " /", "/ ", "/", 
				"-&-", "-&", "&-", " & ", " &", "& ", "&", 
				"-,-", "-,", ",-", " , ", " ,", ", ", ",", 
				" ", "\r", "\n", 
				"---", "--", " - ", " -", "- ",
				"-#-", "-#", "#-", " # ", " #", "# ", "#",
				"-$-", "-$", "$-", " $ ", " $", "$ ", "$",
				"-%-", "-%", "%-", " % ", " %", "% ", "%",
				"-^-", "-^", "^-", " ^ ", " ^", "^ ", "^",
				"-*-", "-*", "*-", " * ", " *", "* ", "*",
				"-(-", "-(", "(-", " ( ", " (", "( ", "(",
				"-)-", "-)", ")-", " ) ", " )", ") ", ")",
				"-;-", "-;", ";-", " ; ", " ;", "; ", ";",
				"-'-", "-'", "'-", " ' ", " '", "' ", "'",
				'-"-', '-"', '"-', ' " ', ' "', '" ', '"',
				"-?-", "-?", "?-", " ? ", " ?", "? ", "?",
				"-+-", "-+", "+-", " + ", " +", "+ ", "+",
				"-!-", "-!", "!-", " ! ", " !", "! ", "!");
			$escapedPermalink = str_replace($replace_keywords, '-', $permalink); 
			return strtolower($escapedPermalink);
		}
		function getUniquePermalink($permalink,$tableName,$main_menu,$newPermalink='',$num=1) {
			if($newPermalink=='') {
				$checkPerma = $permalink;
			} else {
				$checkPerma = $newPermalink;
			}
			$sql = $this->query("select * from ".PREFIX.$tableName." where permalink='$checkPerma' and main_menu='$main_menu'");
			if($this->num_rows($sql)>0) {
				$count = $num+1;
				$newPermalink = $permalink.$count;
				return $this->getUniquePermalink($permalink,$tableName,$main_menu,$newPermalink,$count);
			} else {
				return $checkPerma;
			}
		}
		function getActiveLabel($isActive){
			if($isActive){
				return 'Yes';
			} else {
				return 'No';
			}
		}
		function getImageUrl($imageFor, $fileName, $imageSuffix){
			$image_name = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
			$image_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			switch($imageFor){
				case "car":
					$fileDir = "../images/cars/";
					break;
				default:
					return false;
					break;
			}
			$imageUrl = $fileDir.$image_name."_".$imageSuffix.".".$image_ext;
			if(file_exists($imageUrl)){
				return $imageUrl;
			} else {
				return false;
			}
		}
		function unlinkImage($imageFor, $fileName, $imageSuffix){
			$imagePath = $this->getImageUrl($imageFor, $fileName, $imageSuffix);
			$status = false;
			if($imagePath!==false){
				$status = unlink($imagePath);
			}
			return $status;
		}
		function checkUserPermissions($permission,$loggedInUserDetailsArr) {
			$userPermissionsArray = explode(',',$loggedInUserDetailsArr['permissions']);
			if(!in_array($permission,$userPermissionsArray) and $loggedInUserDetailsArr['user_role']!='super') {
				header("location: index.php");
				exit;
			}
		}
					
		// === MANUFACTURER MASTER START
		
		function getAllManufacturers(){
			$query = "select * from ".PREFIX."manufacturer_master where active='1'";
			$sql = $this->query($query);
			return $sql;
		}
		
		function getUniqueManufacturerById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."manufacturer_master where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function addManufacturer($data,$file){
			$manufacturer_name	 = $this->escape_string($this->strip_all($data['manufacturer_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));

			$query = "insert into ".PREFIX."manufacturer_master(manufacturer_name, active) values ('".$manufacturer_name."','".$active."')";

			return $this->query($query);
		}
		
		function updateManufacturer($data,$file){ 
			$id 				= $this->escape_string($this->strip_all($data['id']));
			$manufacturer_name	 = $this->escape_string($this->strip_all($data['manufacturer_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			
			$query = "update ".PREFIX."manufacturer_master set manufacturer_name='".$manufacturer_name."',  active='".$active."' where id='".$id."'";
			return $this->query($query);
		}
		function deleteManufacturer($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "update ".PREFIX."manufacturer_master set is_deleted = '1' where id='".$id."'";
			
			return $this->query($query);
		}
		
		
		function updateManufacturerStatus($cityId, $status) {
			$id 					= $this->escape_string($this->strip_all($cityId));
			$manufacturer_status 	= $this->escape_string($this->strip_all($status));
			
			if($manufacturer_status == '1') {
				$new_status = "0";
			} else if($manufacturer_status == '0') {
				$new_status = "1";
			}
			$sql_query = "update ".PREFIX."manufacturer_master set active='".$new_status."' where id='".$id."'";
			return $this->query($sql_query);
		}
		
		// === MANUFACTURER MASTER END
					
		// === MODEL MASTER START
		
		function getAllModels(){
			$query = "select * from ".PREFIX."model_master where active='1'";
			$sql = $this->query($query);
			return $sql;
		}
		
		function getUniqueModelById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."model_master where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		
		function getModelsByManufacturerId($manufacturer_id) {
			$manufacturer_id = $this->escape_string($this->strip_all($manufacturer_id));
			$query = "select * from ".PREFIX."model_master where manufacturer_id = '".$manufacturer_id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		function addModel($data,$file){
			$manufacturer_id 	= $this->escape_string($this->strip_all($data['manufacturer_id']));
			$model_name 		= $this->escape_string($this->strip_all($data['model_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));

			$query = "insert into ".PREFIX."model_master(manufacturer_id, model_name, active) values ('".$manufacturer_id."', '".$model_name."', '".$active."')";

			return $this->query($query);
		}
		
		function updateModel($data,$file){ 
			$id 				= $this->escape_string($this->strip_all($data['id']));
			$manufacturer_id 	= $this->escape_string($this->strip_all($data['manufacturer_id']));
			$model_name 		= $this->escape_string($this->strip_all($data['model_name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));
			
			$query = "update ".PREFIX."model_master set manufacturer_id='".$manufacturer_id."',  model_name='".$model_name."',  active='".$active."' where id='".$id."'";
			return $this->query($query);
		}
		function deleteModel($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "update ".PREFIX."model_master set is_deleted = '1' where id='".$id."'";
			
			return $this->query($query);
		}
		
		
		function updateModelStatus($cityId, $status) {
			$id 			= $this->escape_string($this->strip_all($cityId));
			$model_status 	= $this->escape_string($this->strip_all($status));
			
			if($model_status == '1') {
				$new_status = "0";
			} else if($model_status == '0') {
				$new_status = "1";
			}
			$sql_query = "update ".PREFIX."model_master set active='".$new_status."' where id='".$id."'";
			return $this->query($sql_query);
		}
		
		// === MODEL MASTER END
					
		// === CAR MASTER START
		
		function getAllCars(){
			$query = "select * from ".PREFIX."car_master where active='1'";
			$sql = $this->query($query);
			return $sql;
		}
		
		function getUniqueCarById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."car_master where id='$id'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		
		function getCarsByModelId($model_id) {
			$model_id = $this->escape_string($this->strip_all($model_id));
			$query = "select * from ".PREFIX."car_master where model_id = '".$model_id."'";
			return $this->query($query);
		}
		
		function getCarsByManufacturerId($manufacturer_id) {
			$manufacturer_id = $this->escape_string($this->strip_all($manufacturer_id));
			$query = "select c.* from ".PREFIX."car_master as c left join ".PREFIX."model_master as m on c.model_id = m.id where m.manufacturer_id = '".$manufacturer_id."'";
			return $this->query($query);
		}

		function addCar($data,$file){
			$model_id 				= $this->escape_string($this->strip_all($data['model_id']));
			$car_registration_no 	= $this->escape_string($this->strip_all($data['car_registration_no']));
			$car_manufacturing_year = $this->escape_string($this->strip_all($data['car_manufacturing_year']));
			$car_color 				= $this->escape_string($this->strip_all($data['car_color']));
			$note 					= $this->escape_string($this->strip_all($data['note']));

			$SaveImage = new SaveImage();
			$imgDir = 'images/cars/';
			$imageSizes = array(
					'large' => array(
						'width' => 1240,
						'suffix' => 'large'
					),
					'crop' => array(
						'width' => 300,
						'suffix' => 'crop'
					),
				);
			if(isset($file['image_name1']['name']) && !empty($file['image_name1']['name'])){
				$file_name1 = strtolower( pathinfo($file['image_name1']['name'], PATHINFO_FILENAME));
				$file_name1 = $this->getValidatedPermalink($file_name1);
				$image_name1 = $SaveImage->uploadImageFileFromForm($file['image_name1'], $imageSizes, $imgDir, $file_name1."-".time().'-1');
			} else {
				$image_name1 = '';
			}

			if(isset($file['image_name2']['name']) && !empty($file['image_name2']['name'])){
				$file_name2 = strtolower( pathinfo($file['image_name2']['name'], PATHINFO_FILENAME));
				$file_name2 = $this->getValidatedPermalink($file_name2);
				$image_name2 = $SaveImage->uploadImageFileFromForm($file['image_name2'], $imageSizes, $imgDir, $file_name2."-".time().'-1');
			} else {
				$image_name2 = '';
			}

			$active 				= $this->escape_string($this->strip_all($data['active']));

			$query = "insert into ".PREFIX."car_master(model_id, car_registration_no, car_manufacturing_year, car_color, note, image_name1, image_name2, active) values ('".$model_id."', '".$car_registration_no."', '".$car_manufacturing_year."', '".$car_color."', '".$note."', '".$image_name1."', '".$image_name2."', '".$active."')";

			return $this->query($query);
		}
		
		function updateCar($data,$file){ 
			$id 					= $this->escape_string($this->strip_all($data['id']));
			$model_id 				= $this->escape_string($this->strip_all($data['model_id']));
			$car_registration_no 	= $this->escape_string($this->strip_all($data['car_registration_no']));
			$car_manufacturing_year = $this->escape_string($this->strip_all($data['car_manufacturing_year']));
			$car_color 				= $this->escape_string($this->strip_all($data['car_color']));
			$note 					= $this->escape_string($this->strip_all($data['note']));

			$carDetails = $this->getUniqueCarById($id);

			$SaveImage = new SaveImage();
			$imgDir = 'images/cars/';
			$imageSizes = array(
					'large' => array(
						'width' => 1240,
						'suffix' => 'large'
					),
					'crop' => array(
						'width' => 300,
						'suffix' => 'crop'
					),
				);
			if(isset($file['image_name1']['name']) && !empty($file['image_name1']['name'])){
				$file_name1 = strtolower( pathinfo($file['image_name1']['name'], PATHINFO_FILENAME));
				$file_name1 = $this->getValidatedPermalink($file_name1);
				$image_name1 = $SaveImage->uploadImageFileFromForm($file['image_name1'], $imageSizes, $imgDir, $file_name1."-".time().'-1');

				//delete previous uploaded image
				$file_name = str_replace('', '-', strtolower( pathinfo($carDetails['image_name1'], PATHINFO_FILENAME)));
				$ext = pathinfo($carDetails['image_name1'], PATHINFO_EXTENSION);
				if(file_exists($imgDir.$file_name.'_large.'.$ext)) {
					unlink($imgDir.$file_name.'_large.'.$ext);
				}
				if(file_exists($imgDir.$file_name.'_crop.'.$ext)) {
					unlink($imgDir.$file_name.'_crop.'.$ext);
				}
			} else {
				$image_name1 = $carDetails["image_name1"];
			}

			if(isset($file['image_name2']['name']) && !empty($file['image_name2']['name'])){
				$file_name2 = strtolower( pathinfo($file['image_name2']['name'], PATHINFO_FILENAME));
				$file_name2 = $this->getValidatedPermalink($file_name2);
				$image_name2 = $SaveImage->uploadImageFileFromForm($file['image_name2'], $imageSizes, $imgDir, $file_name1."-".time().'-1');

				//delete previous uploaded image
				$file_name = str_replace('', '-', strtolower( pathinfo($carDetails['image_name2'], PATHINFO_FILENAME)));
				$ext = pathinfo($carDetails['image_name2'], PATHINFO_EXTENSION);
				if(file_exists($imgDir.$file_name.'_large.'.$ext)) {
					unlink($imgDir.$file_name.'_large.'.$ext);
				}
				if(file_exists($imgDir.$file_name.'_crop.'.$ext)) {
					unlink($imgDir.$file_name.'_crop.'.$ext);
				}
			} else {
				$image_name2 = $carDetails["image_name2"];
			}

			$active 				= $this->escape_string($this->strip_all($data['active']));
			
			$query = "update ".PREFIX."car_master set model_id='".$model_id."', car_registration_no = '".$car_registration_no."', car_manufacturing_year = '".$car_manufacturing_year."', car_color = '".$car_color."', note = '".$note."', image_name1 = '".$image_name1."', image_name2 = '".$image_name2."',  active='".$active."' where id='".$id."'";
			return $this->query($query);
		}
		function deleteCar($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "update ".PREFIX."car_master set is_deleted = '1' where id='".$id."'";
			
			return $this->query($query);
		}
		
		
		function updateCarStatus($cityId, $status) {
			$id 			= $this->escape_string($this->strip_all($cityId));
			$car_status 	= $this->escape_string($this->strip_all($status));
			
			if($car_status == '1') {
				$new_status = "0";
			} else if($car_status == '0') {
				$new_status = "1";
			}
			$sql_query = "update ".PREFIX."car_master set active='".$new_status."' where id='".$id."'";
			return $this->query($sql_query);
		}
		
		// === CAR MASTER END

	} 
?>