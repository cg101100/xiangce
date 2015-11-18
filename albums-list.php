<?php 
    require_once 'global.php';

    $girlId = intval($_GET['id']);
    $orderBy = 'id DESC';
    $where = array('g_id' => $girlId);
    $albums = D('albums')->order($orderBy)->get($where);
    include 'header.php'; 
?>
        
    <div class="pageheader">
      <h2><i class="fa fa-file-o"></i> 相册列表 <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="index.html">Bracket</a></li>
          <li><a href="calendar.html">Pages</a></li>
          <li class="active">相册列表</li>
        </ol>
      </div>
    </div>
        
    <div class="contentpanel">
        <div class="panel-body panel-body-nopadding">

        <?php 
            $alert = alertGet();
            if (!empty($alert)) {
                echo '<div class="alert alert-'.$alert['flag'].'">';
                echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                echo "<span>{$alert['msg']}</span>";
                echo '</div>';
            }
        ?>
        </div>
      
        <ul class="filemanager-options">
        <li>
            <a href="albums-add.php?id=<?php echo $_GET['id'];?>" class="btn btn-primary btn-xs"><i class="fa fa-file-picture-o"></i>新建相册</a>
        </li>
        <!-- <li>
            <div class="ckbox ckbox-default">
                <input type="checkbox" id="selectall" value="1" />
                <label for="selectall">Select All</label>
            </div>
        </li>
        <li>
          <a href="" class="itemopt disabled"><i class="fa fa-envelope-o"></i> Email</a>
        </li>
        <li>
          <a href="" class="itemopt disabled"><i class="fa fa-download"></i> Download</a>
        </li>
        <li>
          <a href="" class="itemopt disabled"><i class="fa fa-pencil"></i> Edit</a>
        </li>
        <li>
          <a href="" class="itemopt disabled"><i class="fa fa-trash-o"></i> Delete</a>
        </li>
        <li class="filter-type">
          Show:
          <a href="all.html" class="active">All</a>
          <a href="document.html">Documents</a>
          <a href="audio.html">Audio</a>
          <a href="image.html">Images</a>
          <a href="video.html">Videos</a>
        </li> -->
        
      </ul>
        
      
      <div class="row">
        <div class="col-sm-12">
          <div class="row filemanager">

            <?php foreach ($albums as $album) { ?>
            <div class="col-xs-6 col-sm-4 col-md-3 image">
                <div class="thmb">
                    <div class="thmb-prev">
                        <a href="photos-list.php?id=<?php echo $album['id'];?>" data-rel="prettyPhoto">
                            <img style="width:100%;" src="<?php echo $baseurl.$album['cover'];?>" class="img-responsive" alt="" />
                        </a>
                    </div>
                    <h5 class="fm-title"><a href="photos-list.php?id=<?php echo $album['id'];?>"><?php echo $album['title'];?></a></h5>
                    <div style="line-height:27px;">
                        <small class="text-muted">创建日期: <?php echo date('Y-m-d', $album['created_at']);?></small>
                        <button data-msg="确定删除吗？数据删除之后无法撤销" data-url="albums-agent.php?action=delete&id=<?php echo $album['id'];?>" style="float:right;" type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-modal-alert">删除</button>
                        <a href="albums-edit.php?id=<?php echo $album['id'];?>" style="float:right;margin-right:10px;" type="button" class="btn btn-primary btn-xs">编辑</a>
                        <div class="clearfix"></div>
                    </div>
                    
                </div><!-- thmb -->
            </div><!-- col-xs-6 -->
            
            <?php }?>
            
            
          </div><!-- row -->
        </div><!-- col-sm-9 -->
      </div>
    </div>
    
  </div><!-- mainpanel -->
  <script src="js/masonry.pkgd.min.js"></script>
<script type="text/javascript">
    jQuery(function(){
        var container = document.querySelector('.filemanager');
        var msnry = new Masonry( container, {
            columnWidth: '.col-xs-6',
            itemSelector: '.col-xs-6'
        });
    });
</script>
  <?php include 'footer.php'; ?>