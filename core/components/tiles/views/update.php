<script>
    jQuery(function(){
    	jQuery('#jcrop_target').Jcrop({
    		onChange: set_coords,
    		onSelect: set_coords
        });
    });

    function save_tile() {
//	jQuery('#update_tile').on('submit',function(e){
        console.log('Updating product.');
        var values = jQuery('#update_tile').serialize();
    	var url = connector_url + 'save';

	    jQuery.post( url+"&action=update", values, function(data){
//	       alert('Back!');
            // Close modal window or redirect to the list.
/*
	    	jQuery('.moxy-msg').show();
	    	data = jQuery.parseJSON(data);
	    	if(data.success == true) {
	    		jQuery('#moxy-result').html('Success');
	    	} else{
	    		jQuery('#moxy-result').html('Failed');
	    	}
	    	jQuery('#moxy-result-msg').html(data.msg);
	    	jQuery(".moxy-msg").delay(3200).fadeOut(300);
*/
	    });
	    //e.preventDefault();
//    })
    }  
    
    function set_coords(c) {
    	jQuery('#x').val(c.x);
    	jQuery('#y').val(c.y);
    	jQuery('#x2').val(c.x2);
    	jQuery('#y2').val(c.y2);
    	jQuery('#w').val(c.w);
    	jQuery('#h').val(c.h);        
    }
    
    function crop() {
        console.log('Cropping image.');
        var values = jQuery('#update_tile').serialize();
    	var url = connector_url + 'image_crop';

	    jQuery.post( url, values, function(data){
//	       alert('Back!');
            // Close modal window or redirect to the list.
/*
	    	jQuery('.moxy-msg').show();
	    	data = jQuery.parseJSON(data);
	    	if(data.success == true) {
	    		jQuery('#moxy-result').html('Success');
	    	} else{
	    		jQuery('#moxy-result').html('Failed');
	    	}
	    	jQuery('#moxy-result-msg').html(data.msg);
	    	jQuery(".moxy-msg").delay(3200).fadeOut(300);
*/
	    });
	    //e.preventDefault();
//    })
    }  
    
</script>
<form id="update_tile" action="" method="post">
    <input type="hidden" name="id" value="<?php print $data['id']; ?>" />
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?php print htmlspecialchars($data['title']); ?>"/><br/>
    <label for="description">Description</label>
    <input type="text" id="description" name="description" value="<?php print htmlspecialchars($data['description']); ?>"/><br/>
    <label for="url">URL</label>
    <input type="text" id="url" name="url" value="<?php print htmlspecialchars($data['url']); ?>"/><br/>

    <label for="type">Type</label>
    <input type="text" id="type" name="type" value="<?php print htmlspecialchars($data['type']); ?>"/><br/>

    <label for="group">Group</label>
    <input type="text" id="group" name="group" value="<?php print htmlspecialchars($data['group']); ?>"/><br/>


    <!-- We put in a div here as the dropzone in case the image is too small -->
    <?php print $data['wide_load']; ?>
    <label>Drag a new image here or double-click to upload a new image.</label>
    <div id="image_dropzone" style="width:500px; height:200px;">
        <?php
        if ($data['image_location']):
        ?>
            
            <img src="<?php print MODX_ASSETS_URL.$data['image_location']; ?>" 
                height="<?php print $data['visible_height']; ?>" 
                width="<?php print $data['visible_width']; ?>" id="jcrop_target"/>
                
                <input type="hidden" id="x" name="x" />
                <input type="hidden" id="x2" name="x2" />
                <input type="hidden" id="y" name="y" />
                <input type="hidden" id="y2" name="y2" />
                <input type="hidden" id="w" name="w" />
                <input type="hidden" id="h" name="h" />
                <input type="hidden" id="width" name="width" value="<?php print $data['width']; ?>"/>
                <input type="hidden" id="height" name="height" value="<?php print $data['height']; ?>"/>

                
                <span class="btn" onclick="javascript:crop(); return false;">Crop</span>
        <?php
        endif;
        ?>
    </div>
    
    <br/>
    <label for="content">Content</label>
    <textarea id="content" cols="60" rows="10" name="content"><?php print htmlspecialchars($data['content']); ?></textarea><br/>
    
    <br/>

    <input type="submit" onclick="javascript:save_tile(); return false;"value="Save" />
    <a href="<?php print $data['mgr_controller_url'] ?>show_all" class="btn">Close</a>
</form>