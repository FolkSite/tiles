<script>
    jQuery(function(){
    	jQuery('#jcrop_target').Jcrop({
    		onChange: set_coords,
    		onSelect: set_coords
        });
        $('.datepicker').datepicker();
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


<div id="modx-panel-workspace" class="x-plain container">

    <div class="tiles-msg" id="msg">
        <div id="tiles-result"></div>
        <div id="tiles-result-msg"></div>
    </div>
    
    <div class="tiles-header clearfix">
        <div class="header-title">
            <h2>Tiles</h2>
        </div>
        <div class="buttons-wrapper">
               <a href="#" onclick="javascript:save_tile(); return false;" class="btn">Save</a>
                <a href="<?php print $data['mgr_controller_url'] ?>show_all" class="btn">Close</a>
                <a href="#" onclick="javascript:delete_tile(); return false;" class="btn">Delete</a>
        </div>
    </div>
    
         <form id="update_tile" action="" method="post" class="clearfix">
            <input type="hidden" name="id" value="<?php print $data['id']; ?>" />
            <div class="well">

                <table class="table no-top-border">
                    <tbody>
                         <tr>
                            <td>
                                 <label for="title">Title</label>
                                <input type="text" class="span8" id="title" name="title" value="<?php print htmlspecialchars($data['title']); ?>"/>
                                 <label for="description">Description</label>
                                <input type="text" class="span8" id="description" name="description" value="<?php print htmlspecialchars($data['description']); ?>"/>
                                <label for="image_title">Image Title</label>
                                <input type="text" class="span8" id="image_title" name="image_title" value="<?php print htmlspecialchars($data['image_title']); ?>"/>
                                <label for="image_alt">Image Alt</label>
                                <input type="text" class="span8" id="image_alt" name="image_alt" value="<?php print htmlspecialchars($data['image_alt']); ?>"/>
                                <label for="content">Content</label>
                                <textarea id="content" class="span8"  cols="60" rows="7" name="content"><?php print htmlspecialchars($data['content']); ?></textarea>
                            </td>
                            <td>
                                <label for="url">URL</label>
                                <input type="text" class="span4" id="url" name="url" value="<?php print htmlspecialchars($data['url']); ?>"/>
                                <label for="color">Color</label>
                                <input type="text" class="span4" id="color" name="color" value="<?php print htmlspecialchars($data['color']); ?>"/>
                                
                                <div class="input-append date datepicker" data-date="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                                      <label for="expireson">Expires On</label>
                                      <input class="span2" type="text" id="expireson" name="expireson" value="<?php print htmlspecialchars($data['expireson']); ?>"/>

                                      <span class="add-on"><i class="icon icon-calendar"></i></span>
                                </div>

                                
                                

                                <label for="price">Price</label>
                                <input class="span4" type="text" id="price" name="price" value="<?php print htmlspecialchars($data['price']); ?>"/>
                                <label for="prev_price">Prev. Price</label>
                                <input class="span4" type="text" id="prev_price" name="prev_price" value="<?php print htmlspecialchars($data['prev_price']); ?>"/>
                                <label for="type">Type</label>
                                <input class="span4" type="text" id="type" name="type" value="<?php print htmlspecialchars($data['type']); ?>"/>
                                <label for="group">Group</label>
                                 <input class="span4" type="text" id="group" name="group" value="<?php print htmlspecialchars($data['group']); ?>"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            

            <div id="image_stuff">
                <?php 
                /*
                We put in a div here as the dropzone in case the image is too small.
                This div handles image swaps
                */
                ?>
                <?php print $data['wide_load']; ?>
                <label>Drag a new image here or double-click to upload a new image.</label>
                <div id="image_dropzone">
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
        
        </div>
        
    </form>

    
</div>








