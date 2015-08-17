<?php
	$config = array (
  'table' => 'permissions',
  'model' => 'permission',
  'fields' => 
  array (
    'id' => 
    array (
      'type' => 'primary',
      'name' => 'id',
      'length' => '',
      'digits' => '',
      'decimals' => '',
      'data_source' => '',
      'admin_header' => 'on',
      'default_option' => '',
      'default_value' => '',
      'forge' => 
      array (
        'type' => 'INT',
        'auto_increment' => true,
        'NULL' => false,
      ),
    ),
    'permission' => 
    array (
      'name' => 'permission',
      'type' => 'varchar',
      'length' => '100',
      'digits' => '',
      'decimals' => '',
      'data_source' => '',
      'admin_header' => 'on',
      'default_option' => '',
      'default_value' => '',
      'vgroups' => 
      array (
        0 => 
        array (
          'name' => 'create',
          'state' => '',
          'rules' => 
          array (
          ),
        ),
        1 => 
        array (
          'name' => 'update',
          'state' => '',
          'rules' => 
          array (
          ),
        ),
      ),
      'forge' => 
      array (
        'type' => 'varchar',
        'constraint' => '100',
        'name' => 'permission',
        'NULL' => false,
      ),
    ),
  ),
  'controller' => 'manage_permissions',
);
