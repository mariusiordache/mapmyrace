<script type="text/template" id="photo-template">
	
	<input type="hidden" name="id[]" value="<%= id %>" />
	<input type="hidden" name="connection_id[]" value="<%= connection_id %>" />
	<a href="<%= url %>" title="" class="photoThumb"><img src="<%= thumbs.small %>" alt="" /></a>
	<div class="photoActions">	
		<a href="#" title="edit" class="edit" data-edit="<%= id %>"><span class="iconb" data-icon="&#xe1db;"></span></a>
        <a href="#" title="unlink" class="unlink" data-unlink="<%= connection_type %>/<%= connection_id %>"><span class="iconb" data-icon="&#xe07c;"></span></a>
        <a href="#" title="remove" class="remove" data-edit="<%= id %>"><span class="iconb" data-icon="&#xe136;"></span></a>
		
		<a href="#" title="add to deal" class="addToDeal"><span class="iconb" data-icon="&#xe14b;"></span></a>
		<a href="#" title="remove from deal" class="removeFromDeal"><span class="iconb" data-icon="&#xe14d;"></span></a>
	</div>
	
</script>