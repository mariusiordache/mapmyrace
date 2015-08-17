<?php
require_once('admin_controller.php');

class database extends admin_controller {
	public function index() {
		$this -> add_assets();
		$this -> template_engine -> assign('current_menu', 'database');
		$this -> set_template('admin/database.tpl');
		$this -> show_page();
	}
}

?>