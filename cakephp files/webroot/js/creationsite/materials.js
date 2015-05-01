
function clearItemForAdd(parent_id) {
	if(parent_id.length) {
		$('form#itemAddForm #MaterialParentId').val(parent_id);
	} else {
		//$('form#itemAddForm #MaterialParentId').val('');
	}
	$('form#itemAddForm #MaterialName').val('');
	$('form#itemAddForm #MaterialId').val('');
	$('form#itemAddForm #MaterialDescription').val('');
	$('form#itemAddForm #MaterialIsCategory').val(0);
	$('form#itemAddForm #MaterialPricePerUnit').val('');
	$('form#itemAddForm #MaterialPricePerUnitActual').val('');
	$('form#itemAddForm #MaterialUomId').val('');
	$('form#itemAddForm #MaterialFavorite').prop('checked', false);
	
	var type = $('div#data-bank-' + parent_id + ' input#MaterialMaterialTypeId').val();
	$('form#itemAddForm #MaterialMaterialTypeId').val(type);
	
	// Item is in Add mode... Nothing to delete.
	$('#delete-material-item').css('display', 'none');
	
	// Adjust the labels on the forms
	$('#item-status').html('Add');
	
	// Clear all (previous) Validation Errors
	$('form#itemAddForm div.error-message').each( function() {
		$(this).html('');
	});
}

function clearCategoryForAdd(parent_id) {
	if(parent_id.length) {
		$('form#categoryAddForm #MaterialParentId').val(parent_id);
	} else {
		$('form#categoryAddForm #MaterialParentId').val('');
	}
	
	$('form#categoryAddForm #MaterialName').val('');
	$('form#categoryAddForm #MaterialId').val('');
	$('form#categoryAddForm #MaterialIsCategory').val(1);
	
	// Set the material Type to the parent category type... if one exists
	if(parent_id.length) {
		var type = $('div#data-bank-' + parent_id + ' input#MaterialMaterialTypeId').val();
		$('form#categoryAddForm #MaterialMaterialTypeId').val(type);
	} else {
		// No parent... set to default
		$('form#categoryAddForm #MaterialMaterialTypeId').val('form#categoryAddForm #MaterialMaterialTypeId option:first');
	}
	
	// Adjust the labels on the forms
	$('#category-status').html('Add');
	
	// Since there is no id field... Remove the Delete button
	$('#delete-material-category').css('display', 'none');
}

function clearPriceAdjustForm(parent_id) {
	if(parent_id.length) {
		$('#adjust-category-id').val(parent_id);
	} else {
		$('#adjust-category-id').val('');
	}
	
	$('#adjust-perc').val('');
}

