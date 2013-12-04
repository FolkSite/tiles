<script>
    jQuery(function(){
    	jQuery('#jcrop_target').Jcrop({
    		onChange: set_coords,
    		onSelect: set_coords
        });
    });

    /**
     * Ajax save
     *
     */
    function save_tile() {
        var values = jQuery('#update_tile').serialize();
    	var url = connector_url + 'save';

	    jQuery.post( url+"&action=update", values, function(data){
            data = jQuery.parseJSON(data);
	        jQuery('#msg').show();
            if (data.success) {
               jQuery('#tiles-result').html('Success');
               jQuery('#msg').addClass('success');
            }
            else {
               jQuery('#tiles-result').html('Error');                
               jQuery('#msg').addClass('error');
            }
            
            jQuery('#tiles-result-msg').html(data.msg);	       
            jQuery('#msg').delay(3200).fadeOut(300);
            // TODO: Close modal window or redirect to the list.
	    });
	    //e.preventDefault();
    }  

    /** 
     * Ajax delete
     *
     */    
    function delete_tile() {
        var answer = confirm('Are you sure you want to delete this tile?');
        if (answer){
            var values = jQuery('#update_tile').serialize();
        	var url = connector_url + 'save';
    
    	    jQuery.post( url+"&action=delete", values, function(data){
                data = jQuery.parseJSON(data);
                //console.log(data);                
    	        jQuery('#msg').show();
                if (data.success) {
                   window.location = "<?php print $data['mgr_controller_url'] ?>show_all";
                }
                else {
                   jQuery('#tiles-result').html('Error');                
                   jQuery('#msg').addClass('error');
                }
                
                jQuery('#tiles-result-msg').html(data.msg);	       
                jQuery('#msg').delay(3200).fadeOut(300);
                // TODO: Close modal window or redirect to the list.
    	    });

        }
        else{
            return false;
        }
    }
    /**
     * Callback function ref'd by jcrop
     * this sets values in hidden form fields
     * so we know how to handle the cropping action.
     */
    function set_coords(c) {
    	jQuery('#x').val(c.x);
    	jQuery('#y').val(c.y);
    	jQuery('#x2').val(c.x2);
    	jQuery('#y2').val(c.y2);
    	jQuery('#w').val(c.w);
    	jQuery('#h').val(c.h);        
    }
    
    /**
     * This triggers the cropping action
     *
     *
     */
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

<h2>Update Tile</h2>

<div class="tiles-msg" id="msg">
	<div id="tiles-result"></div>
	<div id="tiles-result-msg"></div>
</div>

<form id="update_tile" action="" method="post">


    <a href="#" onclick="javascript:save_tile(); return false;" class="btn">Save</a>
    <a href="<?php print $data['mgr_controller_url'] ?>show_all" class="btn">Close</a>
    <br/>
    <br/>
    <input type="hidden" name="id" value="<?php print $data['id']; ?>" />
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="<?php print htmlspecialchars($data['title']); ?>"/><br/>
    <label for="description">Description</label>
    <input type="text" id="description" name="description" value="<?php print htmlspecialchars($data['description']); ?>"/><br/>
    <label for="url">URL</label>
    <input type="text" id="url" name="url" value="<?php print htmlspecialchars($data['url']); ?>"/><br/>

    <label for="color">Color</label>
    <input type="text" id="color" name="color" value="<?php print htmlspecialchars($data['color']); ?>"/><br/>

    <label for="expireson">Expires On</label>
    <input type="text" id="expireson" name="expireson" value="<?php print htmlspecialchars($data['expireson']); ?>"/><br/>

    <label for="price">Price</label>
    <input type="text" id="price" name="price" value="<?php print htmlspecialchars($data['price']); ?>"/><br/>

    <label for="prev_price">Prev. Price</label>
    <input type="text" id="prev_price" name="prev_price" value="<?php print htmlspecialchars($data['prev_price']); ?>"/><br/>


    <label for="type">Type</label>
    <input type="text" id="type" name="type" value="<?php print htmlspecialchars($data['type']); ?>"/><br/>

    <label for="group">Group</label>
    <input type="text" id="group" name="group" value="<?php print htmlspecialchars($data['group']); ?>"/><br/>


    <div id="image_stuff" style="float:right; vertical-align:top; display:inline-block;">
        <label for="image_title">Image Title</label>
        <input type="text" id="image_title" name="image_title" value="<?php print htmlspecialchars($data['image_title']); ?>"/><br/>
        
        <label for="image_alt">Image Alt</label>
        <input type="text" id="image_alt" name="image_alt" value="<?php print htmlspecialchars($data['image_alt']); ?>"/>
        <br/>    
        <?php 
        /*
        We put in a div here as the dropzone in case the image is too small.
        This div handles image swaps
        */
        ?>
        <?php print $data['wide_load']; ?>
        <label>Drag a new image here or double-click to upload a new image.</label>
        <div id="image_dropzone" style="width:500px; height:200px;">
            <?php
            if ($data['image_location']):
            ?>
                
                <div id="target_image">
                    <?php include dirname(__FILE__).'/image.php'; ?>
                </div>
                
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

    </div>
    <br/> 
    <br/>
    <label for="content">Content</label>
    <textarea id="content" cols="60" rows="10" name="content"><?php print htmlspecialchars($data['content']); ?></textarea><br/>
    
    <a href="#" onclick="javascript:save_tile(); return false;" class="btn">Save</a>
    <a href="<?php print $data['mgr_controller_url'] ?>show_all" class="btn">Close</a>
    <a href="#" onclick="javascript:delete_tile(); return false;" class="btn">Delete</a>
</form>