<li data-id="id-<?php print $data['id']; ?>" data-type="<?php print $data['type']; ?>" class="four columns li_product_image">
    <input type="hidden" name="tiles[]" value="<?php print $data['id']; ?>"/>
	<div class="content-container-white">
		<div class="view view-first">
			<img src="<?php print MODX_ASSETS_URL . $data['image_location']; ?>" alt="<?php print $data['image_location']; ?>" height="100" width="100" />
			<div class="mask">
				<p></p>
				<!--a href="<?php print $data['mgr_controller_url']; ?>update&id=<?php print $data['id']; ?>" class="link info"></a--><a href="<?php print $data['mgr_controller_url']; ?>update&id=<?php print $data['id']; ?>" class="zoom info"></a>
			</div>
		</div>
		<div class="lw-item-caption-container">
			<a href="<?php print $data['mgr_controller_url']; ?>update&id=<?php print $data['id']; ?>" ><span class="bold"><?php print (trim($data['title']))? htmlspecialchars($data['title']):'Title'; ?></a>
		</div>
		<div class="lw-item-text-container">
			<p><?php print (trim($data['description']))? htmlspecialchars($data['description']):'enter description here...'; ?></p>
		</div>
	</div>
	<div class="content-under-container-white"></div>
</li>