function deleteMaterialRecord(id, parent_id, type) {
	// Build a parameter list to lend to the server
	var params = '';
		params = params + 'id:'+ id + '/';
	
	$.ajax({
		url: myBaseUrl + "materials/ajax_delete/"+params,
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
					if(type == 'delete-material-category') {
						$('div#' + id + '-catalog.category-header').parent('div.material-category-container').remove();
						
						// Clear the Category Form ... Keeping the same parent_id.  Clear the Item form placing the select box back at the parent_id.
						clearCategoryForAdd(parent_id);
						clearItemForAdd(parent_id);
						refreshCategoryList(parent_id);
			    	} else {
			    		adjustCategoryItemCount(id, -1);
			    		
			    		// Remove an individual item.
			    		$('div#' + id + '.material-item-container').remove();
			    		
			    		// Clear the Item Form
			    		clearItemForAdd(parent_id);
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

function refreshCategoryList(selectedCategory) {	
	$.ajax({
		url: myBaseUrl + "materials/ajax_category_list_options/",
		beforeSend: function() {
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText,
					obj = jQuery.parseJSON(results),
					option = obj.category_list;
		
				$('div#category-container select#MaterialParentId').empty().append(option).val(selectedCategory);
				$('div#material-container select#MaterialParentId').empty().append(option).val(selectedCategory);
				return true;
			} else {
				return false;
			}
		},
	});
}

function adjustCategoryItemsByPercent(category_id, percent, type) {
	// Build a parameter list to lend to the server
	var params = '';
		params = params + 'category_id:'+ category_id + '/';
		params = params + 'percent:'+ percent + '/';
		params = params + 'calc_type:'+ type + '/';
		
	$.ajax({
		url: myBaseUrl + "materials/ajax_category_item_price_adjust/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText,
					obj = jQuery.parseJSON(results),
					data = obj.data;
				
				$.each(data, function(key, price) {
					$('#' + key + '-price-per-unit').html(price);
				});
				stopPageLoader();
				return true;
			} else {
				stopPageLoader();
				return false;
			}
		},
	});
}

function adjustCategoryItemCount(id, count) {
	$('#' + id + '.material-item-container').parents('div.material-category-container').each( function() {
		var target_id = $(this).find('div.category-header').attr('id').replace('-catalog', ''),
			current = $('#category-count-' + target_id).html();
		$('#category-count-' + target_id).html(parseInt(current) + count);
	});
}

$(document).ready(function(){
	/***********
	 *	A CATEGORY BUTTON IS SELECTED
	 */
	$('div#materials-container').on("click", '.category-header', function() {
		var id = $(this).attr('id').replace('-catalog', ''),
			parent_id = $('div#data-bank-' + id + ' input#MaterialParentId').val(),
			name = $('div#data-bank-' + id + ' input#MaterialName').val(),
			type = $('div#data-bank-' + id + ' input#MaterialMaterialTypeId').val();
			//description = $('div#data-bank-' + id + ' input#MaterialDescription').val(),
			//is_category = $('div#data-bank-' + id + ' input#MaterialIsCategory').val(),
			//price_per_unit = $('div#data-bank-' + id + ' input#MaterialPricePerUnit').val(),
			//uom_id = $('div#data-bank-' + id + ' input#MaterialUomId').val();
		
		// Category
		$('form#categoryAddForm #MaterialParentId').val(parent_id);
		$('form#categoryAddForm #MaterialName').val(name);
		$('form#categoryAddForm #MaterialId').val(id);
		$('form#categoryAddForm #MaterialMaterialTypeId').val(type);
		
		// Now that there is a valide ID... display Delete.
		$('#delete-material-category').css('display', 'block');

		// Item - Clear to Add.
		clearItemForAdd(id);
		
		// Turn on both blocks... User has the ability to add Categories/Items or edit as well.
		$('#fieldset-blocker-category').css('display', 'none');
		$('#fieldset-blocker-item').css('display', 'none');
		
		// Adjust the labels on the forms
		$('#item-status').html('Add');
		$('#category-status').html('Edit');
		return true;
	});
	
	
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
			favorite = $('div#data-bank-' + id + ' input#MaterialFavorite').val();
		
		// Category - clear it!
		$('form#itemAddForm #MaterialParentId').val(parent_id);
		$('form#itemAddForm #MaterialName').val(name);
		$('form#itemAddForm #MaterialId').val(id);
		$('form#itemAddForm #MaterialDescription').val(description);
		$('form#itemAddForm #MaterialIsCategory').val(is_category);
		$('form#itemAddForm #MaterialMaterialTypeId').val(type);
		$('form#itemAddForm #MaterialPricePerUnit').val(price_per_unit);
		$('form#itemAddForm #MaterialPricePerUnitActual').val(price_per_unit_actual);
		$('form#itemAddForm #MaterialUomId').val(uom_id);
		if(favorite == 1) {
			$('form#itemAddForm #MaterialFavorite').prop('checked', true);
		} else {
			$('form#itemAddForm #MaterialFavorite').prop('checked', false);
		}
		
		// An item was selected... allow it to be deleted.
		$('#delete-material-item').css('display', 'block');
		
		// Item - Clear to Add.
		clearCategoryForAdd(0);
		
		// Turn Off Category - When an Item is selected... the only option the user has is to edit the existing item.
		$('#fieldset-blocker-category').css('display', 'block');
		$('#fieldset-blocker-item').css('display', 'none');
		
		// Adjust the labels on the forms
		$('#item-status').html('Edit');
		$('#category-status').html('Add');
		return true;
	});
	
	$(document).on("click", '#clearItem', function() {
		clearItemForAdd($('div#material-container select#MaterialParentId').val());
		return false;
	});
	
	$(document).on("click", '#clearCategory', function() {
		clearCategoryForAdd($('div#category-container input#MaterialId').val());
		return false;
	});
	
	$('#adjust-cost').on('click', function() {
		var category_id = $('#adjust-category-id').val(),
			perc = $('#adjust-perc').val(),
			type = 'margin';
		
		if($('#markup_typeMarkup').prop('checked')) {
			type = 'markup';
		}
		if(perc.length == 0) {
			alert('No percentage was provided');
			return false;
		}
		
		adjustCategoryItemsByPercent(category_id, perc, type);
		clearPriceAdjustForm(0);
		return false;
	});

	
	//**********************************
	// MATERIAL EDIT BLOCK
	// When a material item is 'edit'ed.
	var materials_data_bank = new Array();
	
	/**********************************
	*  CLUETIP BLOCK
	*/
	$('.div-clicktip-material').cluetip({
		activation: 'click',
		sticky: true,
		width: 400,
		closePosition: 'title',
		closeText: 'X',
		local: true,
		onActivate:  function(event) {
			// Obtain the parent_id from the id attribute
			var parent_id = $(this).attr('id').substring(10);
			if(parent_id.length == 0 || parent_id == 0) {
				$('input.parent_id').each(function() {
		        	  $(this).val('');
		        });
			} else {
				$('input.parent_id').each(function() {
		        	  $(this).val(parent_id);
		        });
			}
			
			// Disable the form's submit button
			$('input[type="submit"]').each(function() {
				$(this).attr('disabled','disabled');
			});	
		}
	});

	$('.div-clicktip-material-edit').cluetip({
		activation: 'click',
		sticky: true,
		width: 400,
		closePosition: 'title',
		closeText: 'X',
		local: true,
		onActivate:  function(event) {
			// Obtain the id from the id attribute
			var id = $(this).attr('id').substring(3);
			var name = materials_data_bank[id]['name'];
			var price_per_unit = materials_data_bank[id]['price_per_unit'];
			var uom_id = materials_data_bank[id]['uom_id'];
			var parent_id = materials_data_bank[id]['parent_id'];

			$('input.MaterialId').each(function() {
				$(this).val(id);
			});
			$('input.MaterialName').each(function() {
				$(this).val(name);
			});
			$('input.MaterialPricePerUnit').each(function() {
				$(this).val(price_per_unit);
			});
			$('select.MaterialUomId').each(function() {
				$(this).val(uom_id);
				$(this).find('option').removeAttr('selected');
				$(this).find('option[value="'+uom_id+'"]').attr('selected', 'selected');
			});
			$('input.MaterialParentId').each(function() {
				$(this).val(parent_id);
			});
			
			// Activate the form's submit button
			$('input[type="submit"]').each(function() {
				$(this).removeAttr('disabled');
			});
		}
	});
	/*
	*  END CLUETIP
	*************************/
	
	/**************
	 *  An Input or Select value is changed
	 */
	$('input').on('blur', function() {
		validateMaterialsForm($(this));
		return false;
	});

	$('#MaterialName').on('keypress', function(event) {
		validateMaterialsForm($(this));
	});

	$('select').on('change', function() {
		var form_obj = $(this).parents('form');
		//checkMaterialInputs(form_obj);
		return false;
	});
	
	$('#calculate-adjusted-price').on('change', function() {
		var value = $(this).val(),
			cost = $('form#itemAddForm #MaterialPricePerUnitActual').val(),
			adjustedPrice = Number(cost)/ (1 - (value/100));
		
		if($('#item_markup_typeMarkup').prop('checked')) {
			adjustedPrice = Number(cost) + Number(cost * (value/100));
		}
		
		$('form#itemAddForm #MaterialPricePerUnit').val(adjustedPrice.toFixed(2));
		$(this).val('');
		return false;
	});
	
	/**
	 * DELETE Function
	 */
	$(document).on("click", '.delete-material', function() {
		var type = $(this).attr('id'),
			msg = 'Are you sure you want to delete this item?',
			r=confirm(msg);

	    if (r==true) {
	    	var element = 'material-container';
	    	if(type == 'delete-material-category') {
	    		element = 'category-container';
	    	}
	    	id = $('div#' + element + ' #MaterialId').val();
	    	parent_id = $('div#' + element + ' #MaterialParentId').val();
	    	deleteMaterialRecord(id, parent_id, type);
	    }
	    return false;
	});
	
	/**
	 * FORM SUBMIT
	 * Category Form 
	 * Bind to the form's submit event 
	 */
    $('#categoryAddForm').submit(function() { 
    	var options = { 
	        success: showResponseCategory  // post-submit callback 
	       	}; 
    	 
    	    // bind form using 'ajaxForm' 
    	   	//$('.scheduleJobForm').ajaxForm(options); 
    	
    	startPageLoader();
    	 
    	// Clear all (previous) Validation Errors
		$('div.error-message').each( function() {
			$(this).html('');
		});
		
		// VALIDATE
	    var result = validate($(this), 'category');
		if(result['valid']) { 
			// inside event callbacks 'this' is the DOM element so we first 
	        // wrap it in a jQuery object and then invoke ajaxSubmit.
	        $(this).ajaxSubmit(options); 
		} else {
			// Display Validation Errors
			for(var index in result) {
				$(this).find('#error-message-'+index).html(result[index]);
			}
			stopPageLoader();
		}
		
		// Enable the submit button.
		$(this).find('input[type="submit"]').attr('disabled',false);
		
		// !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
		return false;
    });
    
    /**
	 * Item Form 
	 * Bind to the form's submit event 
	 */
    $('#itemAddForm').submit(function() { 
    	var options = {  
	        success: showResponseItem  // post-submit callback 
       	}; 
       	
       	startPageLoader();
    	
    	// Clear all (previous) Validation Errors
		$('div.error-message').each( function() {
			$(this).html('');
		});
		
		// VALIDATE
	    var result = validate($(this), 'item');
		if(result['valid']) { 
			// inside event callbacks 'this' is the DOM element so we first 
	        // wrap it in a jQuery object and then invoke ajaxSubmit.
	        $(this).ajaxSubmit(options); 
		} else {
			// Display Validation Errors
			for(var index in result) {
				$(this).find('#error-message-'+index).html(result[index]);
			}
			stopPageLoader();
		}
		
		// Enable the submit button.
		$(this).find('input[type="submit"]').attr('disabled',false);
		
		// !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
		return false;
    });
});

