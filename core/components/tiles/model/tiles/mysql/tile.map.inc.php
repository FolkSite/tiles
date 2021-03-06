<?php
$xpdo_meta_map['Tile']= array (
  'package' => 'tiles',
  'version' => '1.0',
  'table' => 'tiles',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'title' => NULL,
    'description' => NULL,
    'content' => '',
    'url' => NULL,
    'image_location' => NULL,
    'image_alt' => NULL,
    'image_title' => NULL,
    'thumbnail_url' => NULL,
    'width' => NULL,
    'height' => NULL,
    'size' => NULL,
    'color' => NULL,
    'expireson' => NULL,
    'price' => NULL,
    'prev_price' => NULL,
    'type' => NULL,
    'group' => NULL,
    'seq' => NULL,
    'is_active' => 1,
    'timestamp_created' => 'CURRENT_TIMESTAMP',
    'timestamp_edited' => NULL,
  ),
  'fieldMeta' => 
  array (
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => '',
    ),
    'content' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => 'Link somewhere else',
    ),
    'image_location' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => 'Acts as both path and URL: rel to MODX_ASSETS_PATH or MODX_ASSETS_URL',
    ),
    'image_alt' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => '',
    ),
    'image_title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'thumbnail_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'width' => 
    array (
      'dbtype' => 'int',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
    ),
    'height' => 
    array (
      'dbtype' => 'int',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
    ),
    'size' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'color' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '16',
      'phptype' => 'string',
      'null' => false,
      'comment' => 'HTML color code',
    ),
    'expireson' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'comment' => 'When this offer expires.',
    ),
    'price' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '8,2',
      'phptype' => 'float',
      'null' => true,
    ),
    'prev_price' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '8,2',
      'phptype' => 'float',
      'null' => true,
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => '',
    ),
    'group' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'comment' => '',
    ),
    'seq' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
    ),
    'is_active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 1,
    ),
    'timestamp_created' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
      'default' => 'CURRENT_TIMESTAMP',
    ),
    'timestamp_edited' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
    ),
  ),
);
