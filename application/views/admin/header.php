<!DOCTYPE html>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap-responsive.css" media="screen" />	
                
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/css/admin/admin.css" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap-datepicker/bootstrap-datepicker.css" media="screen" />
		<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/jquery/dist/jquery.min.js"></script>	
		<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/bootstrap/dist/js/bootstrap.js"></script>	
		
		<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/jqueryui/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/underscore/underscore.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>bower_components/backbone/backbone.js"></script>
		
		<?php 
		if(isset($scripts)) {
			foreach($scripts as $script) {
				echo '<script type="text/javascript" src="' . $script .'"></script>';
			}
		}
		?>
		
		<script type="text/javascript">
			var BASE_URL = '<?php echo base_url(); ?>';
			var CRUD_IGNITION_URL = '<?php echo $crud_ignition_url; ?>';
			<?php 
			if(isset($js_vars)) {
				foreach($js_vars as $key=>$value) {
					echo 'var '.$key.' = \''. $value.'\''."\n";
				}
			}
			?>
		</script>

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->		
	</head>
	
	<body> 
		<div class="navbar">
		  <div class="navbar-inner">
			<div class="container-fluid">
			  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </a>
			  <a class="brand" href="#"><?php echo APPPATH; ?></a>
			  <div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="<?php echo $crud_ignition_url; ?>" >CrudIgnition</a></li>
				</ul>
				<p class="navbar-text pull-right">Logged in as </p>
			  </div><!--/.nav-collapse -->
			</div>
		  </div>
		</div>

		<div class="container-fluid">