function validate(form_obj, form_type) {
	var valid = true,
		data = new Array(),
		error = new Array();

	switch (form_type) {
		case 'category' :
			// Category requires a name.
			data['MaterialName'] = form_obj.find('#MaterialName').val();
			if(!data['MaterialName'].length){
				valid = false;
				error['MaterialName'] = 'Error:  A name is required.';
			}
			break;
		case 'item' :
			// Item requires a name.
			data['MaterialName'] = form_obj.find('#MaterialName').val();
			if(!data['MaterialName'].length){
				valid = false;
				error['MaterialName'] = 'Error:  Name is required.';
			}
			//if(!form_obj.find('#MaterialPricePerUnit').val().length){
			//	valid = false;
			//	error['MaterialPricePerUnit'] = 'Error:  Price Per Unit is required.';
			//}
			break;
	}
	error['valid'] = valid;
    return error; 
} 

// post-submit callback 
function showResponseCategory(responseText, statusText, xhr, $form)  { 
	var obj = jQuery.parseJSON(responseText),
		html = obj.html,
		mode = obj.mode,
		success = obj.success,
		error = obj.error,
		parent_id = obj.parent_id,
		id = obj.id,
		option = obj.category_list;
	
	// Append the html to the beginning of the call_container element.
	if(success > 0){
		if(mode == 'edit') {
			// Grab the name from the Form.
			$('div.material-category-container div#' + id + '-catalog.category-header div.name').html('<b>' + $('form#categoryAddForm #MaterialName').val() + '</b>');
		} else {
			if(parent_id.length > 0) {
				$('div#' + parent_id + '-catalog_toggle_display .category-container-children').html(html);
			} else {
				// TOP LEVEL
				$('div#materials-container').html(html);
			}
			
			// Update the options.. The parent option with category will have the parent_id as selected.
			// Where as the parent option of the item will have the id of the category selected.
			$('div#category-container select#MaterialParentId').empty().append(option).val(parent_id);
			$('div#material-container select#MaterialParentId').empty().append(option).val(id);
		}
		
		//$('#ajax-message-log').html(message);
		//$('#ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');		
	} else {
		// Error
		$('#error-ajax-message-log').html(message);
		$('#error-ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');
	}
	stopPageLoader();
} 

