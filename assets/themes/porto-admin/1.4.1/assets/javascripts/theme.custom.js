/* Add here all your JS customizations */
var base_url = $('#base_url').val();

$( document ).ready(function() {
    window.setTimeout(function() { 
		$(".alert-success").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
		
	}, 5000);
	
});

$(document).on("click",".nav-tabs li a",function()
{
	$(".cleditorButton").removeAttr( "disabled" );
	$(".cleditorGroup div").removeClass( "cleditorDisabled" );
	$(".cleditorMain iframe html body").css( "display", 'block' );
	$(".cleditorMain iframe").css( "width", '100%' );
	$(".cleditorMain iframe").css( "height", '100%' );
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

//get item price
$(document).on("change","select#request_item_id",function()
{
	//get value of the selected item
	var item_id = $(this).val();
	var request_event_id = $(this).attr('request_event_id');
	
	$.ajax({
		type:'POST',
		url: base_url+'inventory/requests/get_item_price/'+item_id,
		cache:false,
		contentType: false,
		processData: false,
		dataType: 'json',
		success:function(data)
		{
			$("#request_item_price"+request_event_id).val(data.item_price);
			$("#minimum_hiring_price"+request_event_id).val(data.minimum_hiring_price);
		},
		error: function(xhr, status, error) 
		{
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	
	return false;
});

