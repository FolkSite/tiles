<li class="li_product_image">
    <img src="<?php print MODX_ASSETS_URL . $data['image_location']; ?>" height="100" width="100" alt="<?php print $data['image_location']; ?>"/>
    <input type="hidden" name="images[]" value="<?php print $data['id']; ?>" />
	<a href="#" data-image_id="<?php print $data['id']; ?>" data-file="<?php //print $data['url']; ?>" class="remove-img" >Remove</a>
	<a href="<?php print $data['mgr_controller_url']; ?>update&id=<?php print $data['id']; ?>" class="edit-img" data-toggle="modal" data-target="#update-image">
	  Edit
	</a>
</li>