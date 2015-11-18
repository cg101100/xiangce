<?php
	require_once 'global.php';
	use Intervention\Image\Image;

	$action = $_REQUEST['action'];
	unset($_POST['action']);
	if (isset($action)) {
		switch ($action) {
			case 'add':
				unset($_POST['action']);
				if (empty($_FILES["avatar"]) == false AND $_FILES["avatar"]["error"] == 0) {
					switch ($_FILES["avatar"]["type"]) {
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
					$image = Image::make($_FILES["avatar"]["tmp_name"]);
					$path = 'upload/images/avatar/'.date('Ymd/', time());
					if (file_exists($path) == false) {
						mkdir($path, 0777, true);
					}
					$basename = substr(time(), 5).rand(100000,999999).'.'.$ext;
					$avatar = $path.$basename;
					$image->resize(200, 200, false)->save($avatar);
					$_POST['avatar'] = $avatar;
				}
				$_POST['created_at'] = time();
				$_POST['updated_at'] = time();
				//$_POST['tags'] = implode(',', $_POST['tags']);
				D('girls')->set($_POST);
				alertSet('新增成功');
				header("Location:girls-list.php");
				break;
			case 'edit':
				$id = intval($_POST['g_id']);
				unset($_POST['g_id']);
				unset($_POST['action']);
				if (empty($_FILES["avatar"]) == false AND $_FILES["avatar"]["error"] == 0) {
					switch ($_FILES["avatar"]["type"]) {
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
					$image = Image::make($_FILES["avatar"]["tmp_name"]);
					$path = 'upload/images/avatar/'.date('Ymd/', time());
					if (file_exists($path) == false) {
						mkdir($path, 0777, true);
					}
					$basename = substr(time(), 5).rand(100000,999999).'.'.$ext;
					$avatar = $path.$basename;
					$image->resize(200, 200, false)->save($avatar);
					$_POST['avatar'] = $avatar;
				}
				$_POST['updated_at'] = time();
				//$_POST['tags'] = implode(',', $_POST['tags']);
				D('girls')->set($_POST, $id);
				alertSet('编辑成功');
				header("Location:".$_SERVER['HTTP_REFERER']);
				break;
			case 'delete':
				$id = intval($_GET['id']);
				D('girls')->del($id);
				alertSet(0, '删除成功');
				header("Location:".$_SERVER['HTTP_REFERER']);
				break;
			case 'follow':
				$where = array('g_id' => $_POST['g_id'], 'user_id' => $_POST['user_id']);
				$follow = D('followers')->one($where);
				if (empty($follow)) {
					$where['created_at'] = time();
					D('followers')->set($where);
				}
				echo json_encode(array('flag' => 1));
				break;
			default:
				# code...
				break;
		}
	}