function showResponseItem(responseText, statusText, xhr, $form)  { 
	var obj = jQuery.parseJSON(responseText),
		html = obj.html,
		success = obj.success,
		error = obj.error,
		parent_id = obj.parent_id,
		id = obj.id,
		mode = obj.mode;

	// Append the html to the beginning of the call_container element.
	if(success > 0){
		if(mode == 'add') {
			$('div#' + parent_id + '-catalog_toggle_display .category-container-children').html(html);
		} else {
			$('div#' + id + '.material-item-container').replaceWith(html);
		}
		adjustCategoryItemCount(id, 1);
		clearItemForAdd(0);
		//$('#ajax-message-log').html(message);
		//$('#ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');		
	} else {
		// Error
		$('#error-ajax-message-log').html(message);
		$('#error-ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');
	}
	
	stopPageLoader();
} 

function updateCategorySession(material_id, mode) {
	// Build a parameter list to lend to the server
	var params = '';
	params = params + 'material_id:'+ material_id + '/';
	params = params + 'mode:'+ mode + '/';
	
	$.ajax({
		url: myBaseUrl + "materials/ajax_update_materials_session/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				//var state = data.responseText.trim();
			}
		},
	});
}

function expand_category(category_id) {
	// Adjust buttons displayed.
	$('#materials_button_'+category_id).addClass('expand');
	$('#materials_button_'+category_id).removeClass('collapse');
	
	// Adjust css for the categories table.
	$('#category_table_container_'+category_id).css('display', 'block');

	// Call ajax functionality to update the Materials Session values
	updateCategorySession(category_id, 'add');
}

