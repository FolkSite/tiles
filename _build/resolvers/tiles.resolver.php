<?php
/** 
 Add to extension_packages
 [{"moxycart":{"path":"[[++core_path]]components/moxycart/model/"}},{"articles":{"path":"[[++core_path]]components/articles/model/"}}]
 */

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
            $modx->addExtensionPackage(PKG_NAME_LOWER,"[[++core_path]]components/".PKG_NAME_LOWER."/model/");
            //print_r($modx->packages);
            $manager = $modx->getManager();
            $manager->createObjectContainer('Currency');
            $manager->createObjectContainer('Product');
            $manager->createObjectContainer('Unit');
            $manager->createObjectContainer('VariationType'); 
            $manager->createObjectContainer('VariationTerm');
            $manager->createObjectContainer('ProductVariationTypes');
            $manager->createObjectContainer('ProductVariantTerm');
            $manager->createObjectContainer('Taxonomy');
            $manager->createObjectContainer('Term');
            $manager->createObjectContainer('ProductTerms');
            $manager->createObjectContainer('Category');
            $manager->createObjectContainer('Cart');
            $manager->createObjectContainer('Image');       
            
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $manager->removeObjectContainer('Currency');
            $manager->removeObjectContainer('Product');
            $manager->removeObjectContainer('Spec');
            $manager->removeObjectContainer('VariationType'); 
            $manager->removeObjectContainer('VariationTerm');
            $manager->removeObjectContainer('ProductVariationTypes');
            $manager->removeObjectContainer('ProductTaxonomy');
            $manager->removeObjectContainer('ProductTerms');
            $manager->removeObjectContainer('ProductSpecs');
            $manager->removeObjectContainer('Cart');
            $manager->removeObjectContainer('Image');        
            $modx->removeExtensionPackage($package_name);
            break;
    }
}

return true;