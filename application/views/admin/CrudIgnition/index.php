<!-- header -->
<?php include($crud_ignition_views_path . 'header.php'); ?>
<!-- END header -->
<h1>Welcome to CrudIgnition</h1>

<?php if(isset($report)) { ?>		
	<div class="alert alert-success">
		<button class="close" data-dismiss="alert">&times;</button>
		<?php echo $report; ?>
	</div>

<?php } ?>

<div class="page-header">
	<h2>Tables in your database 
		<a class="btn" href="<?php echo $create_url; ?>"><i class="icon-plus"></i> create new</a>
		<?php 
			if($new_migration_count>0) {
		?>
			<a class="btn" href="<?php echo $run_migrations_url; ?>"><i class="icon-refresh"></i> run new migrations (<?php echo $new_migration_count; ?>)</a>
		<?php 
			}
		?>
	</h2>
</div>
<table class="table table-bordered" style="width: 1000px;">
	<thead>
		<tr>
			<th>Table name</th>
			<th>CrudIgnition Model</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach($tables as $table) {
			echo '<tr '.( isset($table['model']) ? ' data-model="'.$table['model']['model'].'" ' : '').'>
				<td>'.$table['name'].'</td>';
			if(isset($table['model'])) {
				echo '<td>'.$table['model']['model'].'</td>';
				echo '<td>
					<a class="btn" href="#" data-sync="'.$table['model']['model'].'"><i class="icon-refresh"></i> check sync</a>
					<a class="btn" href="'.$table['model']['edit_url'].'"><i class="icon-edit"></i> edit model</a>
					<a class="btn" href="'.$table['model']['manage_url'].'"><i class="icon-list"></i> manage</a>
					<a class="btn" data-delete="'.$table['model']['model'].'" href="#"><i class="icon-trash"></i> delete</a>
				</td>';
			} else {
				echo '<td> - </td>';
				echo '<td><a href="'.$table['create_url'].'">create</a></td>';
			}
			echo '</tr>';
		}
		?>
	</tbody>
	
	<div id="sync-results" class="modal" style="display: none;">
		<div class="modal-header">
			<button class="close" data-dismiss="modal">&times;</button>
			<h3>Sync check results</h3>
		</div>		
		<ul class="modal-body">
		</ul>
	</div>
	
	<div id="delete-confirmation" class="modal" style="display: none;">
		<div class="modal-header">
			<button class="close" data-dismiss="modal">&times;</button>
			<h3>Delete model?</h3>	
		</div>		
		<ul class="modal-body">			
			<p>Are you sure you want to delete all model files (controller, model, views)? This action cannot be undone!</p>
			<label class="checkbox"><input type="checkbox" id="drop_table" name="drop_table"/> Also delete table from database?</label>		
		</ul>
		<div class="modal-footer">
			<a class="btn btn-primary confirm" href="#">Delete model</a>
			<a class="btn cancel" href="#">Cancel</a>
		</div>
	</div>
</table>

<!-- footer -->
<?php include($crud_ignition_views_path . 'footer.php'); ?>
<!-- END footer -->