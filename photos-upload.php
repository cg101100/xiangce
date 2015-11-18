<?php
	require_once 'global.php';

	use Intervention\Image\Image;

	$albumId = intval($_GET['id']);

	if ($_FILES["photo"]["error"] > 0) {
	 	echo 0;
	}
	else {
		// echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		// echo "Type: " . $_FILES["file"]["type"] . "<br />";
		// echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		// echo "Stored in: " . $_FILES["file"]["tmp_name"];
		switch ($_FILES["photo"]["type"]) {
			case 'image/png':
				$ext = 'png';
				break;
			case 'image/jpeg':
			case 'image/jpg':
				$ext = 'jpg';
				break;
			case 'image/gif':
				$ext = 'gif';
				break;
			default:
				$ext = '';
				break;
		}
		$image = Image::make($_FILES["photo"]["tmp_name"]);
		$path = 'upload/images/origin/'.date('Ymd/', time());
		if (file_exists($path) == false) {
			mkdir($path, 0777, true);
		}
		$basename = substr(time(), 5).rand(100000,999999).'.'.$ext;
		$originFile = $path.$basename;
		$image->save($originFile);
		// $size = $image->filesize();

		$path = 'upload/images/thumb/'.date('Ymd/', time());
		if (file_exists($path) == false) {
			mkdir($path, 0777, true);
		}
		$thumbFile = $path.$basename;
		$width = $image->width;
		$height = $image->height;
		$r = min(250/$width, 250/$height);

		$image->resize($width*$r, $height*$r)->save($thumbFile);

		$photo = array('album_id' => $albumId, 'thumb' => $thumbFile, 'src' => $originFile, /*'size' => $size,*/ 'created_at' => time());
		$photo['width'] = $width;
		$photo['height'] = $height;
		$insertId = D('photos')->set($photo);

		$album = D('albums')->one($albumId);
		D('albums')->set(array('images' => $album['images']+1), $albumId);
		D('girls')->set(array('uploaded_at' => time()), $album['g_id']);

		echo $insertId;
	}

	//echo 1;