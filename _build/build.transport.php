<?php
/**
 * Universal Build Script 
 * 
 * Executable via the command line or via a web request.
 *
 * @author everett@craftsmancoding.com
 */

// Start the stopwatch...
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
// Prevent global PHP settings from interrupting
set_time_limit(0);
 
if (!file_exists(dirname(__FILE__).'/build.config.php')) {
    print "This build script expects package details to be defined inside of a build.config.php file.\n";
    print "Please create a valid build.config.php file inside of ".dirname(__FILE__)."\n";
    die();
}
print "Loading config...\n";
include_once(dirname(__FILE__).'/build.config.php');
print "Building package ".PKG_NAME."\n";

// As long as this script is built placed inside a MODX docroot, this will sniff out
// a valid MODX_CORE_PATH.  This will effectively force the MODX_CONFIG_KEY too.
// The config key controls which config file will be loaded. 
// Syntax: {$config_key}.inc.php
// 99.9% of the time this will be "config", but it's useful when dealing with
// dev/prod pushes to have a config.inc.php and a prod.inc.php, stg.inc.php etc.
if (!defined('MODX_CORE_PATH') && !defined('MODX_CONFIG_KEY')) {
    $max = 10;
    $i = 0;
    $dir = dirname(__FILE__);
    while(true) {
        if (file_exists($dir.'/config.core.php')) {
            include $dir.'/config.core.php';
            break;
        }
        $i++;
        $dir = dirname($dir);
        if ($i >= $max) {
            print "Could not find a valid config.core.php file.\n";
            print "Make sure your repo is inside a MODX webroot and try again.\n";
            die();
        }
	}
}

print "Loading {$dir}/config.core.php\n";

if (!defined('MODX_CORE_PATH') || !defined('MODX_CONFIG_KEY')) {
    print "Somehow the loaded config.core.php did not define both MODX_CORE_PATH and MODX_CONFIG_KEY constants.\n";
    die();    
}

if (!file_exists(MODX_CORE_PATH.'model/modx/modx.class.php')) {
    print "modx.class.php not found at ".MODX_CORE_PATH."\n";
    die();
}
require_once(MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php');

// fire up MODX
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modx();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO'); echo '<pre>'; 
flush();

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/' . PKG_NAME_LOWER.'/');

//------------------------------------------------------------------------------
//! Categories
//------------------------------------------------------------------------------
$cat_attributes = array(
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
    xPDOTransport::UNIQUE_KEY => array('category'),
	xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Snippets' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
        'Chunks' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
        'Plugins' => array (
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
			xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
		        'PluginEvents' => array(
		            xPDOTransport::PRESERVE_KEYS => true,
		            xPDOTransport::UPDATE_OBJECT => false,
		            xPDOTransport::UNIQUE_KEY => array('pluginid','event'),
		        ),
    		),
        ),
    )    
);
    
$Category = $modx->newObject('modCategory');
$Category->set('category', PKG_NAME);

$vehicle = $builder->createVehicle($Category, $cat_attributes);
$builder->putVehicle($vehicle);



//------------------------------------------------------------------------------
//! Snippets
//------------------------------------------------------------------------------
$dir = dirname(dirname(__FILE__)).'/core/components/'.PKG_NAME_LOWER.'/elements/snippets/';
$objects = array();
if (file_exists($dir) && is_dir($dir)) {
    print 'Packaging snippets from '.$dir."\n";
    $files = glob($dir.'*.php');
    print_r($files);
    exit;
    foreach($files as $f) {
        $Snippet = $modx->newObject('modSnippet');
        $Snippet->fromArray(array(
            'name' => 'Glocation',
            'description' => '<strong>Version '.PKG_VERSION.'-'.PKG_RELEASE.'</strong> lookup latitude and longitude from a given address and set a series of placeholders. The results for any address are returned from cache whenever possible',
            'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.Glocation.php'),
        ));
        
        $objects[] = $Snippet;   
    }
    
    $Category->addMany($objects);    
}
else {
    print "No Snippets found in {$dir}\n";
}
exit;


