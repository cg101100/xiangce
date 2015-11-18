<?php
	require_once '../global.php';

	$where = array('state' => 1);
	if (empty($_GET['id'])) {
		die();
	}
	$id = intval($_GET['id']);
	$girl = D('girls')->one($id);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Home</title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Darx Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
		Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<link href='http://fonts.useso.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src='../js/masonry.pkgd.min.js'></script>
		<script type="text/javascript">
		jQuery(function(){

		});
		</script>
	</head>
	<body>
		<div class="container">
			<h3 style="text-align:center;margin-top:10px;"><a onclick="history.go(-1)" style="position:absolute;left:10px;top:6px;" type="button" class="btn btn-info">返回</a>详细资料</h3>
			<table class="table .table-bordered">

				<tr><td>用户</td><td><?php echo $girl['name'];?></td></tr>
				<tr><td>性别</td><td><?php echo $girl['gender'];?></td></tr>
				<tr><td>身高</td><td><?php echo $girl['height'];?>cm</td></tr>
				<tr><td>体重</td><td><?php echo $girl['weight'];?>kg</td></tr>
				<tr><td>胸围</td><td><?php echo $girl['bust'];?>cm</td></tr>
				<tr><td>腰围</td><td><?php echo $girl['waist'];?>cm</td></tr>
				<tr><td>臀围</td><td><?php echo $girl['hip'];?>cm</td></tr>
				<tr><td>职业</td><td><?php echo $girl['job'];?></td></tr>
				<tr><td>地址</td><td><?php echo $girl['location'];?></td></tr>
				<tr><td>签名</td><td><?php echo $girl['sign'];?></td></tr>
			</table>
		</div>

	</body>
</html>