<?php
/* @var $object xPDOObject */
/* @var $modx modX */

/* @var array $options */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UPGRADE:
            // $modx->addExtensionPackage($package_name,"[[++core_path]]components/$package_name/model/");
            // $manager = $modx->getManager();
            // Add a field to an existing container
            // $modx->getManager()->addField('fieldname')     
            break;
        case xPDOTransport::ACTION_INSTALL:
            //$modx->addExtensionPackage(PKG_NAME_LOWER,"[[++core_path]]components/".PKG_NAME_LOWER."/model/");
            $core_path = $modx->getOption('core_path');
            $modx->addPackage('tiles',$core_path.'components/'.PKG_NAME_LOWER.'/model/');
            $manager = $modx->getManager();
            $manager->createObjectContainer('Tile');            
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $manager = $modx->getManager();
            $manager->removeObjectContainer('Tile');
            $modx->removeExtensionPackage(PKG_NAME_LOWER);
            break;
    }
}

return true;