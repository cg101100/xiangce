<?php include 'header.php'; ?>



    
    <div class="pageheader">
      <h2><i class="fa fa-edit"></i> 新增女孩 <span>Subtitle goes here...</span></h2>
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
          <h4 class="panel-title">填写女孩详细资料</h4>
          <p></p>
        </div>
        <div class="panel-body panel-body-nopadding">
          
          <form class="form-horizontal form-bordered" method="POST" action="girls-agent.php" enctype="multipart/form-data">
            <input name="action" type="hidden" value="add">
            <div class="form-group">
              	<label class="col-sm-3 control-label">姓名</label>
              	<div class="col-sm-6">
                	<input type="text" name="name" class="form-control" />
              	</div>
            </div>

            <!-- <div class="form-group">
                <label class="col-sm-3 control-label">头像</label>
                <div class="col-sm-6">
                    <input type="file" name="avatar" class="form-control" />
                </div>
            </div> -->

            <div class="form-group">
                <label class="col-sm-3 control-label">头像</label>
                <div class="col-sm-6 fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                        <img data-src="holder.js/100%x100%/text:Fluid image" alt="Fluid image [100%x100%]" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTkwIiBoZWlnaHQ9IjE0MCIgdmlld0JveD0iMCAwIDE5MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMTkwIiBoZWlnaHQ9IjE0MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjU3Ljk1MzEyNSIgeT0iNzAiIHN0eWxlPSJmaWxsOiNBQUFBQUE7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LWZhbWlseTpBcmlhbCwgSGVsdmV0aWNhLCBPcGVuIFNhbnMsIHNhbnMtc2VyaWYsIG1vbm9zcGFjZTtmb250LXNpemU6MTBwdDtkb21pbmFudC1iYXNlbGluZTpjZW50cmFsIj5GbHVpZCBpbWFnZTwvdGV4dD48L2c+PC9zdmc+" data-holder-rendered="true" style="height: 100%; width: 100%; display: block;">
                    </div>
                    <div class="fileinput-preview fileinput-exists thumbnail" data-trigger="fileinput" style="max-width: 200px; max-height: 150px;">
                        
                    </div>
                    <div>
                        <span class="btn btn-default btn-file">
                            <span class="fileinput-new">选择图片</span>
                            <span class="fileinput-exists">更换图片</span>
                            <input type="file" name="avatar">
                        </span>
                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">移除图片</a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">性别</label>
                <div class="col-sm-6">
    				<div class="radio"><label><input name="gender" value="0" type="radio" checked=""> 女</label></div>
    				<div class="radio"><label><input name="gender" value="1" type="radio"> 男</label></div>
    				<div class="radio"><label><input name="gender" value="2" type="radio"> 其他</label></div>
    			</div>
           	</div>

            <div class="form-group">
              	<label class="col-sm-3 control-label">身高</label>
              	<div class="col-sm-6">

	                <div class="input-group mb15">
	                  	<input type="text" name="height" class="form-control">
	                  	<span class="input-group-addon">cm</span>
	                </div>
              	</div>
            </div>


            <div class="form-group">
              	<label class="col-sm-3 control-label">体重</label>
              	<div class="col-sm-6">

	                <div class="input-group mb15">
	                  	<input type="text" name="weight" class="form-control">
	                  	<span class="input-group-addon">kg</span>
	                </div>
              	</div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">三围</label>
                <div class="col-sm-2">
                    <input type="text" name="bust" placeholder="胸围" class="form-control">
                </div>
                <div class="col-sm-2">
                    <input type="text" name="waist" placeholder="腰围" class="form-control">
                </div>
                <div class="col-sm-2">
                    <input type="text" name="hip" placeholder="臀围" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">签名</label>
                <div class="col-sm-6">
                    <textarea name="sign" class="form-control" rows="5"></textarea>
                </div>
            </div>

            <!-- <div class="form-group">
              <label class="col-sm-3 control-label">类型</label>
              <div class="col-sm-6">
                <select class="select2" name="tags[]" multiple data-placeholder="选择类型...">
                  	<option value="">热门</option>
                  	<option value="清纯">清纯</option>
                  	<option value="性感">性感</option>
                  	<option value="萝莉">萝莉</option>
                  	<option value="明星">明星</option>
                  	<option value="动漫COS">动漫COS</option>
                  	<option value="模特">模特</option>
                  	<option value="新秀">新秀</option>
                  
                </select>
              </div>
            </div> -->

            <div class="form-group">
              	<label class="col-sm-3 control-label">职业</label>
              	<div class="col-sm-6">
                	<input type="text" name="job" class="form-control" />
              	</div>
            </div>

            <div class="form-group">
              	<label class="col-sm-3 control-label">所在地区</label>
              	<div class="col-sm-6">
                	<input type="text" name="location" class="form-control" />
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

	