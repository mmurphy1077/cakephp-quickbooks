<?php #echo $this->Html->script('jquery/tinymce/tiny_mce/tiny_mce', array('inline' => false)); ?>
<?php echo $this->Html->script('jquery/tinymce/tinymce.min', array('inline' => false)); ?>

<script type="text/javascript">	
tinyMCE.init({
	// General options
    mode : "textareas",

    //theme : "advanced",
    theme : "modern",
    
	<?php if($__browser_view_mode['view_device'] == 'computer') : ?>
	    toolbar: "styleselect | undo redo | bold italic underline |  aligncenter alignjustify  | bullist numlist outdent indent | link",
		menu : { // this is the complete default configuration
	        edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
	        insert : {title : 'Insert', items : 'link'},
	        view   : {title : 'View'  , items : 'fullscreen'},
	        format : {title : 'Format', items : 'fontselect | bold italic underline strikethrough superscript subscript | formats | removeformat'},
	        table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
	        //tools  : {title : 'Tools' , items : 'spellchecker code'}
	    },
	
	    plugins: [
	    		"advlist autolink lists link charmap print preview anchor",
	    		"searchreplace visualblocks code fullscreen",
	    		"insertdatetime media table contextmenu paste"
		],
	
		height: '400px',
	<?php else :?>
    		height: '200px',
    		toolbar: false,
    		statusbar: false,
    		menubar:false,
    		menu: {},
    		plugins: [], 
	<?php endif; ?>

	
 // Theme options	
	editor_deselector: "mceNoEditor",
	width: '100%',
	
	forced_root_block : false,
    force_p_newlines : false,
    remove_linebreaks : false,
    force_br_newlines : true,              //btw, I still get <p> tags if this is false
    remove_trailing_nbsp : false,   
    verify_html : false,
});
</script>