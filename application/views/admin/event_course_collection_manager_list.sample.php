<!-- header -->
<?php include($crud_ignition_views_path . 'header.php'); ?><!-- END header -->

		<ul class="breadcrumb">
			<li>
				<a href="<?php echo $crud_ignition_url; ?>">CrudIgnition</a> <span class="divider">/</span>
			</li>
			<li class="active">
				<?php echo $model; ?>
			</li>
		</ul>
	
		<h1 style="margin-bottom: 20px;"><?php  echo $model; ?> <a class="btn" href="<?php echo current_url(); ?>/add"><i class="icon-plus"></i> Add new</a></h1>
		
		<?php if(isset($report)) { ?>
		
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<?php echo $report; ?>
			</div>
		
		<?php } ?>
		
		<div class="row-fluid">
			<div class="pull-left span4">
				<div class="pagination" style="margin-top: 0px;">
					<ul>
						<?php for($i=1; $i<=$paging['total_pages']; $i++) {							
							echo '<li '.($paging['cur_page']==$i ? 'class="active"' : '').'><a href="'.str_replace('%page_no%', $i, $paging['url_template']).'">'.$i.'</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
			<form method="get" class="form form-inline pull-right">
				<label>Search</label>
				<input class="input-medium" type="text" name="search_value" value="<?php echo form_prep($search_value); ?>" /> 
				<select name="search_field" class="input-medium">
					<option value="">Search in ...</option>
					<option value="id" <?php if($search_field=='id') { echo 'selected="selected"'; } ?> >id</option><option value="event_id" <?php if($search_field=='event_id') { echo 'selected="selected"'; } ?> >event_id</option><option value="course_id" <?php if($search_field=='course_id') { echo 'selected="selected"'; } ?> >course_id</option>				</select>
				<label>Sort</label>				
				<select name="sort_field" class="input-small">
					<option value="">Sort by ...</option>
					<option value="id" <?php if($sort_field=='id') { echo 'selected="selected"'; } ?> >id</option><option value="event_id" <?php if($sort_field=='event_id') { echo 'selected="selected"'; } ?> >event_id</option><option value="course_id" <?php if($sort_field=='course_id') { echo 'selected="selected"'; } ?> >course_id</option>				</select>
				<select name="sort_dir" class="input-medium">
					<option value="ASC" <?php if($sort_dir=='ASC') echo 'selected="selected"'; ?>>Ascending</option>
					<option value="DESC" <?php if($sort_dir=='DESC') echo 'selected="selected"'; ?>>Descending</option>
				</select>
				<button class="btn btn-small">Apply</button>
			</form>
		</div>
		<div class="row-fluid">
			<p><?php echo $paging['total_items']; ?> items found</p>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td style="width: 30px;">#</td>
				<th>id</th><th>event_id</th><th>course_id</th>					<td></td>
				</tr>
			</thead>
			<tbody>
				<?php					foreach($list as $key => $item) {
				?>
					<tr data-item="<?php echo $item['id']; ?>">
						<td><?php echo (($paging['cur_page']-1)*$paging['per_page']) + $key+1; ?></td>
				<?php if($fields['id']['admin_header'] == 'on') { echo '<td>'.$item['id'].'</td>';  } ?><?php if($fields['event_id']['admin_header'] == 'on') { echo '<td>'.$item['event_id'].'</td>';  } ?><?php if($fields['course_id']['admin_header'] == 'on') { echo '<td>'.$item['course_id'].'</td>';  } ?>					<td>	
						<?php						if(isset($item['id'])) {
						?>
						<a class="btn btn-small btn-success" href="<?php echo $edit_url.$item['id']; ?>"><i class="icon-edit icon-white"></i> Edit</a>
						<a class="btn btn-small btn-danger" data-delete="<?php echo $item['id']; ?>"><i class="icon-remove icon-white"></i> Delete</a>
						<a class="btn btn-small" href="<?php echo $duplicate_url.$item['id']; ?>"><i class="icon-remove icon-share"></i> Duplicate</a>
						<?php } else { ?>
						Table does not have an id column and cannot be managed by CrudIgnition yet.<br />
						Please add a primary "id" column in the model editor.
						<?php } ?>
					</td>
					</tr>
				<?php					}
				?>
			</tbody>
		</table>
		
		<div class="row-fluid">
			<div class="pull-left span4">
				<div class="pagination">
					<ul>
						<?php for($i=1; $i<=$paging['total_pages']; $i++) {							
							echo '<li '.($paging['cur_page']==$i ? 'class="active"' : '').'><a href="'.str_replace('%page_no%', $i, $paging['url_template']).'">'.$i.'</a></li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		
		<div id="delete-confirmation" class="modal" style="display: none;">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h3>Delete model?</h3>	
			</div>		
			<ul class="modal-body">			
				<p>Are you sure you want to delete this item? This action cannot be undone!</p>
			</ul>
			<div class="modal-footer">
				<a class="btn btn-primary confirm" href="#">Delete model</a>
				<a class="btn cancel" href="#">Cancel</a>
			</div>
		</div>
		
<?php include($crud_ignition_views_path . 'footer.php'); ?>