$(document).ready( function() {
	/***********
	 *	A ITEM BUTTON IS SELECTED
	 */
	$('div#materials-container').on("click", '.material-item-container', function() {
		var id = $(this).attr('id'),
			parent_id = $('div#data-bank-' + id + ' input#MaterialParentId').val(),
			name = $('div#data-bank-' + id + ' input#MaterialName').val();
			description = $('div#data-bank-' + id + ' input#MaterialDescription').val(),
			is_category = $('div#data-bank-' + id + ' input#MaterialIsCategory').val(),
			type = $('div#data-bank-' + id + ' input#MaterialMaterialTypeId').val(),
			price_per_unit = $('div#data-bank-' + id + ' input#MaterialPricePerUnit').val(),
			price_per_unit_actual = $('div#data-bank-' + id + ' input#MaterialPricePerUnitActual').val(),
			uom_id = $('div#data-bank-' + id + ' input#MaterialUomId').val();
		
		return true;
	});
	
	$(document).on("click", 'tr.material-assembly-name-container', function() {
		$('tr.material-assembly-name-container').each( function() {
			$(this).removeClass('selected');
		});
		
		var id = $(this).attr('id').replace('material-assembly-name-container-', '');
		$(this).addClass('selected');
		$('tr.material-assembly-item-container').each( function() {
			$(this).css('display', 'none');
		});
		
		$('tr.material-assembly-item-container-' + id).each( function() {
			$(this).css('display', 'table-row');
		});
		
		return true;
	});
	
	function deleteAssembly(assembly_id, material_id, type) {
		// Build a parameter list to lend to the server
		var params = '';
			params = params + 'assembly_id:'+ assembly_id + '/';
			params = params + 'material_id:'+ material_id + '/';
			params = params + 'type:'+ type + '/';
		
		$.ajax({
			url: myBaseUrl + "material_assemblies/ajax_delete/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				stopPageLoader();
				
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						error = obj.error;
					
					if(success == 1) {
						// Remove the Material depending on the type
						if(type == 'assembly') {
							$('tr#material-assembly-name-container-' + assembly_id).remove();
							$('tr.material-assembly-item-container-' + assembly_id).each( function() {
								$(this).remove();
							});
				    	} else {
				    		// Assembly Item
				    		$('tr#material-assembly-item-' + material_id + '.material-assembly-item-container-' + assembly_id).remove();
				    	}
					} else {
						// Error
					}
					return success;
					
				} else {
					return false;
				}
			},
		});
	}
	
	function addAssmeblyItem(assembly_id, material_id) {
		// Build a parameter list to lend to the server
		var params = '';
			params = params + 'assembly_id:'+ assembly_id + '/';
			params = params + 'material_id:'+ material_id + '/';
		
		$.ajax({
			url: myBaseUrl + "material_assemblies/ajax_add/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				stopPageLoader();
				
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						error = obj.error;
					
					if(success == 1) {
						var new_row = $('tr#material-assembly-item-template').clone();
						new_row.removeClass('hide');
						
						// Grab the material data
						var name = $('div#data-bank-' + material_id + ' input#MaterialName').val(),
							desc = $('div#data-bank-' + material_id + ' input#MaterialDescription').val(),
							list = $('div#data-bank-' + material_id + ' input#MaterialPricePerUnit').val();
						
						//new_row.find('tr#material-assembly-item-template').attr('id', 'material-assembly-item-' + material_id).removeClass('material-assembly-item-container-template').addClass('material-assembly-item-container-' + assembly_id).css('display', 'table-row');
						new_row.attr('id', 'material-assembly-item-' + material_id).removeClass('material-assembly-item-container-template').addClass('material-assembly-item-container-' + assembly_id).css('display', 'table-row');
						new_row.find('td#name').html(name);
						new_row.find('td#description').html(desc);
						new_row.find('td#list').html('$'+list);
						new_row.find('div#assembly-id-contianer').html(assembly_id);
						new_row.find('a#assembly-item-delete-').attr('id', 'assembly-item-delete-' + material_id);
						
						// Look for the last item in the assmebly
						var last_item = $('table#assembly_container tr.material-assembly-item-container-' + assembly_id).last();
						if(last_item.length) {
							// Items already exist within the Assembly.
							last_item.after(new_row);
						} else {
							// First Item within the Assembly.
							$('tr#material-assembly-name-container-' + assembly_id).after(new_row);
						}
					} else {
						// Error
					}
					return success;
					
				} else {
					return false;
				}
			},
		});
	}
	
	$(document).on("click", 'a.assembly-delete', function() {
		var assembly_id = $(this).attr('id').replace('assembly-delete-', '');
		deleteAssembly(assembly_id, 0, 'assembly');
		return false;
	});
	
	$(document).on("click", 'a.assembly-item-delete', function() {
		var material_id = $(this).attr('id').replace('assembly-item-delete-', ''),
			assembly_id = $('tr#material-assembly-item-' + material_id).find('#assembly-id-contianer').html();
		
		deleteAssembly(assembly_id, material_id, 'assembly_item');
		return false;
	});
	
	$(document).on("click", 'div.material-item-container', function() {
		var material_id = $(this).attr('id'),
			assembly_id = 0;
		
		$('.material-assembly-name-container').each( function() {
			if($(this).hasClass('selected')) {
				assembly_id = $(this).attr('id').replace('material-assembly-name-container-', '');
			}
		});
		
		if(assembly_id > 0) {
			addAssmeblyItem(assembly_id, material_id);
		} else {
			alert('Select the Assmbly the item is to be added.')
		}
		return false;
	});
	
});	