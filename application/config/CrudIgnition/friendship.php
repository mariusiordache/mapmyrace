<?php
	$config = array (
  'table' => 'friendships',
  'model' => 'friendship',
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
    'request_user_id' => 
    array (
      'name' => 'request_user_id',
      'type' => 'int',
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
        'type' => 'int',
        'NULL' => false,
      ),
    ),
    'target_user_id' => 
    array (
      'name' => 'target_user_id',
      'type' => 'int',
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
        'type' => 'int',
        'NULL' => false,
      ),
    ),
    'accepted' => 
    array (
      'name' => 'accepted',
      'type' => 'boolean',
      'length' => '',
      'digits' => '',
      'decimals' => '',
      'data_source' => '',
      'admin_header' => 'on',
      'default_option' => 'CUSTOM',
      'default_value' => '0',
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
        'type' => 'boolean',
        'NULL' => false,
        'default' => '0',
      ),
    ),
  ),
  'controller' => 'manage_friendships',
);
