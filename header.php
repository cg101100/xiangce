<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>魅影相册</title>

  <link rel="stylesheet" href="css/style.default.css" />
  <link rel="stylesheet" type="text/css" href="http://laravel.lc/blackon/assets/global/plugins/bower_components/jasny-bootstrap-fileinput/css/jasny-bootstrap-fileinput.min.css">
  <link rel="stylesheet" href="css/bootstrap-timepicker.min.css" />
  <link rel="stylesheet" href="css/jquery.tagsinput.css" />
  <link rel="stylesheet" href="css/colorpicker.css" />
  <link rel="stylesheet" href="css/dropzone.css" />

  <script src="js/jquery-1.11.1.min.js"></script>
  <script src="js/jquery-migrate-1.2.1.min.js"></script>
  <script src="js/jquery-ui-1.10.3.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/modernizr.min.js"></script>
  <script src="js/jquery.sparkline.min.js"></script>
  <script src="js/toggles.min.js"></script>
  <script src="js/retina.min.js"></script>
  <script src="js/jquery.cookies.js"></script>
  

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
  
</head>

<body>



<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>


<section>
  
  <div class="leftpanel">
    
    <div class="logopanel">
        <h1><span>[</span> 魅影相册 <span>]</span></h1>
    </div><!-- logopanel -->
    
    <div class="leftpanelinner">
        
        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">   
            <div class="media userlogged">
                <img alt="" src="images/photos/loggeduser.png" class="media-object">
                <div class="media-body">
                    <h4>John Doe</h4>
                    <span>"Life is so..."</span>
                </div>
            </div>
          
            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li><a href="profile.html"><i class="fa fa-user"></i> <span>Profile</span></a></li>
              <li><a href=""><i class="fa fa-cog"></i> <span>Account Settings</span></a></li>
              <li><a href=""><i class="fa fa-question-circle"></i> <span>Help</span></a></li>
              <li><a href="signout.html"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>
      
      <h5 class="sidebartitle">导航</h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
        

        <li class="nav-parent nav-active active"><a href=""><i class="fa fa-user"></i> <span>女孩管理</span></a>
            <ul class="children" style="display: block;">
                <li><a href="girls-add.php"><i class="fa fa-caret-right"></i> 新增女孩</a></li>
                <li class="active"><a href="girls-list.php"><i class="fa fa-caret-right"></i> 女孩列表</a></li>
            </ul>
        </li>

      
      </ul>
      
      <div class="infosummary">
        <h5 class="sidebartitle">Information Summary</h5>    
        <ul>
            <li>
                <div class="datainfo">
                    <span class="text-muted">Daily Traffic</span>
                    <h4>630, 201</h4>
                </div>
                <div id="sidebar-chart" class="chart"></div>   
            </li>
            <li>
                <div class="datainfo">
                    <span class="text-muted">Average Users</span>
                    <h4>1, 332, 801</h4>
                </div>
                <div id="sidebar-chart2" class="chart"></div>   
            </li>
            <li>
                <div class="datainfo">
                    <span class="text-muted">Disk Usage</span>
                    <h4>82.2%</h4>
                </div>
                <div id="sidebar-chart3" class="chart"></div>   
            </li>
            <li>
                <div class="datainfo">
                    <span class="text-muted">CPU Usage</span>
                    <h4>140.05 - 32</h4>
                </div>
                <div id="sidebar-chart4" class="chart"></div>   
            </li>
            <li>
                <div class="datainfo">
                    <span class="text-muted">Memory Usage</span>
                    <h4>32.2%</h4>
                </div>
                <div id="sidebar-chart5" class="chart"></div>   
            </li>
        </ul>
      </div><!-- infosummary -->
      
    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">
      
      <div class="headerbar">
        
        <a class="menutoggle"><i class="fa fa-bars"></i></a>
        
        
        
        <!-- header-right -->
        
      </div>