<? $PHP_SELF=$_SERVER['PHP_SELF']; if (!stripos($PHP_SELF,"index.php")) die ("Access denied"); ?>
</td>
</tr>
</table>
</div>
<div class="copyright"><a href="http://cherepkova.ru/" target="_blank">Created by cherepkova.ru</a></div>

	<script type="text/javascript" src="jscripts/colorpicker.js"></script>
	
	<script>
	$(window).load(function() {
	$('#colorSelector').ColorPicker({
	color: '#0000ff',
	onShow: function (colpkr) {
		$(colpkr).show();
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).hide();
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector div').css('backgroundColor', '#' + hex);
	},
	onSubmit: function (hsb, hex, rgb) {
		tmpval=$('#good_colors').val();
		tmpval+=hex+';';
		$('#good_colors').val(tmpval);
		
		tmpval=$('#submit_colors').html();
		tmpval+='<li style="background-color: #'+hex+';" title="'+hex+'"></li>';
		$('#submit_colors').html(tmpval);
	}
	});
	});
	</script>



<? 
if ($autorized != 0)
{
	if ($page_active!="addanswform") echo '<script src="jscripts/jquery.uniform.js" type="text/javascript" charset="windows-1251"></script>';
?> 
	<script type="text/javascript" src="jscripts/datepicker.js"></script>
	<script type="text/javascript" src="jscripts/delete.js"></script>
    <script language="javascript" type="text/javascript" src="jscripts/jquery-ui-1.9.2.custom.min.js"></script>
<?
if ($mod == "gallery" or $action == "edit")
{
?>
    <script type="text/javascript" src="jscripts/uploader/js/swfupload.js"></script>
	<script type="text/javascript" src="jscripts/uploader/js/handlers.js"></script>
<? 
}
if ($mod == "catalog" or $action == "edit")
{
?>
    <script type="text/javascript" src="jscripts/jquery.MultiFile.js"></script>
<? 
}
if ($action == "edit" or $action == "edit_cat" or $action == "edit_mark" or $action == "editscroll")
{
?>     
	<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
    
	<script language="javascript" type="text/javascript">
	
		tinyMCE.init({
			mode : "textareas",
			theme : "advanced",
			language:"ru",
			plugins : "advimage,advlink,media,contextmenu,table,imagemanager",
			theme_advanced_buttons1_add_before : "newdocument,separator",
			theme_advanced_buttons1_add : "fontselect,fontsizeselect",
			theme_advanced_buttons2_add : "separator,forecolor,backcolor,liststyle",
			theme_advanced_buttons2_add_before: "cut,copy,separator",
			theme_advanced_buttons3_add_before : "",
			theme_advanced_buttons3_add : "tablecontrols,media",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			extended_valid_elements : "iframe[src|title|width|height|frameborder|allowfullscreen]",
			paste_use_dialog : false,
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true,
			apply_source_formatting : true,
			force_br_newlines : false,
			force_p_newlines : true,	
			relative_urls : true,
			imagemanager_path : "jscripts/tiny_mce/plugins/imagemanager"
		});


		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "jscripts/uploader/upload.php",
				post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},

				// File Upload Settings
				file_size_limit : "2 MB",	// 2MB
				file_types : "*.jpg",
				file_types_description : "JPG Images",
				file_upload_limit : "0",

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "jscripts/uploader/images/SmallSpyGlassWithTransperancy_17x18.png",
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 180,
				button_height: 18,
				button_text : '<span class="button">Select Images <span class="buttonSmall">(2 MB Max)</span></span>',
				button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
				button_text_top_padding: 0,
				button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "jscripts/uploader/js/swfupload.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer"
				},
				
				// Debug Settings
				debug: false
			});
		};
	</script>
<!-- /TinyMCE -->
<?
}
if ($need_mod=="news" && $action=="list") { ?>
<script type="text/javascript" src="jscripts/jquery-ui-1.8.13.slider.min.js"></script>
<script type="text/javascript" src="jscripts/jPaginator-min.js"></script>
<? } else if ($need_mod=="comment" && $action=="list") { ?>
<script type="text/javascript" src="jscripts/jquery-ui-1.8.13.slider.min.js"></script>
<script type="text/javascript" src="jscripts/jPaginator-min.js"></script>
<? } ?>
<script type="text/javascript">
	$(function () {			
		$('#inputDate').DatePicker({
			format:'Y-m-d',
			date: $('#inputDate').val(),
			current: $('#inputDate').val(),
			starts: 1,
			position: 'r',
			onBeforeShow: function(){
				$('#inputDate').DatePickerSetDate($('#inputDate').val(), true);
			},
			onChange: function(formated, dates){
				$('#inputDate').val(formated);
					$('#inputDate').DatePickerHide();
			}
		});
		$(".not_hide").show();		
		$('.one_cat').click(function() {
			var myattr = $(this).attr('title');
			
			$(".hidden_mod").hide(200);
			$(myattr).toggle(200);
		});
		$('.filter').click(function() {
			$('.filter_form').toggle(200);
		});
		$("#datepicker").datepicker();
       	
		$("input, text, select, button").uniform();								 
		var i = $('input').size() + 1;
	
		$('#add').click(function() {
			$('<div><input type="text" class="field" name="dynamic[]" value="" size="58" /></div>').fadeIn('slow').appendTo('.inputs');
			i++;
		});

		$('#remove').click(function() {
		if(i > 1) {
			$('.field:last').remove();
			i--; 
		}
		});
	
		$('#reset').click(function() {
		while(i > 2) {
			$('.field:last').remove();
			i--;
		}
		});
		
	});
	
</script>
<? } 
else
{
?>
<script src="jscripts/jquery.uniform.js" type="text/javascript" charset="windows-1251"></script>
<script type="text/javascript">
	$(function () {		
				$("input, text, select, button").uniform();								 
		var i = $('input').size() + 1;
	});			
</script>
<? } 
if ($need_mod=="sovetday" && $action=="edit") {
	?>
<script type="text/javascript">    
	$(".this_val1").keyup(function ()  
        {
			var text = $(this).val();
			$("#recomend").html(text);
		});
	$(".this_val2").keyup(function ()  
        {
			var text = $(this).val();
			$("#name_sovet").html(text);
		});
	$(".this_val3").keyup(function ()  
        {
			var text = $(this).val();
			$("#who").html(text);
		});
		
		$(".fontsize").change(function ()  
        {
			var text = $(".fontsize option:selected").val();
			$("#name_sovet").css("font-size", text+"px");
		});
</script>
    <?
}
?>
	

</body>
</html>