<?php
$menus = array();

$action = $modx->newObject('modAction');
$action->fromArray( array (
  'id' => 0,
  'namespace' => PKG_NAME_LOWER,
  'controller' => 'controllers/index',
  'haslayout' => true,
  'lang_topics' => 'tiles:default',
  'assets' => '',
), '', true, true);

$menuitem = $modx->newObject('modMenu');
$menuitem->fromArray( array (
  'text' => 'Tiles',
  'parent' => 'components',
  'description' => 'Manage your Tiles',
  'icon' => '',
  'menuindex' => 0,
  'params' => '',
  'handler' => '',
  'permissions' => '',
), '', true, true);
$menuitem->addOne($action);
$menus[] = $menuitem;

return $menus;
