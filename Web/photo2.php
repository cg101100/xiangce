<?php
	require_once '../global.php';

	$id = intval($_GET['id']);
	if (empty($id)) {
		die();
	}
	
	$photo = D('photos')->one($id);
	$photos = D('photos')->get(array('album_id' => $photo['album_id'], 'state' => 1));
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>魅影相册</title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="">
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src='js/jquery.events.js'></script>
		<script type="text/javascript" src='js/jquery.swipe.js'></script>
		<script type="text/javascript">
		jQuery(function(){
			var	width = document.documentElement.clientWidth;
			$('.image-items').css({width: width});
			var index = parseInt($('input[name=index]').val());
			var len = $('#image-view .image-items').length;
			$('#image-view').css({left: '-'+index+'00%'});
			$(document.body).on('swipeleft', function(){
				if (index != len-1) {
					index++;
					$('#image-view').animate({left: '-'+index+'00%'});
				}
			}).on('swiperight', function(){
				if (index != 0) {
					index--;
					$('#image-view').animate({left: '-'+index+'00%'});
				}
			});
		});
		</script>
	</head>
	<body>
		<div style="width:100%;position:relative;">
			<div id="image-view" style="width:<?php echo count($photos);?>00%;position:absolute;">
				<?php 

				$index = 0;
				$found = false;
				foreach ($photos as $val) {
					if ($val['id'] == $id) {
						$found = true;
					} else if(!$found){
						$index++;
					}

					echo "<a class='image-items' style='float:left;'><img class='img-responsive' src='{$baseurl}{$val['src']}'></a>";
				} 

				echo "<div class='clear'></div>";
				echo "<input name='index' type='hidden' value='$index' >";
				?>
			</div>
		</div>
	</body>
</html>