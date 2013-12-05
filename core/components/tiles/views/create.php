<script>
    jQuery(function(){
        $('.datepicker').datepicker();
    });

    /**
     * Ajax save
     *
     */
    function save_tile() {
        var values = jQuery('#create_tile').serialize();
        console.log(values);
        var url = connector_url + 'save';

      jQuery.post( url+"&action=create", values, function(data){
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

 
 
    
</script>


<div id="modx-panel-workspace" class="x-plain container">

    <div class="tiles-msg" id="msg">
        <div id="tiles-result"></div>
        <div id="tiles-result-msg"></div>
    </div>
    
    <div class="tiles-header clearfix">
        <div class="header-title">
            <h2>Create New Tiles</h2>
        </div>
        <div class="buttons-wrapper">
               <a href="#" onclick="javascript:save_tile(); return false;" class="btn">Save</a>
                <a href="<?php print $data['mgr_controller_url'] ?>show_all" class="btn">Close</a>
        </div>
    </div>
    
         <form id="create_tile" action="" method="post" class="clearfix">
            <div class="well">

                <table class="table no-top-border">
                    <tbody>
                         <tr>
                            <td style="vertical-align: top;">
                                 <label for="title">Title</label>
                                <input type="text" class="span8" id="title" name="title" value=""/>
                                 <label for="description">Description</label>
                                <input type="text" class="span8" id="description" name="description" value=""/>
                                
                               
                                <label for="group">Group</label>
                                 <input class="span8" type="text" id="group" name="group" value=""/>
                                  <label for="url">URL</label>
                                <input type="text" class="span8" id="url" name="url" value=""/>
                                 <label for="type">Type</label>
                                <input class="span8" type="text" id="type" name="type" value=""/>
                            </td>
                            <td style="vertical-align: top;">
                               
                                <label for="price">Price</label>
                                <input class="span4" type="text" id="price" name="price" value=""/>
                                <label for="prev_price">Prev. Price</label>
                                <input class="span4" type="text" id="prev_price" name="prev_price" value=""/>
                                <label for="color">Color</label>
                                <input type="text" class="span4" id="colorpickerField1" name="color" value=""/>
                                
                                <div class="input-append date datepicker" data-date="<?php echo date('Y-m-d') ?>" data-date-format="yyyy-mm-dd">
                                      <label for="expireson">Expires On</label>
                                      <input class="span2" type="text" id="expireson" name="expireson" value=""/>

                                      <span class="add-on"><i class="icon icon-calendar"></i></span>
                                </div>

                            </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                              <label for="content">Content</label>
                              <textarea id="content" class="span12" rows="7" name="content"></textarea>
                          </td>
                        </tr>
                    </tbody>
                </table>
        
        </div>
        
    </form>

    
</div>








