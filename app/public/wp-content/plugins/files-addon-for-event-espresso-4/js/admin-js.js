jQuery(document).ready(function(){
	
	price = ssa_var_ds.ext;
	tt = price;

	if(price == null)
		tt ='';
	input = "<input type='text' id='question_ext' name='question_ext' value='"+tt+"'><br><em>Enter comma separated values jpg,jpeg,png,..</em>";
	str ="<tr class='ssa_ext'><th>Allowed file types</th>";
	str +="<td><span>"+input+"</span></td></tr>";
	
    if(jQuery('#update_question_event_form').length > 0 || jQuery('#insert_question_event_form').length > 0 )
    {
		jQuery('.form-table tbody').append(str);
		jQuery('#QST_type').change(function(){
			selected_val = jQuery(this).val();
			
			if(selected_val == 'file')
			{
				jQuery('.ssa_ext').show();
			}
			else
			{
				jQuery('.ssa_ext').hide();	
			}
		});
		if(price != '' && jQuery('#QST_type').val() == 'file')
		{
			jQuery('.ssa_ext').show();
		}
	}
});