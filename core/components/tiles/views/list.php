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
<form id="all_tiles">  
<!-- PAGE TITLE -->
	<div class="container m-bot-25 clearfix">
		<div class="sixteen columns">
			<h1 class="page-title">Tiles</h1>
			
            <span class="btn" onclick="javascript:save_order(); return false;">Save Order</span>

			     <?php
			     /*
			     // TODO: filter by type or group?
				<ul id="filter"> 
					<li class="current all"><a href="#">All</a></li> 
					<li class="category1"><a href="#">Web</a></li> 
					<li class="category2"><a href="#">Design</a></li> 
					<li class="category3"><a href="#">Illustration</a></li> 
					<li class="category4"><a href="#">Photo</a></li> 
				</ul>
				*/
				?>
		</div>
	</div>	


	<div class="container filter-portfolio clearfix" id="image_dropzone">


            <?php print $data['tiles']; ?>

	</div>
	
</form>	
<?php
/*
	
<!-- PAGINATION-1 -->
	<div class="container m-bot-25 clearfix">
		<div class="sixteen columns pagination-1-container">
			<ul class="pagination-1">
				<li>
					<a class="pag-prev" href="#"></a>
				</li>
				<li>
					<a class="pag-current" href="#">1</a>
				</li>
				<li>
					<a href="#">2</a>
				</li>
				<li>
					<a href="#">3</a>
				</li>
				<li>
					<a class="pag-next" href="#"></a>
				</li>
			</ul>
		</div>
	</div>	
*/
?>
