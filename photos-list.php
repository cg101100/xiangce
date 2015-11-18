<?php 
    require_once 'global.php';

    $albumId = intval($_GET['id']);
    $album = D('albums')->one($albumId);
    if (!$albumId OR empty($album)) {
        
    }
    $girlId = $album['g_id'];
    $girl = D('girls')->one($girlId);
    if (empty($girl)) {
        
    }
    $where = array('album_id' => $albumId, 'state' => 1);
    $photos = D('photos')->order('created_at DESC and id desc')->get($where);

    include 'header.php'; 
?>



    
    <div class="pageheader">
      <h2><i class="fa fa-edit"></i> 相册集 <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="index.html">Bracket</a></li>
          <li><a href="general-forms.html">Forms</a></li>
          <li class="active">General Forms</li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
        <ul class="filemanager-options">
            <li>
                <a href="photos-add.php?id=<?php echo $_GET['id'];?>" class="btn btn-primary btn-xs"><i class="fa fa-file-picture-o"></i>上传相片</a>
            </li>
        </ul>
        
        <div id="bloglist" class="row">

            <?php 
                echo "<div id='photos-source' style='display:none;'>".json_encode($photos)."</div>";
                $index = 0;
                foreach ($photos as $photo) { 
            ?>
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="blog-item">
                        <a href="javascript:;" class="blog-img" data-index="<?php echo $index;?>" data-image="<?php echo $photo['src'];?>" data-toggle="modal" data-target=".bs-modal-photo-view">
                            <img style="width:100%;" src="<?php echo $baseurl.$photo['thumb'];?>" class="img-responsive" alt="" />
                        </a>
                  </div><!-- blog-item -->
                </div><!-- col-xs-6 -->
            <?php $index++; }?>
            
        </div>
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->

    <div class="modal fade bs-modal-photo-view in" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false" style="display: none;">
      <div class="modal-dialog modal-photo-viewer">
        <div class="modal-content" style=" border: 2px solid #fff;"><div class="row">
        
        <div class="col-sm-9 modal-photo-left">
            <div class="modal-photo">
                <img src="http://localhost/xiangce/upload/images/origin/20151112/53100858166.jpg" class="photo img-responsive" alt="">
            </div>
        </div>
        
        <div class="col-sm-3 modal-photo-right">
            <div class="media-details">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                
                <!-- <span class="categ">Portrait / Women</span> -->
                <h3 class="media-title">Party Girl At Disco</h3>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis</p>
            
                <div class="details">
                    <h4>相片信息</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="40%"><span class="fa fa-laptop"></span> <strong>上传日期</strong></td>
                                <td width="60%" style="text-align: right;">January 30, 2013</td>
                            </tr>
                            <tr>
                                <td><span class="fa fa-thumbs-up"></span> &nbsp;<strong>Like</strong></td>
                                <td style="text-align: right;">16</td>
                            </tr>
                            <tr>
                                <td><span class="fa fa-download"></span> &nbsp;<strong>Downloads</strong></td>
                                <td style="text-align: right;">4,124</td>
                            </tr>
                            <tr>
                                <td><span class="fa fa-eye"></span> &nbsp;<strong>Views</strong></td>
                                <td style="text-align: right;">20,130</td>
                            </tr>
                            <tr>
                                <td><span class="fa fa-link"></span> &nbsp;<strong>URL</strong></td>
                                <td style="text-align: right;"><a href="">http://thmpx.ls/24</a></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary btn-xs prev">上一张</button>
                    <button class="btn btn-primary btn-xs next">下一张</button>
                    <a style="float:right;" href='javascript:;' class="btn btn-danger btn-xs delete">删除</a>
                </div><!--details-->

            </div><!-- media-details -->
        </div><!-- modal-photo-right -->
        
    </div><!-- row -->
    </div>
      </div>
    </div>

<script src="js/masonry.pkgd.min.js"></script>
<script type="text/javascript">
    jQuery(function(){
        var width = document.documentElement.clientWidth;

        $('.modal-photo-viewer .modal-content').css({width: Math.min(width, 1200)});

        var container = document.querySelector('#bloglist');
        var msnry = new Masonry( container, {
            columnWidth: '.col-xs-6',
            itemSelector: '.col-xs-6'
        });
        
        // check on load
        if(jQuery(window).width() <= 480 )
            msnry.destroy();

        // check on resize
        jQuery(window).resize(function(){
            if(jQuery(this).width() <= 480 )
                msnry.destroy();
        });
        
        // relayout items when clicking chat icon
        jQuery('#chatview, .menutoggle').click(function(){
            msnry.layout();
        });

        $('#bloglist .col-xs-6 img').on('load', function(){
            msnry.layout();
        });

        var photos = JSON.parse($('#photos-source').html());
        $('.bs-modal-photo-view').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var modal = $(this)           

            var index = parseInt(button.data('index'));
            modal.find('.img-responsive').attr({src:photos[index]['src']})
            if(index != 0){
                modal.find('.prev').attr({'data-index': index-1}).show();
            }
            else{
                modal.find('.prev').hide()
            }
            if(index != photos.length-1){
                modal.find('.next').attr({'data-index': index+1}).show();
            }
            else{
                modal.find('.next').hide()
            }
            modal.find('.delete').prop({href:'photos-agent.php?action=delete&notajax=1&id='+photos[index]['id']})
        })
        $('.bs-modal-photo-view .prev, .bs-modal-photo-view .next').click(function(event){
            var index = parseInt($(this).data('index'));
            console.log(index)
            var index = parseInt($(this).attr('data-index'));
            console.log(index)
            // if ($(this).hasClass('prev')) {
            //     index--;
            // } else {
            //     index++;
            // }

            var modal = $('.bs-modal-photo-view') 
            modal.find('.img-responsive').attr({src:photos[index]['src']})
            if(index != 0){
                modal.find('.prev').attr({'data-index': index-1}).show();
            }
            else{
                modal.find('.prev').hide()
            }
            if(index != photos.length-1){
                modal.find('.next').attr({'data-index': index+1}).show();
            }
            else{
                modal.find('.next').hide()
            }
            modal.find('.delete').prop({href:'photos-agent.php?action=delete&notajax=1&id='+photos[index]['id']})
        });
    });
</script>
<?php include 'footer.php'; ?>

	