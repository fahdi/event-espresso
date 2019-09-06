jQuery(document).ready(function(){
    exts = JSON.parse(ssa_var_ds.extensions);
   // console.log(exts);
	jQuery('.ssa-file').on('change',function(event){
        id = jQuery(this).prev('label').attr('id');
        console.log(id);
        if(typeof id =='undefined')
            id = jQuery(this).attr('id');
        piece = id.split('-');
        qid = piece[2];
      
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if(exts[qid] != '' && typeof exts[qid] != 'undefined')
        {
            var fileExtension = exts[qid].split(',');
            for(i=0;i<fileExtension.length;i++)
                fileExtension[i] =  fileExtension[i].trim();
        }
		
		if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            alert("Only formats are allowed : "+fileExtension.join(', '));
            jQuery(this).val('');
            return;
        }
		element= jQuery(this);
       
        var data = new FormData();        
        
		
        var file_data = jQuery(this).prop("files")[0];
	    data.append("file", file_data);
        data.append("action", 'ssa_upload_file');
        data.append("qst_id", qid);
	    path = ssa_var_ds.ajax_path;
        jQuery(this).siblings('.ssa-loading').css('display','inline-block');
    	jQuery.ajax({
        	url: path,
        	type: 'POST',
        	data: data,
        	cache: false,
        	dataType: 'json',
        	processData: false,
        	contentType: false, 
        	success: function(data, textStatus, jqXHR)
        	{
                 console.log(data.files.url);
        		
            	if(typeof data.error === 'undefined')
            	{
                    element.siblings('.file_value').val(data.files.url);
                    element.siblings('.ssa-loading').css('display','none');
                    element.siblings('.ssa-remove').css('display','inline-block');
                    element.closest('div').find('.ee-required-text').hide();
                    element.removeClass('ee-needs-value');
                    element.addClass('ee-has-value');
            	}
            	else
            	{
                
                    alert('File could not be uploaded successfully. Please try again!');
                    console.log('ERRORS: ' + data.error);
            	}
        	},
        	error: function(jqXHR, textStatus, errorThrown)
        	{
        	    // Handle errors here
           		console.log('ERRORS: ' + textStatus);
            	// STOP LOADING SPINNER
        	}
        });
    });
    jQuery('.ssa-remove').click(function(){
        jQuery(this).siblings('.ssa-file').val('');
        jQuery(this).siblings('.file_value').val('');
        jQuery(this).css('display','none');
    });



});

