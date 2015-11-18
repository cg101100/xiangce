<?php
	require_once '../global.php';

	$id = intval($_GET['id']);
	if (empty($id)) {
		die();
	}
	
	$girl = D('girls')->one($id);
	$albums = D('albums')->get(array('g_id' => $id, 'state' => 1));
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			魅影相册
		</title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="Darx Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
		Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<link href='http://fonts.useso.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<style type="text/css">
		#images{
			position: relative;
			overflow: hidden;
			width: 100%;
			height: 300px;
		}
		#images #image-view{
			position: absolute;
			height: 300px;
		}
		#images #image-view .image-items{
			float: left;
		}
		#images #image-view .image-items img{
			width: 100%;
		}
		#tab{
			height: 40px;
			line-height: 40px;
			border-bottom: 1px solid #ccc;
		}
		#tab a{
			float: left;
			width: 25%;
			text-align: center;
			font-size: 18px;
		}
		</style>
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src='../js/masonry.pkgd.min.js'></script>
		<script type="text/javascript">

			jQuery(function(){
				var width = document.documentElement.clientWidth;

				var height = document.documentElement.clientHeight;
			});
		</script>
	</head>
	<body>
		<div class="container">
			<h3 style="text-align:center;margin-top:10px;"><a onclick="history.go(-1)" style="position:absolute;left:10px;top:6px;" type="button" class="btn btn-info">返回</a>女孩主页 </h3>
			<table style="table-layout:fixed;font-size:16px;width:100%;">
				<tr>
					<td style="width:80px;"></td>
					<td style="width:80px;"></td>
					<td></td>
				</tr>
				<tr> 
					<td rowspan="2" style="width:80px;">
						<img src="<?php echo $baseurl.$girl['avatar'];?>" style="width:100%;">
					</td>
					<td style="padding-left:10px;font-size:14px;" colspan="2">
						<strong style='font-size:18px;margin-right:10px;'><?php echo $girl['name'];?></strong>
						护花使者：周杰伦
						<a style="float:right;margin-top:4px;" href="girl.php?id=<?php echo $girl['id'];?>">详细资料&gt;</a>
					</td>
				</tr>
				<tr>
					<td style="padding-left:10px;"><button type="button" class="btn btn-danger btn-sm">+ 关注</button></td>
					<td style="font-size:14px;">
						关注:<span style='color:red;'>302254</span>
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding:5px 0;line-height:1.4;">
						<?php echo $girl['sign'];?>
					</td>
				</tr>
			</table>

			<div class="row" style="border-top:1px solid #ccc;">
				<?php foreach ($albums as $album) { ?>
					<div class="col-xs-4" style="margin-top:10px;">
						<a href="album.php?id=<?php echo $album['id'];?>">
							<img class="img-responsive" src="<?php echo $baseurl.$album['cover'];?>">
						</a>
						<a href="album.php?id=<?php echo $album['id'];?>">
							<span><?php echo $album['title'];?></span>
						</a>
					</div>
				<?php }?>
			</div>
		</div>
		
	</body>
</html>