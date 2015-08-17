<!-- header -->
<?php include($crud_ignition_views_path . 'header.php'); ?>
<!-- END header -->
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo $crud_ignition_url; ?>">CrudIgnition</a> <span class="divider">/</span>
			</li>
			<li>
				<a href="<?php echo $list_url; ?>"><?php echo $model; ?></a> <span class="divider">/</span>
			</li>
			<li class="active">
				edit
			</li>
		</ul>
		<h1 style="margin-bottom: 20px;"><?php echo $model; ?> </h1>
		<?php	
			echo form_open_multipart(current_url());			
			foreach($fields as $field) { 
				$html_addon = ' id="field_' . $field['name'] . '" ' . (isset($_POST['ignore_field_'.$field['name']]) ? 'disabled="disabled"' : '');
				echo '<label style="font-weight: bold; color: #555; margin-top: 10px;">'.$field['name'].'</label>';				
				
				if(isset($errors) && isset($errors[$field['name']])) {
					echo '<div class="alert alert-error">'.$errors[$field['name']].'</div>';
				}
				
				if(isset($field['data_source']) && isset($field['options'])) {
					echo form_dropdown($field['name'], $field['options'], set_value($field['name'], $item[$field['name']]), $html_addon);
				} else {				
					switch($field['type']) {						
						case 'text':
							echo form_textarea($field['name'], set_value($field['name'], $item[$field['name']]), $html_addon);
							break;
						case 'date':			
							echo form_input($field['name'], set_value($field['name'], $item[$field['name']]), $html_addon . ' data-plugin="datepicker" ');							
							break;
						case 'datetime':									
							echo form_input($field['name'], set_value($field['name'], $item[$field['name']]), $html_addon . ' data-plugin="datetimepicker" ');							
							break;
						default: 
							echo form_input($field['name'], set_value($field['name'], $item[$field['name']]), $html_addon);
							break;
					}
				}
				echo '<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;">' . form_checkbox('ignore_field_'.$field['name'],'', isset($_POST['ignore_field_'.$field['name']]) ? true : false, ' class="toggler" data-field="field_'.$field['name'].'" ') . ' Ignore</label>';
			} 
			echo '<div style="margin-top: 10px; padding-top: 10px; border-top: 1px; solid #DDD;">';
			foreach($photo_fields as $identifier => $photo) {
				if(isset($item['photos']) && isset($item['photos'][$identifier])) {
					echo '<img src="'.$item['photos'][$identifier]['url'].'" />';
				}
				echo '<input type="file" name="photos['.$identifier.']" /><br />';
			}
			echo '<button type="submit" class="btn">Save</button>';
			echo '</div>';
			echo form_close();
			?>
		</form>

<?php include($crud_ignition_views_path . 'footer.php'); ?>