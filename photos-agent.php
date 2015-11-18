<?php
	require_once 'global.php';

	$action = $_REQUEST['action'];
	if (isset($action)) {
		switch ($action) {
			case 'add':
				break;
			case 'edit':
				break;
			case 'delete':
				$id = intval($_REQUEST['id']);
				if (empty($_REQUEST['r']) == false) {
					$photo = D('photos')->one($id);
					if (false == empty($photo)) {
						unlink($photo['src']);
						unlink($photo['thumb']);
						D('photos')->del($id);
					}
				} else{
					D('photos')->set(array('state' => 0), $id);
				}
				if ($_REQUEST['notajax'] == 1) {
					header("Location:".$_SERVER['HTTP_REFERER']);
				}
				break;
			default:
				# code...
				break;
		}
	}