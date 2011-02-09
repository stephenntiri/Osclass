<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<?php /*$last = end($plugins); $last_id = $last['int_name'];*/ $last_id = ""; ?>
<script>
	$(function() {
		$.fn.dataTableExt.oApi.fnGetFilteredNodes = function ( oSettings )
		{
			var anRows = [];
			for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
			{
				var nRow = oSettings.aoData[ oSettings.aiDisplay[i] ].nTr;
				anRows.push( nRow );
			}
			return anRows;
		};
		
		sSearchName = "<?php _e('Search'); ?>...";	
		oTable = $('#datatables_list').dataTable({
	       	"bAutoWidth": false,
			"sDom": '<"top"fl>rt<"bottom"ip<"clear">',
			"oLanguage": {
					"sProcessing":   "<?php _e('Processing'); ?>...",
					"sLengthMenu":   "<?php _e('Show _MENU_ entries'); ?>",
					"sZeroRecords":  "<?php _e('No matching records found'); ?>",
					"sInfo":         "<?php _e('Showing _START_ to _END_ of _TOTAL_ entries'); ?>",
					"sInfoEmpty":    "<?php _e('Showing 0 to 0 of 0 entries'); ?>",
					"sInfoFiltered": "(<?php _e('filtered from _MAX_ total entries'); ?>)",
					"sInfoPostFix":  "",
					"sSearch":       "<?php _e('Search'); ?>:",
					"sUrl":          "",				
					"oPaginate": {
						"sFirst":    "<?php _e('First'); ?>",
						"sPrevious": "<?php _e('Previous'); ?>",
						"sNext":     "<?php _e('Next'); ?>",
						"sLast":     "<?php _e('Last'); ?>"
					},
			       	"sLengthMenu": '<div style="float:left;"><?php _e('Show'); ?> <select class="display" id="select_range">'+
			        '<option value="10">10</option>'+
			        '<option value="15">15</option>'+
			        '<option value="20">20</option>'+
			        '<option value="100">100</option>'+
					'</select> <?php _e('entries'); ?>',
			        "sSearch": '<span class="ui-icon ui-icon-search" style="display: inline-block;"></span>'
			 },
			"sPaginationType": "full_numbers",
			"aaData": [

				<?php $plugins = osc_listAllPlugins();
					foreach($plugins as $p): ?>
				<?php $p_info = osc_getPluginInfo($p); ?>
				<?php osc_isPluginInstalled($p) ? $installed = 1 : $installed = 0; ?>
					[
						"<input type='hidden' name='installed' value='<?php echo $installed ?>' /><input type='checkbox' name='id[]' value='<?php echo $p; ?>' />",
						"<?php echo $p_info['plugin_name']; ?>&nbsp;<div id='datatables_quick_edit'><?php if(osc_isPluginInstalled($p)) { ?><?php if(isset($active_plugins[$p.'_configure'])) { ?><a href='plugins.php?action=admin&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Configure'); ?></a> | <?php }; ?><?php if(osc_checkUpdate($p_info['filename'])) { ?><a href='upgrade-plugin.php?plugin=<?php echo $p_info['filename']; ?>'><?php _e('There\'s a new version. You should update!'); ?></a> | <?php }; ?><a href='plugins.php?action=uninstall&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Uninstall'); ?></a><?php } else { ?><a href='plugins.php?action=install&amp;plugin=<?php echo $p_info['filename']; ?>'><?php _e('Install'); ?></a><?php }; ?></div>", 
						'<?php echo $p_info['description']; ?>'
					] <?php echo $p != end($plugins) ? ',' : ''; ?>
				<?php endforeach; ?>
			], 
			"aoColumns": [
				{"sTitle": "<div style='margin-left: 8px;'><input id='check_all' type='checkbox' /></div>", 
				 "bSortable": false, 
				 "sClass": "center", 
				 "sWidth": "10px",
				 "bSearchable": false
				 },
				{"sTitle": "<?php _e('Name'); ?>",
				 "sWidth": "auto" },
				{"sTitle": "<?php _e('Description'); ?>" }
			]
		});
		
		$('input:hidden[name=installed]').each(function() {
			$(this).parent().parent().children().css('background', 'none');

			if ($(this).val() == '1') {
				$(this).parent().parent().css('background-color', '#EDFFDF');
			} else {
				$(this).parent().parent().css('background-color', '#FFF0DF');
			}

		});
	});
</script>
<script type="text/javascript" src="<?php echo  osc_current_admin_theme_url() ; ?>js/datatables.post_init.js"></script>
	<div id="content">
		<div id="separator"></div>	

		<?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>
		
	    <div id="right_column">

			<div id="content_header" class="content_header">
				<div style="float: left;"><img src="<?php echo  osc_current_admin_theme_url() ; ?>images/back_office/plugins-icon.png" /></div>
				<div id="content_header_arrow">&raquo; <?php _e('Plugins'); ?></div>
				<a href="?action=add" id="button_open"><?php echo osc_lowerCase(__('Add a new plugin')); ?></a>
				<div style="clear: both;"></div>
			</div>
			<?php osc_show_flash_message() ; ?>

			<div id="content_separator"></div>
			<div id="TableToolsToolbar">
			
			</div>
			
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="datatables_list"></table>
			<br />
			<div style="clear: both;"></div>

		</div> <!-- end of right column -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#datatables_list tr').live('mouseover', function(event) {
			$('#datatables_quick_edit', this).show();
		});

		$('#datatables_list tr').live('mouseleave', function(event) {
			$('#datatables_quick_edit', this).hide();
		});
	});
</script>		
