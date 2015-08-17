<!-- header -->
<?php include($crud_ignition_views_path . 'header.php'); ?>
<!-- END header -->

<?php if(isset($fields)) { ?>
	<script type="text/javascript">
	var preload_fields = new Array();
	<?php foreach($fields as $f) { ?>
		preload_fields.push(<?php echo json_encode($f); ?>);
	<?php } ?>
	</script>
<?php } ?>

<div id="crud">

<ul class="breadcrumb">
	<li>
		<a href="<?php echo $crud_ignition_url; ?>">CrudIgnition</a> <span class="divider">/</span>
	</li>
	<li class="active">
		Edit model
	</li>
</ul>

<h1>New model</h1>
<div class="row-fuild">	
	<div id="save_messages" class="alert alert-info" style="display: none;">
		<ul></ul>
	</div>
	<div id="save_errors" class="alert alert-error" style="display: none;">
		<ul></ul>
	</div>
</div>
<div class="row-fluid" style="margin-top: 20px;">		
	<div class="span4">		
		<form onsubmit="return false;">
			
			<p>
			<label>Model name</label>
			<input style="width: 400px;" class="span2" type="text" name="model" id="model" placeholder="Only a-z, 0-9, _" value="<?php echo $model; ?>" />
			</p>
			
			<p>
			<label>Table name</label>
			<input style="width: 400px;" class="span2" type="text" name="table" id="table" placeholder="Only a-z, 0-9, _" value="<?php echo $table; ?>" />	
			</p>
		
			<div class="row-fluid" style="margin-bottom: 10px;">
				<div class="span6">				
					<h2>Fields</h2>
				</div>
				<div class="span6">
					<div class="btn-group pull-right">
						<a class="btn btn-small" id="add_custom" href="#"><i class="icon-plus-sign"></i></a>
						<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
							Quick field
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu" id="quickFieldMenu">
							<script type="text/javascript">
								var presetQuickFields = {};
								<?php 
									if(isset($quick_fields)) {
										foreach($quick_fields as $quick_field) {
											echo 'presetQuickFields["'.$quick_field['name'].'"] = '.json_encode($quick_field).';'."\n";
										}
									}
								?>
							</script>							
						</ul>
					</div>
				</div>
			</div>
		
			<table class="table" id="field-list">
				
			</table>			
			
			<label class="checkbox"><input type="checkbox" name="overwrite[controller]" /> Overwrite controller</label>
			<label class="checkbox"><input type="checkbox" name="overwrite[model]" /> Overwrite model</label>
			<label class="checkbox"><input type="checkbox" name="overwrite[list_sample]" /> Overwrite list sample</label>
			<label class="checkbox"><input type="checkbox" name="overwrite[edit_sample]" /> Overwrite edit sample</label>
			
			<div class="btn-toolbar">
				<button class="btn btn-primary" id="save_fields">Save model</button>
			</div>
		</form>
	</div>
	  
	<div class="span8" id="form-holder">
		<form id="field-form" class="well" onsubmit="return false;">
			<div class="row-fluid">
				<div class="span4">
					<p class="name">          
						<label>Field name</label>
						<input type="text" name="name" />		
					</p>
				
					<p>
						<label class="type">Type</label>
						<select name="type">
							<option value="varchar">Varchar</option>
							<option value="char">Char</option>
							<option value="boolean">Boolean</option>
							<option value="enum">Enum</option>
							<option value="primary">Primary</option> 
							<option value="tinyint">Tiny INT</option>
							<option value="float">Float</option>
							<option value="double">Double</option>
							<option value="int">INT</option>
							<option value="bigint">BigInt</option>
							<option value="datetime">Datetime</option>
							<option value="date">Date</option>
							<option value="timestamp">Timestamp</option>
							<option value="decimal">Decimal</option>
							<option value="text">Text</option>
						</select>
					</p>
				
					<p class="length">
						<label>Max Length</label>
						<input class="span1" type="text" name="length" style="width: 100px"/>		
					</p>
				
					<p class="decimal_digits">
						<label>Digits, decimals (0.34 = 3,2)</label>
						<input class="span1" type="text" name="digits" size="1"/>		
						<input class="span1" type="text" name="decimals" size="1" />		 
					</p>	
				
					<p class="data_source">
						<label>Data source <a href="#" rel="popover" data-popover-placeholder="data_source_explanation">(?)</a></label>
						<input type="text" name="data_source" />		 
						<div id="data_source_explanation" style="display: none;">
							<span class="title">Generate a dropdown</span>
							<span class="content">
								If this field is going to be populated with a dropdown, set the options in this field:
