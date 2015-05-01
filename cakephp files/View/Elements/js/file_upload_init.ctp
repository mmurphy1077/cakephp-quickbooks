<script type="text/javascript">
$(function(){
    var ul = $('#upload ul');

    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        //$(this).parent().find('input').click();
        $('input#file_input').click();
        return false;
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),
        disableImageResize: false,
        maxFileSize: 20000000,
        
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
        	var size = formatFileSize(data.files[0].size); 
        	var mime_type = data.files[0].type;
        	var name = data.files[0].name;

            var tpl = $('<li class="working"><input type="text" value="0" data-width="25" data-height="25"'+
                ' data-fgColor="#3d58b3" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

			var target_id = $('#version_target_id').val();
			if(target_id.length > 0) {
				var append_str = $('div#doc-id-' + target_id);
				append_str.addClass('working');
				data.context = append_str;
			} else {
				var append_str = $(
		            '<div class="file-container working grid" id="doc-id-">' + 
		            '	<div class="col-1of2">' + 
		            '		<div class="doc-info-container doc-type">' +
					'			<div class="name">' + name + '</div>' + 
					'			<div class="details">by <?php  echo $this->Web->humanName($__user['User'], 'first_initial'); ?> &bull; <? echo date('M d, Y'); ?> &bull; ' + size + '</div>' +
					'		</div>' + 
					'	</div>' +
					'	<div class="col-1of2">' + 
					'		<div class="grid">' +
					'			<div class="col-1of2"><div>&nbsp;' +
					'				<div class="progress progress-striped active" aria-valuenow="40" aria-valuemax="100" aria-valuemin="0" role="progressbar">' +
					'					<div class="progress-bar progress-bar-success" style="width:30%;"></div>' +
					'				</div>' +
					'			</div></div>' +
					'			<div class="col-1of2 doc-action-container">' +
					'				<div class="file-actions page-buttons right">' + 
					'					<?php echo $this->Html->link('Delete', '#', array('id' => 'delete_file_', 'class' => 'delete_file')); ?>' +
					'					<?php echo $this->Html->link('New Version', '#', array('id' => 'new_version_', 'class' => 'new_version')); ?>' +
					'					<?php echo $this->Html->link('Download', array('controller' => 'documents', 'action' => 'download')); ?>' +
					'				</div>' +
					'				<div class="file-loading-actions page-buttons">' + 
					'					<?php echo $this->Html->link('Cancel', array('#'), array('id' => '', 'class' => 'cancel bg-red')); ?>' + 
					'				</div>' +
					'			</div>' +
					'		</div>' +
					'	</div>' +
					'</div>');

				// Add the HTML to the UL element
	            data.context = append_str.prependTo('div#uploaded-files-container');

	         	// Determine the file type.  Place the appropriate class in the String object
	            var type_class = '';
	            if (mime_type.toLowerCase().indexOf('zip') >= 0) {
					type_class = 'doc-type-zip';
	            } else if (mime_type.toLowerCase().indexOf('word') >= 0) {
					type_class = 'doc-type-word';
	            } else if ((mime_type.toLowerCase().indexOf('pdf') >= 0) || (mime_type.toLowerCase().indexOf('octet-stream') >= 0)) {
					type_class = 'doc-type-pdf';
	            } else if (mime_type.toLowerCase().indexOf('excel') >= 0) {
					type_class = 'doc-type-excel';
	            } else if (mime_type.toLowerCase().indexOf('powerpoint') >= 0) {
					type_class = 'doc-type-pp';
	            } else if (mime_type.toLowerCase().indexOf('spreadsheet') >= 0) {
					type_class = 'doc-type-ss';
	            } else if (mime_type.toLowerCase().indexOf('text') >= 0) {
					type_class = 'doc-type-text';
	            } else if (mime_type.toLowerCase().indexOf('photoshop') >= 0) {
					type_class = 'doc-type-ps';
	            }
	            append_str.find('div.doc-info-container').addClass(type_class);
			}
			
            // Listen for clicks on the cancel icon
            append_str.find('div.file-loading-actions a.cancel').click(function(){
                if(append_str.hasClass('working')){
                    jqXHR.abort();
                }

                append_str.fadeOut(function(){
                	append_str.remove();
                });

				return false;
            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        done: function (e, data) {
        	var obj = jQuery.parseJSON(data.result);
			var success = obj.success;
			var target = obj.target;
			var result = obj.result;
			var error = obj.error;

		    if(data.textStatus == 'success') {
		    	$('div#doc-id-' + target).replaceWith(result);
			    // if target is not empty... remove it.
			    if(target.length > 0) {
			    	$('div#doc-id-').remove();
			    }
		    	//data.context.find('div.file-actions').html(result);
		    } else {

		    }

		    clearVersioning();
			//data.context.removeClass('working');
		
            //alert(data.result);
            //$.each(data.result.files, function (index, file) {
            //    $('<p/>').text(file.name).appendTo(document.body);
            //});
        }, 
        
        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            /* data.context.find('input').val(progress).change(); */
            data.context.find('div.progress-bar').css('width', progress+'%').change();
            if(progress == 100){
                //data.context.removeClass('working');
                //data.context.find('div.page-buttons').css('display', 'block');
            }
        },

        fail:function(e, data){
            alert('fail');
            // Something has gone wrong!
            data.context.addClass('error');      
        }

    });

    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }
 
    $('.button_upload_file').bind('click', function() {
		var id = $(this).attr('id').substring(19);
		$('#file_upload_container_'+id).toggle();
    	clearVersioning();
    	return false;
    });


    /**************************
     * DELETE
     */
     function deleteFile(id) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'document_id:'+ id + '/';
			
		$.ajax({
			url: myBaseUrl + "documents/ajax_delete/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				//document.getElementById("ajax-loader-contacts").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var result = obj.result;
					var error = obj.error;
					$('#doc-id-' + id).remove();
					
					$('table#contact_search_table').html(result);
				} else {
					
				}
				//document.getElementById("ajax-loader-contacts").style.display = 'none';
			},
		});
	}

 	$('div#uploaded-files-container').on('click', ".delete_file", function(){
		var id = $(this).attr('id').replace('delete_file_', '');
		deleteFile(id);
		return false;
	});

	function clearVersioning() {
		$('input#version_target_id').val(null);
		$('#version_title').html('');
	}
	
 	$('div#uploaded-files-container').on('click', ".new_version", function(){
		var id = $(this).attr('id').replace('new_version_', '');

		// Obtain the file_upload_container... make sure the container is open
		$('div#uploaded-files-container').prev('.file_upload_container').css('display', 'block');
		$('input#version_target_id').val(id);
		title = 'Replace file: <b>' + $('#doc_name_' + id).html() + '</b>';
		$('#version_title').html(title);
		return false;
	});
    
});
</script>