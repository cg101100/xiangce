<?php
	require_once '../global.php';

	$id = intval($_GET['id']);
	if (empty($id)) {
		die();
	}
	
	$photo = D('photos')->one($id);
	$photos = D('photos')->get(array('album_id' => $photo['album_id'], 'state' => 1));
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="stylesheet" href="css/photoswipe.css"> 

	<!-- Skin CSS file (styling of UI - buttons, caption, etc.)
	     In the folder of skin CSS file there are also:
	     - .png and .svg icons sprite, 
	     - preloader.gif (for browsers that do not support CSS animations) -->
	<link rel="stylesheet" href="js/default-skin/default-skin.css"> 

	<!-- Core JS file -->
	<script src="js/photoswipe.min.js"></script> 

	<!-- UI JS file -->
	<script src="js/photoswipe-ui-default.min.js"></script> 
</head>
<body>
	<!-- <button id="btn">Open PhotoSwipe</button> -->

	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

	    <!-- Background of PhotoSwipe. 
	         It's a separate element, as animating opacity is faster than rgba(). -->
	    <div class="pswp__bg"></div>

	    <!-- Slides wrapper with overflow:hidden. -->
	    <div class="pswp__scroll-wrap">

	        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
	        <div class="pswp__container">
	            <!-- don't modify these 3 pswp__item elements, data is added later on -->
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	            <div class="pswp__item"></div>
	        </div>

	        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
	        <div class="pswp__ui pswp__ui--hidden">

	            <div class="pswp__top-bar">

	                <!--  Controls are self-explanatory. Order can be changed. -->

	                <div class="pswp__counter"></div>

	                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

	                <!-- <button class="pswp__button pswp__button--share" title="Share"></button> -->

	                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

	                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

	                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
	                <!-- element will get class pswp__preloader--active when preloader is running -->
	                <div class="pswp__preloader">
	                    <div class="pswp__preloader__icn">
	                      <div class="pswp__preloader__cut">
	                        <div class="pswp__preloader__donut"></div>
	                      </div>
	                    </div>
	                </div>
	            </div>

	            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
	                <div class="pswp__share-tooltip"></div> 
	            </div>

	            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
	            </button>

	            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
	            </button>

	            <div class="pswp__caption">
	                <div class="pswp__caption__center"></div>
	            </div>

	          </div>

	        </div>

	</div>
	<script type="text/javascript">
	window.onload = function(){
		var openPhotoSwipe = function() {
		    var pswpElement = document.querySelectorAll('.pswp')[0];

		    // build items array
		    var items = <?php
		    	$out = array();
		    	$index = 0;
		    	$found = false;
		    	foreach ($photos as $val) {
		    		if ($val['id'] == $id) {
		    			$found = true;
		    		} else if(!$found){
		    			$index++;
		    		}
		    		$out[] = array(
		    			'src' => $baseurl.$val['src'],
		    			'w' => $val['width'],
		    			'h' => $val['height']
		    		);
		    	}
		    	echo json_encode($out);
		    ?> 
		    
		    // define options (if needed)
		    var options = {
		        // history & focus options are disabled on CodePen        
		        history: false,
		        focus: false,
		        index: <?php echo $index;?>,
		        showAnimationDuration: 0,
		        hideAnimationDuration: 0
		        
		    };
		    
		    var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		    gallery.init();
		    gallery.listen('close', function() {
		    	history.go(-1)
		    });
		};

		openPhotoSwipe();

		//document.getElementById('btn').onclick = openPhotoSwipe;
	}
	</script>
</body>
</html>