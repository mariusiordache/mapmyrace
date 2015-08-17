<?php

/* controller subfolder where the main CrudIgnition.php controller is located

If you access it like this: 
	http://www.yoursite.com/admin/CrudIgnition
then the url would be 
	admin/
	
If you access it like this:
	http://www.yoursite.com/CrudIgnition
then leave the url blank

*/
$config['crud_ignition']['url'] = 'admin/';

/* 
subfolder, inside the views folder, where the CrudIgnition template folder is located. 
Typically you won't need to change this 
*/

$config['crud_ignition']['views_path'] = 'admin/';

/* 
quick fields - shortcuts to make adding new models as easy as possible
*/
$config['crud_ignition']['quick_fields'] = array(
	'id'           => array('type'=>'primary',  'name'=>'id'),
	'date_created' => array(
		'name'    => 'date_created',
		'type'    => 'datetime', 
		'vgroups' => array(
			array(
				'name'  => 'create',
				'rules' => array(
					array('rule' => 'required'),
					array('rule' => 'callback_current_date') /* this is a custom rule, take note! */
				)
			),
			array(
				'name' => 'update'
			)
		)
	),
	'email' => array(
		'name'    => 'email',
		'type'    => 'string',   
		'vgroups' => array(
			array(
				'name' => 'create',
				'rules' => array(
					array('rule' => 'required'),
					array('rule' => 'valid_email'),
				)
			),
			array(
				'name' => 'update',
				'rules' => array(
					array('rule' => 'required'),
					array('rule' => 'valid_email'),
				)
			)
		)
	),
	'password'     => array(
		'name'   => 'password',
		'type'   => 'varchar',  
		'vgroups' => array(
			array(
				'name'  => 'create',
				'rules' => array(
					array('rule' => 'required'),
					array('rule' => 'callback_encrypt_password')
				)
			),
			array(
				'name'  => 'update',
				'rules' => array(
					array('rule' => 'callback_encrypt_password')
				)
			)
		)
	),
	'ip'     => array(
		'name'    => 'ip',
		'type'    => 'bigint',  
		'vgroups' => array(
			array(
				'name'  => 'create',
				'rules' => array(
					array('rule' => 'callback_current_ip')
				)
			),
			array(
				'name'  => 'update',
				'rules' => array(
					array('rule' => 'callback_current_ip')
				)
			)
		)
	),
);


/* quick rules */
$config['crud_ignition']['quick_rules'] = array(
	'custom' => 'Custom rule', 
	'ignore' => 'Ignore (CRIG specific)',
	'divider1' => 'divider',
	'callback_' => 'Callback',
	'divider2' => 'divider',
	'required' => 'Required',
	'numeric' => 'Numeric',
	'decimal' => 'Decimal',
	'alpha_numeric' => 'alpha-numeric',
	'alpha_dash' => 'alpha-dash',
	'is_unique[table.column]' => 'Unique',
	'min_length[number]' => 'Min length',
	'max_length[number]' => 'Max length',
	'date[Y-m-d H:i:s]' => 'Current date&time'
);
?>