<?php 
    include 'global.php'; 
    include 'header.php'; 
?>
    <link href="css/jquery.datatables.css" rel="stylesheet">
    <style type="text/css">
    #girls-list td{
        vertical-align: middle !important;
    }
    </style>
    <script src="js/jquery.datatables.min.js"></script>
    <script type="text/javascript">
    jQuery(function(){
        jQuery('#table1').dataTable();
        
        jQuery('#table2').dataTable({
            "sPaginationType": "full_numbers"
        });

        

        

        $('#girls-list').DataTable( {
            autoWidth: false,
            "processing": false,
            "serverSide": true,
            "ajax": "girls-data.php",
            "columns": [
                { "sName": "id" },
                { "sName": "name" },
                { "sName": "gender" },
                { "sName": "height" },
                { "sName": "weight" },
                { "sName": "bust" },
                { "sName": "waist" },
                { "sName": "hip" },
                { "sName": "action" }
            ],
            "aoColumnDefs": [
                {
                    sDefaultContent: '',
                    aTargets: [ '_all' ]
                },
                {
                    bSortable: false,
                    aTargets: [8] 
                }
            ],
            "language": {
                "sLengthMenu"  : "每页显示 _MENU_条",
                "sZeroRecords" : "没有找到符合条件的数据",
                "sProcessing"  : '<div class="indicator" style="display: block;"><span class="spinner"></span></div>',
                "sInfo"        : "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                "sInfoEmpty"   : "没有记录",
                "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                "sSearch"      : "搜索：",
                "oPaginate"    : {
                    "sFirst"    : "首页",
                    "sPrevious" : "上一页",
                    "sNext"     : "下一页",
                    "sLast"     : "尾页"
                }
            },
            "drawCallback": function( settings ) {
                jQuery('select').select2({
                    minimumResultsForSearch: -1
                });
            }
        });
    });
    </script>


    
    <div class="pageheader">
        <h2><i class="fa fa-edit"></i> 浏览女孩 <span>Subtitle goes here...</span></h2>
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
                </div><!-- panel-btns -->
                <h3 class="panel-title">女孩基本信息列表</h3>
                <!-- <p>DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, based upon the foundations of progressive enhancement, which will add advanced interaction controls to any HTML table.</p> -->
            </div>
            <div class="panel-body">

            <?php 
                $alert = alertGet();
                if (!empty($alert)) {
                    echo '<div class="alert alert-'.$alert['flag'].'">';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
                    echo "<span>{$alert['msg']}</span>";
                    echo '</div>';
                }
            ?>
                <div class="table-responsive">
                    <table class="table" id="girls-list">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>性别</th>
                                <th>身高</th>
                                <th>体重</th>
                                <th>胸围</th>
                                <th>腰围</th>
                                <th>臀围</th>
                                <th style="width:168px;">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div><!-- table-responsive -->
              
            </div><!-- panel-body -->
          </div><!-- panel -->
            
        </div><!-- contentpanel -->
  </div><!-- mainpanel -->


<?php include 'footer.php'; ?>

	