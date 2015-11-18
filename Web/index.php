<?php
	require_once '../global.php';

	$where = array('state' => 1);
	$tags = D('tags')->get();
	$id = intval($_GET['id']);
	if(empty($id)){
		$id = $tags[0]['id'];
	}
	$where['tag_id'] = $id;
	$albums = D('albums')->order('created_at desc')->get($where);
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
		<link href='http://fonts.useso.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<style type="text/css">
		.width1{
			width: 46%;
		}
		.width2{
			width: 30%;
		}
		.ui-loader{
			display: none;
		}
		</style>
		<script src="js/jquery.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
		<script type="text/javascript" src='../js/masonry.pkgd.min.js'></script>
		<script type="text/javascript">
		jQuery(function(){		 

			var container = document.querySelector('.photos');       
			function layout(orientation){
				var width = document.documentElement.clientWidth;
				if (orientation == 'landscape') {
					$('.item').removeClass('width1').addClass('width2').css({margin:width*0.016+'px'});
					msnry = new Masonry( container, {
				        columnWidth: '.width2',
				        itemSelector: '.item',
				        percentPosition: true
				    });
				} else {
					$('.item').removeClass('width2').addClass('width1').css({margin:width*0.012+'px'});
					msnry = new Masonry( container, {
				        columnWidth: '.width1',
				        itemSelector: '.item',
				        percentPosition: true
				    });
				}
				msnry.layout();
				return msnry;
			}

			var msnry;
		    var width = document.documentElement.clientWidth;
		    var height = document.documentElement.clientHeight;
		    if (width < height) {
		    	msnry = layout('portrait');
		    } else {
		    	msnry = layout('landscape');
		    }

            $('.photos .item img').on('load', function(){
            	msnry.layout();
            });

            $(window).on("orientationchange",function(event){
            	msnry.destroy();
            	msnry = layout(event.orientation);
            });
		});
		</script>
	</head>
	<body>
		<div class="container" style="overflow:scroll;border-bottom: 1px solid #ddd;">
			<ul class="nav nav-tabs" style="width:<?php echo count($tags)*66;?>px;border:none;">
				<?php 
				for($i = 0; $i<min(10, count($tags)); $i++){
					echo "<li role='presentation'><a data-ajax='false' href='index.php?id={$tags[$i]['id']}'>{$tags[$i]['text']}</a></li>";
				} ?>
			</ul>
		</div>
		<div class="photos">
			<?php foreach ($albums as $album) { ?>
				<div class="item width1" style="float:left;">
					<p style="position:absolute;bottom:0;padding-left:4px;background:#999;color:#fff;opacity:0.7;height:30px;line-height:30px;width:100%;">
						<?php echo $album['title'];?>
						<span style='color:#fff;float:right;margin-right:1em;'><?php echo $album['images'];?> 张</span>
					</p>
					<a data-ajax='false' href="album.php?id=<?php echo $album['id'];?>">
						<img style="width:100%;" src="<?php echo $baseurl.$album['cover'];?>" >
					</a>
				</div>
			<?php }?>
			<div class="clearfix"> </div>
		</div>

	</body>
</html>