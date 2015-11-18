<?php
	require_once 'global.php';
	use Intervention\Image\Image;


	$action = $_REQUEST['action'];
	unset($_POST['action']);
	if (isset($action)) {
		switch ($action) {
			case 'add':
				
				$mobile = $_POST['mobile'];
				$user = D('users')->one(array('mobile' => $mobile));
				if (empty($user)) {
					$_POST['created_at'] = time();
					$insertId = D('users')->set($_POST);
					$_SESSION['id'] = $insertId;
				}
				else{

				}
				break;
			case 'login':
				$mobile = $_POST['mobile'];
				$user = D('users')->one(array('mobile' => $mobile));
		
				if (! empty($user)) {
					$_SESSION['id'] = $user['id'];
					echo json_encode(array('flag' => 1));
				}
				else{
					echo json_encode(array('flag' => 0));
				}
				break;
			case 'logout':
				unset($_SESSION['id']);
				break;
			case 'edit':
				$id = intval($_POST['g_id']);
				unset($_POST['g_id']);
				unset($_POST['action']);
				$_POST['updated_at'] = time();
				$_POST['tags'] = implode(',', $_POST['tags']);
				D('girls')->set($_POST, $id);
				alertSet(0, '编辑成功');
				header("Location:".$_SERVER['HTTP_REFERER']);
				break;
			case 'delete':
				$id = intval($_GET['id']);
				D('girls')->del($id);
				alertSet(0, '删除成功');
				header("Location:".$_SERVER['HTTP_REFERER']);
				break;
			default:
				# code...
				break;
		}
	}