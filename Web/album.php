<?php
	require_once '../global.php';

	if (empty($_GET['id'])) {
		die();
	}
	$id = intval($_GET['id']);
	$album = D('albums')->one($id);
	$photos = D('photos')->get(array('album_id' => $id, 'state' => 1));
	$comments = D('comments')->get(array('album_id' => $id, 'state' => 1));
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>魅影相册</title>
		<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="">
		<meta name="user_id" content="<?php echo $_SESSION['id'];?>">
		<meta name="g_id" content="<?php echo $album['g_id'];?>">
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
		#chat-box{
			overflow: scroll;
			padding: 0 10px;
		}
		#chat-box .msg-item{
			margin-bottom: 5px;
		}
		</style>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" src='../js/masonry.pkgd.min.js'></script>
		<script type="text/javascript" src='js/jquery.events.js'></script>
		<script type="text/javascript" src='js/jquery.swipe.js'></script>
	<script type="text/javascript">
		jQuery(function(){
			var width = document.documentElement.clientWidth;
			var index = 0;
			var len = $('#images #image-view .image-items').length;

			$('#images #image-view .image-items').css({width: width}).on('swipeleft', function(){
				console.log('swipeleft');
				if (index != len-1) {
					index++;
					$('#image-view').animate({left: '-'+index+'00%'});
				}
			}).on('swiperight', function(){
				console.log('swiperight');
				if (index != 0) {
					index--;
					$('#image-view').animate({left: '-'+index+'00%'});
				}
			});

			var height = document.documentElement.clientHeight;
			$('#chat-box').css({height:height-350-$('.container')[0].offsetHeight-$('.form-inline')[0].offsetHeight});
			
			function checkUser(){
				var userId = $('meta[name="user_id"]').attr('content');
				if (userId) {
					return userId;
				}

				$('#loginModal').modal();
				return false;
			}

			$('#follow-girl').click(function(){
				var userId = checkUser();
				if (userId) {
					var girlId = $('meta[name="g_id"]').attr('content');
					$.post('../girls-agent.php', {'action': 'follow', 'g_id': girlId, 'user_id': userId}, function(data){
						if (data.flag == 1) {
							alert('关注成功');
						}
					}, 'json');
				}
			});

			$('#send-msg').click(function(){
				var userId = checkUser();
				if (userId) {
					var msg = $('input[name=message]').val();
					var id = $('input[name=album_id]').val();
					if (msg.length) {
						$.post('../albums-agent.php', {action: 'comment', id: id, user_id: userId, content: msg}, function(data){
							if (data.flag) {
								var div = document.createElement('DIV');
								div.className = 'msg-item';
								div.innerHTML = '<span>'+data.data.name+':</span><span>'+data.data.content+'</span>';
								$('#chat-box')[0].appendChild(div);
							} else {
								alert(data.errinfo);
							}
						}, 'json');
					}
				}
				
			});

			$('#login-btn').click(function(){
				var mobile = $('form#login-form input[name=mobile]').val();
				var code = $('form#login-form input[name=code]').val();

				if (/^1\d{10}$/.test(mobile)) {
					var postData = {
						action: 'login',
						mobile: mobile
					};
					$.post('../users-agent.php', postData, function(data){
			
						if (data.flag) {
							location.reload();
						} else {
							alert('登录失败');
						}
					}, 'json');
				}
			});


		});
		</script>
	</head>
	<body>
		<div class="container">
			<h3 style="text-align:center;margin-top:10px;">
				<a onclick="history.go(-1)" style="position:absolute;left:10px;top:6px;" type="button" class="btn btn-info">返回</a>
				浏览相册
				<a href="home.php?id=<?php echo $album['g_id'];?>" style="position:absolute;right:10px;top:6px;" type="button" class="btn btn-info">更多</a></h3>
		</div>
		<div id="images">
			<div id="image-view" style="width:<?php echo count($photos);?>00%">
				<?php foreach ($photos as $key => $value) { ?>
					<div class="image-items">
						<a href="photo.php?id=<?php echo $value['id'];?>"><img src="<?php echo $baseurl.$value['thumb'];?>"></a>
					</div>
				<?php }?>
				<div class="clearfix"></div>
			</div>
		</div>
		<div id="tab">
			<a href="###">聊天</a>
			<a href="###" id="follow-girl">关注</a>
			<a href="girl.php?id=<?php echo $album['g_id'];?>">资料</a>
			<a href="###" id="up-girl">点赞</a>
			<div class="clearfix"></div>
		</div>
		<div id="chat-box">
			<?php 
			foreach ($comments as $value) {
				$user = D('user')->one($value['user_id']);
				if (!empty($user)) {
					echo '<div class="msg-item">';
					echo "<span>{$user['name']}:</span>";
					echo "<span>{$value['content']}</span>";
					echo "</div>";
				}
			}
			?>
			

		</div>
		<form class="form-inline">
			<table style="table-layout:fixed;width:98%;margin:5px auto;">
				<tr>
					<td><input name="message" style="width:98%;height:44px;"  class="form-control" id="message"></td>
					<td style="width:100px;">
						<input name="album_id" type="hidden" value="<?php echo $id;?>">
						<input id="send-msg" style="width:100%;height:44px;font-size:18px;" type="button" class="btn btn-primary" value="发送">
					</td>
				</tr>
			</table>			
		</form>


		<div class="modal fade in" id="loginModal" tabin x="-1" role="dialog" aria-labelledby="exampleModalLabel" style="display: none; padding-right: 15px;">
		      <div class="modal-dialog modal-sm" role="document" style="width:300px;margin:10px auto;">
		        <div class="modal-content">
		          <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		            <h4 class="modal-title" id="exampleModalLabel">请先登录</h4>
		          </div>
		          <div class="modal-body">
		            <form id="login-form">
		              <div class="form-group">
		                <label for="recipient-name" class="control-label">手机号:</label>
		                <input name="mobile" type="text" class="form-control" id="mobile">
		              </div>
		              <div class="form-group">
		                <label for="message-text" class="control-label">验证码:</label>
		              <input name="code" type="text" class="form-control" id="code">
		              </div>
		            </form>
		          </div>
		          <div class="modal-footer">
		            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
		            <button id="login-btn" type="button" class="btn btn-primary">确认</button>
		          </div>
		        </div>
		      </div>
		    </div>
	</body>
</html>