<?php
/** 
 * This file handles manager requests made by Tiles. 
 *
 * PARAMETERS
 *  @param string f function name inside of moxycart.class.php where request gets routed
 *      default: help
 */
 
$core_path = $modx->getOption('tiles.core_path','',MODX_CORE_PATH);

require_once($core_path.'components/tiles/controllers/tilesmgrcontroller.class.php');

$Tiles = new TilesMgrController($modx);

$log_level = $modx->getOption('log_level',$_GET, $modx->getOption('log_level'));
$old_level = $modx->setLogLevel($log_level);

$args = array_merge($_POST,$_GET); // skip the cookies, more explicit than $_REQUEST

$function = $modx->getOption('f',$_GET,'show_all');

$results = $Tiles->$function($args);

$modx->setLogLevel($old_level);
return $results;
/*EOF*/

