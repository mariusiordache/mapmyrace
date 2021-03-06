<!-- header -->
<?php include($crud_ignition_views_path . 'header.php'); ?><!-- END header -->
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
		<h1 style="margin-bottom: 20px;">event_course</h1>
		<?php echo form_open_multipart(current_url()); ?>		

		<!-- field: id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">id</label>
		<?php echo form_error('id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('id', set_value('id', $item['id']), ' id="field_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_id']) ? true : false, ' class="toggler" data-field="field_id" '); ?> Ignore</label>

		<!-- field: event_id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">event_id</label>
		<?php echo form_error('event_id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('event_id', set_value('event_id', $item['event_id']), ' id="field_event_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_event_id']) ? true : false, ' class="toggler" data-field="field_event_id" '); ?> Ignore</label>

		<!-- field: course_id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">course_id</label>
		<?php echo form_error('course_id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('course_id', set_value('course_id', $item['course_id']), ' id="field_course_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_course_id']) ? true : false, ' class="toggler" data-field="field_course_id" '); ?> Ignore</label>

<div style="margin-top: 10px; padding-top: 10px; border-top: 1px; solid #DDD;">
	<button type="submit" class="btn">Save</button>
</div>

<?php echo form_close(); ?>
<?php include($crud_ignition_views_path . 'footer.php'); ?>