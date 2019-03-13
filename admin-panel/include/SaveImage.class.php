<?php 
	/*
	 * - SaveImage 
	 * v1 - supports saving image from URL and HTML FORM
	 * v2 - supports saving images from HTML FORM Array
	 * v2.5  - added uploadImageFileFromFormWithHeight()
	 * v3 - added resize option, updated parameters for all functions and removed uploadImageFileFromFormWithHeight()
	 * v4 - added uploadCroppedImageFileFromForm(), added alpha channel for PNG when saving PNG image
	 */
	/* TEMPLATE CODE TO IMPLEMENT UPLOAD IMAGE FROM FILE
		$imageSizes = array(
			'large' => array(
				'width' => 500,
				'suffix' => 'large'
			),
			
			'medium' => array(
				'width' => 250,
				'suffix' => 'medium'
			),
			
			'small' => array(
				'width' => 100,
				'suffix' => 'small'
			)
		);
		// $path = "https://www.dropbox.com/s/0mzbmy9zw3fvc79/1001.jpg?dl=1";
		$path = "http://www.google.co.in/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png";
		$SaveImage = new SaveImage();
		$imageName = $SaveImage->uploadImageFileFromUrl($path, $imageSizes, 'images/images/', SaveImage::$RESIZE_TO_WIDTH);
		echo $imageName;
	   TEMPLATE CODE TO IMPLEMENT UPLOAD IMAGE FROM FILE 
	   
	   TEMPLATE CODE TO IMPLEMENT UPLOAD CROPPED IMAGE FROM FILE
		if(isset($file['image1']['name']) && !empty($file['image1']['name'])){
			$SaveImage = new SaveImage();
			$imgDir = '../images/products/';
			// sample cropData : {"x":12.21590909090909,"y":-46.606060606060595,"width":736.2121212121211,"height":736.2121212121211,"scaleX":1,"scaleY":1},
			$cropData = $this->strip_all($data['cropData']);
			$image1 = $SaveImage->uploadCroppedImageFileFromForm($file['image1'], 200, $cropData, $imgDir, time().'1');
			echo $image1;
		}
	   TEMPLATE CODE TO IMPLEMENT UPLOAD CROPPED IMAGE FROM FILE
	 */

	class SaveImage{
		static $RESIZE_TO_WIDTH = 0; // DEFAULT
		static $RESIZE_TO_HEIGHT_WIDTH = 1;

		function uploadImageFileFromUrl($sourceFileName, $imageSizes, $imgDir, $resizeTo = 0, $targetFileName = '') {
			$targetFileName = (empty($targetFileName)) ? time() : $targetFileName;
			// $fileName = str_replace(' ', '-', strtolower(pathinfo($sourceFileName, PATHINFO_FILENAME)) );
			$ext = strtolower(pathinfo($sourceFileName, PATHINFO_EXTENSION));

			// == DROPBOX UPDATE
			// removes any extra parameters like ?dl=1 after extension for fileName.jpg?dl=1,
			// not required files with name such as fileName.jpg
			$locationOfQuestionMark = strrpos($ext, "?");
			if($locationOfQuestionMark){
				$ext = substr($ext, 0, $locationOfQuestionMark);
			}
			// == DROPBOX UPDATE

			$imageProcessObj = new ImageProcess();
			$imageProcessObj->load($sourceFileName);

			foreach($imageSizes as $key => $value) {
				if($resizeTo == self::$RESIZE_TO_WIDTH){
					$imageProcessObj->resizeToWidth($value['width']);
				} else if($resizeTo == self::$RESIZE_TO_HEIGHT_WIDTH){
					$imageProcessObj->resize($value['width'], $value['height']);
				}
				$imageProcessObj->save($imgDir.$targetFileName.'_'.$value['suffix'].'.'.$ext);
			}
			return $targetFileName.'.'.$ext;
		}
		function uploadImageFileFromForm($sourceFileElement, $imageSizes, $imgDir, $resizeTo = 0, $targetFileName = ''){
			$targetFileName = (empty($targetFileName)) ? time() : $targetFileName;

			$fileName = str_replace(' ', '-', strtolower(pathinfo($sourceFileElement['name'], PATHINFO_FILENAME)) );
			$ext = strtolower(pathinfo($sourceFileElement['name'], PATHINFO_EXTENSION));

			$imageProcessObj = new ImageProcess();
			$imageProcessObj->load($sourceFileElement["tmp_name"]);

			foreach($imageSizes as $key => $value) {
				if($resizeTo == self::$RESIZE_TO_WIDTH){
					$imageProcessObj->resizeToWidth($value['width']);
				} else if($resizeTo == self::$RESIZE_TO_HEIGHT_WIDTH){
					$imageProcessObj->resize($value['width'], $value['height']);
				}
				// echo $imgDir.$targetFileName.'_'.$value['suffix'].'.'.$ext."<br/>"; // TEST
				$imageProcessObj->save($imgDir.$targetFileName.'_'.$value['suffix'].'.'.$ext);
			}
			return $targetFileName.'.'.$ext;
		}
		function uploadImageFileArrayFromForm($sourceFileElement, $imageSizes, $imgDir, $resizeTo = 0, $targetFileName = ''){
			$targetFileName = (empty($targetFileName)) ? time() : $targetFileName;

			$fileNameArr = array();

			for($i=0; $i<count($sourceFileElement['name']); $i++){
				$fileName = str_replace(' ', '-', strtolower(pathinfo($sourceFileElement['name'][$i], PATHINFO_FILENAME)) );
				$ext = strtolower(pathinfo($sourceFileElement['name'][$i], PATHINFO_EXTENSION));
				$targetFileName .= $i;

				$imageProcessObj = new ImageProcess();
				$imageProcessObj->load($sourceFileElement["tmp_name"][$i]);

				foreach($imageSizes as $key => $value) {
					if($resizeTo == self::$RESIZE_TO_WIDTH){
						$imageProcessObj->resizeToWidth($value['width']);
					} else if($resizeTo == self::$RESIZE_TO_HEIGHT_WIDTH){
						$imageProcessObj->resize($value['width'], $value['height']);
					}
					$imageProcessObj->save($imgDir.$targetFileName.'_'.$value['suffix'].'.'.$ext);
				}
				$fileNameArr[] = $targetFileName.'.'.$ext;
			}
			return $fileNameArr;
		}
		
		function uploadCroppedImageFileFromForm($sourceFileElement, $largeWidth, $cropData, $imgDir, $targetFileName = ''){
			$targetFileName = (empty($targetFileName)) ? time() : $targetFileName;

			$fileName = str_replace(' ', '-', strtolower(pathinfo($sourceFileElement['name'], PATHINFO_FILENAME)) );
			$ext = str_replace(' ', '-', strtolower(pathinfo($sourceFileElement['name'], PATHINFO_EXTENSION)) );

			$imageProcessObj = new ImageProcess();

			// == SAVE CROPPED IMAGE ==
			$imageProcessObj->load($sourceFileElement["tmp_name"]);
			$imageProcessObj->cropImage($cropData, $largeWidth);
			$imageProcessObj->save($imgDir.$targetFileName.'_crop.'.$ext);

			// == SAVE ORIGINAL IMAGE ==
			$imageProcessObj->load($sourceFileElement["tmp_name"]);
			$imageProcessObj->resizeToWidth($largeWidth);
			$imageProcessObj->save($imgDir.$targetFileName.'_large.'.$ext);

			return $targetFileName.'.'.$ext;
		}
	}

	class ImageProcess{
		private $image;
		private $image_type;
		function getWidth() {
			return imagesx($this->image);
		}
		private function getHeight() {
			return imagesy($this->image);
		}

		function load($filename) {
			$image_info = getimagesize($filename);
			$this->image_type = $image_info[2];
			if( $this->image_type == IMAGETYPE_JPEG ) {
				$this->image = imagecreatefromjpeg($filename);
			} elseif( $this->image_type == IMAGETYPE_GIF ) {
				$this->image = imagecreatefromgif($filename);
			} elseif( $this->image_type == IMAGETYPE_PNG ) {
				$this->image = imagecreatefrompng($filename);
			}
		}
		function resizeToWidth($width) {
			$ratio = $width / $this->getWidth();
			$height = $this->getheight() * $ratio;
			$this->resize($width,$height);
			
		}
		function resize($width,$height) {
			$new_image = imagecreatetruecolor($width, $height);
			if( $this->image_type == IMAGETYPE_PNG ) {
				// imagealphablending($croppedImage, false);
				imagesavealpha($new_image, true);
				$transparentColor = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
				imagefill($new_image, 0, 0, $transparentColor);
			}
			imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
			$this->image = $new_image;
		}
		function cropImage($cropData, $largeWidth) {
			// cropData : {"x":12.21590909090909,"y":-46.606060606060595,"width":736.2121212121211,"height":736.2121212121211,"scaleX":1,"scaleY":1},
			$cropData = json_decode($cropData, true);

			$width = $cropData['width'];
			$height = $cropData['height'];
			$srcX = $cropData['x'];
			$srcY = $cropData['y'];
			$croppedImage = imagecreatetruecolor($width, $height);
			if( $this->image_type == IMAGETYPE_PNG ) {
				// imagealphablending($croppedImage, false);
				imagesavealpha($croppedImage, true);
				$transparentColor = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
				imagefill($croppedImage, 0, 0, $transparentColor);
			}
			// imagecopyresampled($croppedImage, $this->image, 0, 0, $srcX, $srcY, $width, $height, $this->getWidth(), $this->getHeight()); // DEPRECATED
			imagecopyresampled($croppedImage, $this->image, 0, 0, $srcX, $srcY, $width, $height, $width, $height);
			$this->image = $croppedImage;
			$this->resizeToWidth($largeWidth);
		}
		function save($filename, $compression=90, $permissions=null) {
			if( $this->image_type == IMAGETYPE_JPEG ) {
				imagejpeg($this->image,$filename,$compression);
			} elseif( $this->image_type == IMAGETYPE_GIF ) {
				imagegif($this->image,$filename);
			} elseif( $this->image_type == IMAGETYPE_PNG ) {
				imagepng($this->image, $filename);
			}
			if( $permissions != null) {
				chmod($filename,$permissions);
			}
		}
	}

?>
