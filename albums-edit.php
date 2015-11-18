<?php 
    require_once 'global.php';
    $id = intval($_GET['id']);
    if (false == empty($id)) {
        $album = D('albums')->one($id);
    }
    
    $tags = D('tags')->order('id ASC')->get();
    include 'header.php'; 
?>



    
    <div class="pageheader">
      <h2><i class="fa fa-edit"></i> 编辑相册 <span>Subtitle goes here...</span></h2>
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
      
      <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="" class="panel-close">&times;</a>
                <a href="" class="minimize">&minus;</a>
            </div>
            <h4 class="panel-title">填写相册信息</h4></div>
        <div class="panel-body panel-body-nopadding">
          
          <form class="form-horizontal form-bordered" method="POST" action="albums-agent.php" enctype="multipart/form-data">
            <input name="action" type="hidden" value="edit">
            <input name="id" type="hidden" value="<?php echo $id;?>">

            <div class="form-group">
              	<label class="col-sm-3 control-label">标题</label>
              	<div class="col-sm-6">
                	<input type="text" name="title" class="form-control" value="<?php echo $album['title'];?>">
              	</div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">标签</label>
              <div class="col-sm-6">
                <select class="select2-nosearch" name="tag_id"  data-placeholder="选择标签...">
                    <?php foreach ($tags as $tag) {
                        echo "<option ".($tag['id'] == $album['tag_id'] ? 'selected="selected"' : '')." value='{$tag['id']}'>{$tag['text']}</option>";
                    }?>
                  
                </select>
              </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">封面</label>
                <div class="col-sm-6 fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 300px; height: 200px;">
                        <?php if(empty($album['cover'])){?>
                        <img data-src="holder.js/100%x100%/text:Fluid image" alt="Fluid image [100%x100%]" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTkwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE5MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMTkwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjU3Ljk1MzEyNSIgeT0iNzAiIHN0eWxlPSJmaWxsOiNBQUFBQUE7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LWZhbWlseTpBcmlhbCwgSGVsdmV0aWNhLCBPcGVuIFNhbnMsIHNhbnMtc2VyaWYsIG1vbm9zcGFjZTtmb250LXNpemU6MTBwdDtkb21pbmFudC1iYXNlbGluZTpjZW50cmFsIj5GbHVpZCBpbWFnZTwvdGV4dD48L2c+PC9zdmc+" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                        <?php } else { ?>
                        <img data-src="holder.js/100%x100%/text:Fluid image" src="<?php echo $baseurl.$album['cover'];?>">
                        <?php }?>
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" data-trigger="fileinput" style="max-width: 200px; max-height: 150px;">
                        
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                            <span class="fileinput-new">选择图片</span>
                            <span class="fileinput-exists">更换图片</span>
                            <input type="file" name="cover">
                        </span>
                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除图片</a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <input type="submit" value="确 认" class="btn btn-primary">
                </div>
            </div>

          </form>
          
        </div><!-- panel-body -->
        
      </div><!-- panel -->
      
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->
    <script type="text/javascript" src='http://laravel.lc/blackon/assets/global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js'></script>


<?php include 'footer.php'; ?>

	