<?php
	$config = array (
  'table' => 'courses',
  'model' => 'course',
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
    'date_created' => 
    array (
      'name' => 'date_created',
      'type' => 'timestamp',
      'length' => '',
      'digits' => '',
      'decimals' => '',
      'data_source' => '',
      'admin_header' => 'on',
      'default_option' => 'NULL',
      'default_value' => '',
      'is_null' => 'on',
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
        'type' => 'timestamp',
        'NULL' => true,
        'default' => 'NULL',
      ),
    ),
    'duration' => 
    array (
      'name' => 'duration',
      'type' => 'double',
      'length' => '',
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
        'type' => 'double',
        'NULL' => false,
      ),
    ),
    'length' => 
    array (
      'name' => 'length',
      'type' => 'double',
      'length' => '',
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
        'type' => 'double',
        'NULL' => false,
      ),
    ),
    'file_id' => 
    array (
      'name' => 'file_id',
      'type' => 'varchar',
      'length' => '32',
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
        'constraint' => '32',
        'NULL' => false,
      ),
    ),
    'user_id' => 
    array (
      'name' => 'user_id',
      'type' => 'int',
      'length' => '',
      'digits' => '',
      'decimals' => '',
      'data_source' => '',
      'admin_header' => 'on',
      'default_option' => 'NULL',
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
        'type' => 'int',
        'NULL' => true,
        'default' => 'NULL',
      ),
    ),
  ),
  'controller' => 'manage_courses',
);
