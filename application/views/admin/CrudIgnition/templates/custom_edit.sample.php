<!-- header -->
<?php echo '<?php'; ?> include($crud_ignition_views_path . 'header.php'); <?php echo '?>'; ?>
<!-- END header -->
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo '<?php'; ?> echo $crud_ignition_url; ?>">CrudIgnition</a> <span class="divider">/</span>
			</li>
			<li>
				<a href="<?php echo '<?php'; ?> echo $list_url; ?>"><?php echo '<?php'; ?> echo $model; ?></a> <span class="divider">/</span>
			</li>
			<li class="active">
				edit
			</li>
		</ul>
		<h1 style="margin-bottom: 20px;"><?php echo $model; ?></h1>
		<?php echo '<?php'; ?> echo form_open_multipart(current_url()); <?php echo '?>'; ?>
		<?php 
			foreach($fields as $field) { 
				$html_addon = ' id="field_' . $field['name'] . '" ';
echo "\n\n";
echo '		<!-- field: '.$field['name'].' -->'."\n";
echo '		<label style="font-weight: bold; color: #555; margin-top: 10px;">'.$field['name'].'</label>' . "\n";				
echo '		<?php echo form_error(\''.$field['name'].'\', \'<div class="alert alert-error">\', \'</div>\'); ?>' . "\n";
				
				if(isset($field['data_source']) && strlen($field['data_source'])>0) {
echo '		<?php echo form_dropdown(\''.$field['name'].'\', isset($'.$field['name'].'_options) ? $'.$field['name'].'_options : $fields[\''.$field['name'].'\'][\'options\'], set_value(\''.$field['name'].'\', $item[\''.$field['name'].'\']), \''.$html_addon.'\'); ?>';
				} else {				
					switch($field['type']) {						
						case 'text':
echo '		<?php echo form_textarea(\''.$field['name'].'\', set_value(\''.$field['name'].'\', $item[\''.$field['name'].'\']), \''.$html_addon.'\'); ?>';
							break;
						case 'date':		
							$html_addon .= ' data-plugin="datepicker" ';
echo '		<?php echo form_input(\''.$field['name'].'\', set_value(\''.$field['name'].'\', $item[\''.$field['name'].'\']), \''.$html_addon.'\'); ?>';						
							break;
						case 'datetime':		
							$html_addon .= ' data-plugin="datetimepicker" ';
echo '		<?php echo form_input(\''.$field['name'].'\', set_value(\''.$field['name'].'\', $item[\''.$field['name'].'\']), \''.$html_addon.'\'); ?>';
							break;
						default: 
echo '		<?php echo form_input(\''.$field['name'].'\', set_value(\''.$field['name'].'\', $item[\''.$field['name'].'\']), \''.$html_addon.'\'); ?>';						
							break;
					}
				}
echo "\n";
echo '		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox(\'\',\'\', isset($_POST[\'ignore_field_'.$field['name'].'\']) ? true : false, \' class="toggler" data-field="field_'.$field['name'].'" \'); ?> Ignore</label>';
			} 
			echo "\n\n".'<div style="margin-top: 10px; padding-top: 10px; border-top: 1px; solid #DDD;">';
			echo "\n\t".'<button type="submit" class="btn">Save</button>';
			echo "\n".'</div>'."\n\n";
			echo '<?php echo form_close(); ?>';
		?>

<?php echo '<?php'; ?> include($crud_ignition_views_path . 'footer.php'); <?php echo '?>'; ?>