//------------------------------------------------------------------------------
//! Chunks
//------------------------------------------------------------------------------
$dir = dirname(dirname(__FILE__)).'/core/components/'.PKG_NAME_LOWER.'/elements/chunks/';
$objects = array();
if (file_exists($dir) && is_dir($dir)) {
    print 'Packaging chunks from '.$dir."\n";
    $files = glob($dir.'*.*');
    print_r($files);
    exit;
    foreach($files as $f) {
        $Chunk = $modx->newObject('modChunk');
        $Chunk->fromArray(array(
            'name' => 'Glocation',
            'description' => '<strong>Version '.PKG_VERSION.'-'.PKG_RELEASE.'</strong> lookup latitude and longitude from a given address and set a series of placeholders. The results for any address are returned from cache whenever possible',
            'snippet' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/snippets/snippet.Glocation.php'),
        ));
        
        $objects[] = $Chunk;   
    }
    
    $Category->addMany($objects);    
}
else {
    print "No Chunks found in {$dir}\n";
}
exit;
//------------------------------------------------------------------------------
//! Plugins
//------------------------------------------------------------------------------
$Events = array();

$Plugin = $modx->newObject('modPlugin');
$Plugin->fromArray(array(
    'name' => 'Geocoding',
    'description' => 'Looks up latitude and longitude coordinates when a page containing location information is saved.',
    'plugincode' => file_get_contents('../core/components/'.PKG_NAME_LOWER.'/elements/plugins/plugin.geocoding.php'),
));

// Plugin Events
$Events['OnDocFormSave'] = $modx->newObject('modPluginEvent');
$Events['OnDocFormSave']->fromArray(array(
    'event' => 'OnDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$Plugin->addMany($Events);

$Category->addMany($Plugin);


//------------------------------------------------------------------------------
//! System Settings
//------------------------------------------------------------------------------
$attributes = array(
	xPDOTransport::UNIQUE_KEY => 'key',
	xPDOTransport::PRESERVE_KEYS => true,
	xPDOTransport::UPDATE_OBJECT => false,	
);
$file = dirname(__FILE__).'/data/settings.php';
if (file_exists($file)) {
    $settings = include($file);
    foreach($settings as $s) {
        $Setting = $modx->newObject('modSystemSetting');
        $Setting->fromArray($s,'',true,true);
        $vehicle = $builder->createVehicle($Setting, $attributes);
        $builder->putVehicle($vehicle);
    
    }
}
else {
    print "No System Settings found.\n";
}

//------------------------------------------------------------------------------
//! Actions and Menus (CMP)
//------------------------------------------------------------------------------

//------------------------------------------------------------------------------
//! Schema?
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
//! Files
//------------------------------------------------------------------------------

// Assets
$dir = dirname(dirname(__FILE__)).'/assets/components/'.PKG_NAME_LOWER;
if (file_exists($dir) && is_dir($dir)) {
    $vehicle->resolve('file', array(
        'source' => $dir,
        'target' => "return MODX_ASSETS_PATH . 'components/';",
    ));
    $builder->putVehicle($vehicle);
}

// Core
$dir = dirname(dirname(__FILE__)).'/core/components/'.PKG_NAME_LOWER;
if (file_exists($dir) && is_dir($dir)) {
    $vehicle->resolve('file', array(
        'source' => $dir,
        'target' => "return MODX_ASSETS_PATH . 'components/';",
    ));
    $builder->putVehicle($vehicle);
}


//------------------------------------------------------------------------------
//! DOCS
//------------------------------------------------------------------------------
$dir = dirname(dirname(__FILE__)).'/core/components/'.PKG_NAME_LOWER.'/docs/';
if (file_exists($dir) && is_dir($dir)) {
    $docs = array();
    $files = glob($dir.'{*.html,*.txt}');
    foreach($files as $f) {
        $stub = basename($f,'.txt');
        $stub = basename($stub,'.html');
        $docs[$stub] = file_get_contents($f);
    }

    if (!empty($docs)) {
        $builder->setPackageAttributes($docs);
    }
}


// Add everything we put into the category
$vehicle = $builder->createVehicle($Category, $cat_attributes);
$builder->putVehicle($vehicle);



// Zip up the package
$builder->pack();

echo '<br/>Package complete. Check your '.MODX_CORE_PATH . 'packages/ directory for the newly created package.';
/*EOF*/