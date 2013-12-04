<script>
$(function() {
    $( "#portfolio" ).sortable();
    $( "#portfolio" ).disableSelection();
});

function save_order() {
    var values = jQuery('#all_tiles').serialize();
	var url = connector_url + 'save_order';

    jQuery.post( url, values, function(data){
        alert('Saved or something...');
    });

}
</script>

<div id="modx-panel-workspace" class="x-plain container">
	<div class="tiles-header clearfix">
		<div class="header-title">
			<h2>Tiles</h2>
		</div>
		<div class="buttons-wrapper">
	            <button class="btn" onclick="javascript:save_order(); return false;" id="save_order">Save Order</button>
	        	<a class="btn" href="#">Close</a>
		</div>
	</div>


		<ul class="clearfix" id="product_images"><?php print isset($data['tiles']) ? $data['tiles'] : ''; ?></ul>

        <div class="dropzone-wrap" id="image_dropzone">
        	<div class="dz-default dz-message"><span>Drop files here to upload</span></div>
        </div>


</div>