function collapse_category(category_id) {
	// Adjust buttons displayed.
	$('#materials_button_'+category_id).removeClass('expand');
	$('#materials_button_'+category_id).addClass('collapse');

	// Adjust css for the categories table.
	$('#category_table_container_'+category_id).css('display', 'none');

	// Call ajax functionality to update the Materials Session values
	updateCategorySession(category_id, 'delete');
}

function enableSubmit() {
	$('input[type="submit"]').each(function() {
		$(this).removeAttr('disabled');
	});
}

function checkMaterialInputs(form) {
	var pass = true;

	if(form.find('#MaterialName').val().length == 0) {
		pass = false;
	}
	if(form.find('#MaterialPricePerUnit').val().length == 0) {
		pass = false;
	}
	if(form.find('#MaterialUomId').val() == 0) {
		pass = false;
	}

	if(pass) {
		enableSubmit();
	} else {
		// Disable the form's submit button
		$('input[type="submit"]').each(function() {
			$(this).attr('disabled','disabled');
		});
	}
}

function validateMaterialsForm(obj) {
	// Determine if a category or material is being edited.
	var form_obj = obj.parents('form');
	if(form_obj.attr('id') == 'categoryAddForm') {
		// Category (only this value needs to exist to submit).
		if(obj.length > 0) {
			enableSubmit();
		}
	} else {
		// Material (Check all three before enabling submit)
		//checkMaterialInputs(form_obj);
	}
}