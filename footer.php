</section>





<div class="modal fade bs-modal-alert in" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="false" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">操作确认</h4>
            </div>
            <div class="modal-body">
                <div id="alert-msg"></div>
                <a class="btn btn-maroon btn-sm" style="float:right;margin-top:10px;">确认</a>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.autogrow-textarea.js"></script>
<script src="js/bootstrap-timepicker.min.js"></script>
<script src="js/jquery.maskedinput.min.js"></script>
<script src="js/jquery.tagsinput.min.js"></script>
<script src="js/jquery.mousewheel.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/dropzone.min.js"></script>
<script src="js/colorpicker.js"></script>


<script src="js/custom.js"></script>

<script>
jQuery(document).ready(function(){
    
    "use strict";
    
    $('.bs-modal-alert').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var modal = $(this)           

        var url = button.data('url');
        var msg = button.data('msg');
        modal.find('#alert-msg').html(msg);
        modal.find('.btn-maroon').prop({href: url})
    })
  
  // Tags Input
  jQuery('#tags').tagsInput({width:'auto'});
  
  // Select2
    jQuery(".select2").select2({
        width: '100%',
    });
    jQuery(".select2-nosearch").select2({
        width: '100%',
        minimumResultsForSearch: -1
    });
   
  // Textarea Autogrow
  jQuery('#autoResizeTA').autogrow();
  
  // Color Picker
  if(jQuery('#colorpicker').length > 0) {
	 jQuery('#colorSelector').ColorPicker({
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery('#colorSelector span').css('backgroundColor', '#' + hex);
				jQuery('#colorpicker').val('#'+hex);
			}
	 });
  }


  
  // Color Picker Flat Mode
	jQuery('#colorpickerholder').ColorPicker({
		flat: true,
		onChange: function (hsb, hex, rgb) {
			jQuery('#colorpicker3').val('#'+hex);
		}
	});
   
  // Date Picker
  jQuery('#datepicker').datepicker();
  
  jQuery('#datepicker-inline').datepicker();
  
  jQuery('#datepicker-multiple').datepicker({
    numberOfMonths: 3,
    showButtonPanel: true
  });
  
  // Spinner
  var spinner = jQuery('#spinner').spinner();
  spinner.spinner('value', 0);
  
  // Input Masks
  jQuery("#date").mask("99/99/9999");
  jQuery("#phone").mask("(999) 999-9999");
  jQuery("#ssn").mask("999-99-9999");
  
  // Time Picker
  jQuery('#timepicker').timepicker({defaultTIme: false});
  jQuery('#timepicker2').timepicker({showMeridian: false});
  jQuery('#timepicker3').timepicker({minuteStep: 15});

  
});
</script>


</body>
</html>
	