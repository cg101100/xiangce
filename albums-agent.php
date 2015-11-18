<?php
	require_once 'global.php';
	use Intervention\Image\Image;

	$action = $_REQUEST['action'];
	unset($_POST['action']);
	if (isset($action)) {
		switch ($action) {
			case 'add':
				
				$_POST['created_at'] = time();
				$_POST['updated_at'] = time();
				if (empty($_FILES["cover"]) == false AND $_FILES["cover"]["error"] == 0) {
					switch ($_FILES["cover"]["type"]) {
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
					$image = Image::make($_FILES["cover"]["tmp_name"]);
					$path = 'upload/images/cover/'.date('Ymd/', time());
					if (file_exists($path) == false) {
						mkdir($path, 0777, true);
					}
					$basename = substr(time(), 5).rand(100000,999999).'.'.$ext;
					$cover = $path.$basename;
					$image->resize(300, 200, true)->save($cover);
					$_POST['cover'] = $cover;
				}
				D('albums')->set($_POST);
				alertSet('新增成功');
				header("Location:albums-list.php?id=".$_POST['g_id']);
				break;
			case 'edit':
				$id = intval($_POST['id']);
				unset($_POST['id']);

				$_POST['updated_at'] = time();
				if (empty($_FILES["cover"]) == false AND $_FILES["cover"]["error"] == 0) {
					switch ($_FILES["cover"]["type"]) {
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
					$image = Image::make($_FILES["cover"]["tmp_name"]);
					$path = 'upload/images/cover/'.date('Ymd/', time());
					if (file_exists($path) == false) {
						mkdir($path, 0777, true);
					}
					$basename = substr(time(), 5).rand(100000,999999).'.'.$ext;
					$cover = $path.$basename;
					$image->resize(300, 200, true)->save($cover);
					$_POST['cover'] = $cover;
				}
				D('albums')->set($_POST, $id);
				go(-1, '编辑成功');
				break;
			case 'delete':
				$id = intval($_REQUEST['id']);
				D('albums')->del($id);
				D('photos')->set(array('state' => 0), array('album_id' => $id));
				go(-1, '删除成功');
				break;
			case 'up'://赞
				$id = intval($_REQUEST['id']);
				if (! empty($id)) {
					$album = D('albums')->one($id);
					if (!empty($album)) {
						$userId = intval($_REQUEST['user_id']);
						$where = array('album_id' => $id, 'user_id' => $userId);
						$record = D('up_records')->one($where);
						if (empty($record)) {
							D('albums')->set(array('up' => $album['up']+1, $id));
							D('up_records')->set($where);
						}
					}
					
				}
				break;
			case 'comment'://评论
				$id = intval($_REQUEST['id']);
				if (! empty($id)) {
					$album = D('albums')->one($id);
					if (!empty($album)) {
						$userId = intval($_REQUEST['user_id']);
						$user = D('users')->one($userId);

						$content = htmlspecialchars($_POST['content']);
						if (strlen($content)) {
							$insertId = D('comments')->set(array('album_id' => $id, 'user_id' => $userId, 'content' => $comment));
							if ($insertId) {
								D('albums')->set(array('comments' => $album['comments']+1, $id));
								echo json_encode(array('flag' => 1, 'data' => array('name' => $user['name'], 'content' => $content)));
							}
						} else {
							echo json_encode(array('flag' => 0, 'errinfo' => '请输入评论内容'));
						}

						
					}
				} else {
					echo json_encode(array('flag' => 0));
				}
				break;
			default:
				# code...
				break;
		}
	}