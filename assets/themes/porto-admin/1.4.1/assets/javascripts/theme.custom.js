/* Add here all your JS customizations */
var base_url = $('#base_url').val();

$( document ).ready(function() {
    window.setTimeout(function() { 
		$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
		
	}, 5000);
});

$(document).on("change",".parent_sections select",function()
{
	var section_parent = $(this).val();
	
	$.ajax({
		type:'POST',
		url: base_url+'hr/personnel/get_section_children/'+section_parent,
		cache:false,
		contentType: false,
		processData: false,
		dataType: 'text',
		success:function(data)
		{	
			$(".child_sections select").html(data);
		},
		error: function(xhr, status, error) 
		{
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	
	return false;
});