<?php

class admin_controller extends KMS_Admin_Controller {

	public function __construct() {		
	
		parent::__construct();				
		$this -> load -> library('bootstrap');
		
		if(!$this -> current_user -> is_admin()) {
			redirect();
		}
		
	}

	public function add_assets() {
		
		# BOOTSTRAP CSS
		$this -> assets -> add_css('bootstrap/css/bootstrap.css', false);
		$this -> assets -> add_css('bootstrap/css/bootstrap-responsive.css', false);
		
		# BOOTSTRAP JS
		$this -> assets -> add_js('bootstrap/js/bootstrap.min.js', false);		
		$this -> assets -> add_js('bootstrap/js/bootstrap-alert.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-dropdown.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-button.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-tooltip.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-modal.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-popover.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-collapse.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-scrollspy.js');
		$this -> assets -> add_js('bootstrap/js/bootstrap-tab.js');
	
		
		# ACQUINCUM
		 
		//$this -> assets -> add_js('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js', 'include'=>true),
		$this -> assets -> add_js('jquery-ui/jquery-ui.js', false);
		
		# AQUINCUM JS
		
		$aquincum_files = array(
		
			array('file'=>'plugins/charts/excanvas.min.js', 'include'=>false),
			array('file'=>'plugins/charts/jquery.flot.js', 'include'=>false),
			array('file'=>'plugins/charts/jquery.flot.orderBars.js', 'include'=>false),
			array('file'=>'plugins/charts/jquery.flot.pie.js', 'include'=>false),
			array('file'=>'plugins/charts/jquery.flot.resize.js', 'include'=>false),
			array('file'=>'plugins/charts/jquery.sparkline.min.js', 'include'=>false),
			array('file'=>'plugins/tables/jquery.dataTables.js', 'include'=>false),
			array('file'=>'plugins/tables/jquery.sortable.js', 'include'=>false),
			array('file'=>'plugins/tables/jquery.resizable.js', 'include'=>false),
			
			array('file'=>'plugins/forms/jquery.uniform.js', 'include'=>true),
			array('file'=>'plugins/forms/autogrowtextarea.js', 'include'=>true),
			array('file'=>'plugins/forms/jquery.inputlimiter.min.js', 'include'=>true),
			array('file'=>'plugins/forms/jquery.tagsinput.min.js', 'include'=>true),
			array('file'=>'plugins/forms/jquery.mousewheel.js', 'include'=>false),
			array('file'=>'plugins/forms/ui.spinner.js', 'include'=>false),
			array('file'=>'plugins/forms/jquery.maskedinput.min.js', 'include'=>true),
			
			array('file'=>'plugins/forms/jquery.autotab.js', 'include'=>false),
			array('file'=>'plugins/forms/jquery.chosen.min.js', 'include'=>true),
			array('file'=>'plugins/forms/jquery.dualListBox.js', 'include'=>false),
			
			array('file'=>'plugins/forms/jquery.cleditor.js', 'include'=>true),
			array('file'=>'plugins/forms/jquery.ibutton.js', 'include'=>true),
			
			
			array('file'=>'plugins/forms/jquery.validationEngine-en.js', 'include'=>false),
			array('file'=>'plugins/forms/jquery.validationEngine.js', 'include'=>false),
		
			array('file'=>'plugins/uploader/plupload.js', 'include'=>false),
			array('file'=>'plugins/uploader/plupload.html4.js', 'include'=>false),
			array('file'=>'plugins/uploader/plupload.html5.js', 'include'=>false),
			array('file'=>'plugins/uploader/jquery.plupload.queue.js', 'include'=>false),
			

			array('file'=>'plugins/wizards/jquery.form.wizard.js', 'include'=>false),
			array('file'=>'plugins/wizards/jquery.validate.js', 'include'=>false),
			array('file'=>'plugins/wizards/jquery.form.js', 'include'=>false),
			
			array('file'=>'plugins/ui/jquery.collapsible.min.js', 'include'=>true),
			array('file'=>'plugins/ui/jquery.breadcrumbs.js', 'include'=>true),
			
			array('file'=>'plugins/ui/jquery.tipsy.js', 'include'=>true),
			
			array('file'=>'plugins/ui/jquery.progress.js', 'include'=>false),
			array('file'=>'plugins/ui/jquery.timeentry.min.js', 'include'=>false),
			array('file'=>'plugins/ui/jquery.colorpicker.js', 'include'=>false),
			array('file'=>'plugins/ui/jquery.jgrowl.js', 'include'=>false),
			
			array('file'=>'plugins/ui/jquery.fancybox.js', 'include'=>true),
			
			array('file'=>'plugins/ui/jquery.fileTree.js', 'include'=>false),
			array('file'=>'plugins/ui/jquery.sourcerer.js', 'include'=>false),
			
			array('file'=>'plugins/others/jquery.fullcalendar.js', 'include'=>true),
			
			array('file'=>'plugins/others/jquery.elfinder.js', 'include'=>false),
			
			
			array('file'=>'plugins/ui/jquery.easytabs.min.js', 'include'=>true),
			array('file'=>'files/functions.js', 'include'=>true),

			array('file'=>'charts/chart.js', 'include'=>false),
			array('file'=>'charts/hBar_side.js', 'include'=>false),
		);
		
		foreach($aquincum_files as &$file) {
			if($file['include']) {
				$this -> assets -> add_js('aquincum/js/'.$file['file']);
			}
		}
		unset($aquincum_files); 
		$aquincum_files = null;
			
		# AQUINCUM CSS
		$this -> assets -> add_css('aquincum/css/styles.css');	
				
		
		# CUSTOM CSS
		$this -> assets -> add_css('css/admin/admin.css', false);
	
	}

}


?>