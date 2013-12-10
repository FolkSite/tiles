<?php
/* Reused by Ajax request when image is updated 
uniqid to bust cache.
*/
?>
<?php print ($data['wide_load'])? '<span class="warning">'.$data['wide_load'].'</span>': ''; ?>
<img src="<?php print MODX_ASSETS_URL.$data['image_location']; ?>?rand=<?php print uniqid(); ?>" 
height="<?php print $data['visible_height']; ?>" 
width="<?php print $data['visible_width']; ?>" id="jcrop_target"/>
