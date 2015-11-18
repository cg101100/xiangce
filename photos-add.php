<?php 
    require_once 'global.php';
    $albumId = intval($_GET['id']);
    include 'header.php'; 
?>



    
    <div class="pageheader">
      <h2><i class="fa fa-edit"></i> 新增相片 <span>Subtitle goes here...</span></h2>
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
      
      <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="" class="panel-close">&times;</a>
                <a href="" class="minimize">&minus;</a>
            </div>
            <h4 class="panel-title">上传相片</h4></div>
        <div class="panel-body panel-body-nopadding">

            <form method="POST" id="photos-upload" action="photos-upload.php" class="dropzone" enctype="multipart/form-data">
                <div class="fallback">
                    <input name="photo" type="file" multiple >
                </div>
            </form>
          
        </div><!-- panel-body -->

        <div class="panel-footer">
             <div class="row">
                <div class="col-sm-60">
                  <a href="photos-list.php?id=<?php echo $albumId;?>" class="btn btn-primary">返回相册集</a>
                </div>
             </div>
          </div>
        
      </div><!-- panel -->
      
    </div><!-- contentpanel -->
  </div><!-- mainpanel -->

<script type="text/javascript">
    jQuery(function(){
        $("#photos-upload").dropzone({
            url: "photos-upload.php?id=<?php echo $albumId;?>",
            paramName: 'photo',
            addRemoveLinks: true, 
            dictRemoveFile: '移除图片',
            acceptedFiles: 'image/*',
            init: function() {
                this.on("success", function(file, resp) {
                    //file.id = resp;
                }),
                this.on("removedfile", function(file) {
                    console.dir( file );
                });
            }
        });
    });
</script>
<?php include 'footer.php'; ?>

	