<pre class="prettyprint linenums" style="margin-top: 10px;">
Value1,Value2,Value3
</pre>
<pre class="prettyprint linenums">
Label1[Value1],Label2[Value2],...
</pre>
<pre class="prettyprint linenums">
table:value_column,label_column
</pre>
							</span>
						</div>
					</p>
					
					<p class="admin_header">
						<label>Model manager</label>
						<select name="admin_header">
							<option value="on">Show column</option>
							<option value="off">Hide column</option>
						</select>						
					</p>
                                        
                                        <p class="default">
						<label>Default Value</label>
						<select name="default_option">
                                                    <option value="">NONE</option>
                                                    <option value="NULL">NULL</option>
                                                    <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
                                                    <option value="CUSTOM">CUSTOM</option>
						</select>	
                                                
                                                <input type="text" name="default_value" style="display:none" />
					</p>
                                        
                                        <p class="default">
                                            <label>
                                                <input type="checkbox" name="is_null" />
                                                NULL
                                            </label>
                                        </p>
					
					<button class="btn" name="submit">Save <span class="button_field_name" style="font-weight: bold;"></span></button>
				</div>
				
				<div class="span8">
					
					<div class="tabbable well" style="background: #FFF; padding: 10px 20px 20px 20px;">
						<h3 style="margin-bottom: 10px;">Validation rules & modifiers</h3>
						<ul class="nav nav-tabs" id="vGroupTabs">
							<li class="last">
								<a href="#" id="add_validation_group" style="line-height: 27px;"><i class="icon-plus"></i></a>
							</li>
							<li style="float: right;">
								<div class="btn-group pull-right">
									<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
										Add rules
										<span class="caret"></span>
									</a>
									<script type="text/javascript">
										var presetQuickRules = {};
										<?php 
											if(isset($quick_rules)) {
												foreach($quick_rules as $code => $quick_rule) {
													echo 'presetQuickRules["'.str_replace('"', '\"', $code).'"] = "'.str_replace('"', '\"', $quick_rule).'";'."\n";
												}
											}
										?>
									</script>
									<ul class="dropdown-menu" id="quickRulesMenu">										
									</ul>
								</div>
							</li>
						</ul>
					
						<div class="tab-content">
							<div class="tab-pane active">
								<ol id="rulePanel"> 
									
								</ol>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</form>
	</div>
</div>


<!-- Templates -->
<script type="text/template" id="field-template">
	<td><a href="#" class="fieldTitle"><% if(typeof(new_name) != 'undefined') { print(new_name); } else { print(name); } %> (<%= type %>)</a></td>
	<td>
		<a href="#" class="remove pull-right"><i class="icon-remove-sign"></i></a>
		<a href="#" class="restore pull-right"><i class="icon-refresh"></i></a>
	</td>
</script>

<script type="text/template" id="vGroupTab-template">
	<a style="line-height: 27px;" href="#" data-label="<% if(typeof(new_name) != 'undefined') { print(new_name); } else { print(name); } %>">
		<span class="name"><% if(typeof(new_name) != 'undefined') { print(new_name); } else { print(name); } %></span>
		<input type="text" class="input-mini name form-inline" style="margin-bottom: 0px;"/>
		<span class="remove"><i class="icon-remove-sign"></i></span>
		<span class="restore"><i class="icon-refresh"></i></span>
	</a>
</script>

<script type="text/template" id="vRule-template">
	<input class="edit form-inline" type="text" value="<%= rule %>" />
	<a href="#" class="remove"><i class="icon-remove-sign"></i></a>
	<a href="#" class="restore"><i class="icon-refresh"></i></a>
	<span class="sortHandle" style="cursor: move;"><i class="icon-resize-vertical"></i></span>
</script>

<?php include($crud_ignition_views_path . 'footer.php'); ?>