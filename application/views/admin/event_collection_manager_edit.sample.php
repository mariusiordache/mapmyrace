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
		<h1 style="margin-bottom: 20px;">event</h1>
		<?php echo form_open_multipart(current_url()); ?>		

		<!-- field: id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">id</label>
		<?php echo form_error('id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('id', set_value('id', $item['id']), ' id="field_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_id']) ? true : false, ' class="toggler" data-field="field_id" '); ?> Ignore</label>

		<!-- field: date_created -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">date_created</label>
		<?php echo form_error('date_created', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('date_created', set_value('date_created', $item['date_created']), ' id="field_date_created"  data-plugin="datetimepicker" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_date_created']) ? true : false, ' class="toggler" data-field="field_date_created" '); ?> Ignore</label>

		<!-- field: name -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">name</label>
		<?php echo form_error('name', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('name', set_value('name', $item['name']), ' id="field_name" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_name']) ? true : false, ' class="toggler" data-field="field_name" '); ?> Ignore</label>

		<!-- field: public -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">public</label>
		<?php echo form_error('public', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('public', set_value('public', $item['public']), ' id="field_public" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_public']) ? true : false, ' class="toggler" data-field="field_public" '); ?> Ignore</label>

		<!-- field: owner_id -->
		<label style="font-weight: bold; color: #555; margin-top: 10px;">owner_id</label>
		<?php echo form_error('owner_id', '<div class="alert alert-error">', '</div>'); ?>
		<?php echo form_input('owner_id', set_value('owner_id', $item['owner_id']), ' id="field_owner_id" '); ?>
		<label class="checkbox inline" style="margin-left: 10px; margin-top: -15px;"><?php echo form_checkbox('','', isset($_POST['ignore_field_owner_id']) ? true : false, ' class="toggler" data-field="field_owner_id" '); ?> Ignore</label>

<div style="margin-top: 10px; padding-top: 10px; border-top: 1px; solid #DDD;">
	<button type="submit" class="btn">Save</button>
</div>

<?php echo form_close(); ?>
<?php include($crud_ignition_views_path . 'footer.php'); ?>