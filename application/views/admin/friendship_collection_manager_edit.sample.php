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
		<h1 style="margin-bottom: 20px;">friendship</h1>
		<?php echo form_open_multipart(current_url()); ?>		

		<!-- field: id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">id</label>
		<?php echo form_error('id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('id', set_value('id', $item['id']), ' id="field_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_id']) ? true : false, ' class="toggler" data-field="field_id" '); ?> Ignore</label>

		<!-- field: request_user_id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">request_user_id</label>
		<?php echo form_error('request_user_id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('request_user_id', set_value('request_user_id', $item['request_user_id']), ' id="field_request_user_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_request_user_id']) ? true : false, ' class="toggler" data-field="field_request_user_id" '); ?> Ignore</label>

		<!-- field: target_user_id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">target_user_id</label>
		<?php echo form_error('target_user_id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('target_user_id', set_value('target_user_id', $item['target_user_id']), ' id="field_target_user_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_target_user_id']) ? true : false, ' class="toggler" data-field="field_target_user_id" '); ?> Ignore</label>

		<!-- field: accepted -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">accepted</label>
		<?php echo form_error('accepted', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('accepted', set_value('accepted', $item['accepted']), ' id="field_accepted" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_accepted']) ? true : false, ' class="toggler" data-field="field_accepted" '); ?> Ignore</label>

<div style="margin-top: 10px; padding-top: 10px; border-top: 1px; solid #DDD;">
	<button type="submit" class="btn">Save</button>
</div>

<?php echo form_close(); ?>
<?php include($crud_ignition_views_path . 'footer.php'); ?>