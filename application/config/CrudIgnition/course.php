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
    'name' => 
    array (
      'name' => 'name',
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
        'NULL' => false,
      ),
    ),
    'offset_left' => 
    array (
      'name' => 'offset_left',
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
    'offset_right' => 
    array (
      'name' => 'offset_right',
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
    'offset_top' => 
    array (
      'name' => 'offset_top',
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
    'offset_bottom' => 
    array (
      'name' => 'offset_bottom',
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
    'location' => 
    array (
      'name' => 'location',
      'type' => 'varchar',
      'length' => '150',
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
        'constraint' => '150',
        'NULL' => false,
      ),
    ),
    'center_x' => 
    array (
      'name' => 'center_x',
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
    'center_y' => 
    array (
      'name' => 'center_y',
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
    'radius' => 
    array (
      'name' => 'radius',
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
  ),
  'controller' => 'manage_courses',
);
