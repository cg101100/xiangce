<?php
	session_start();

	function alertSet($msg, $flag = 0){
		$types = array('success', 'info', 'warning', 'danger');
		$_SESSION['alert'] = array('flag' => $types[$flag], 'msg' => $msg);
	}

	function alertGet(){
		$alert = $_SESSION['alert'];
		unset($_SESSION['alert']);
		return $alert;
	}

	function go($url, $msg = NULL, $msgFlag = 0){
		if ($url == -1) {
			$url = $_SERVER['HTTP_REFERER'];
		}
		if(empty($msg) == false){
			if (is_array($msg)) {
				$msgFlag = $msgFlag OR (count($msg) > 1 ? $msg[1] : 0);
				$msg = $msg[0];
			}
			alertSet($msg, $msgFlag);
		}
		header("Location: $url");
	}
	