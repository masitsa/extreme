<?php


$v_data['signature_location'] = base_url().'assets/signatures/';
$v_data['query'] = $this->nurse_model->get_notes(1, $visit_id);

if(!isset($mobile_personnel_id))
{
	$mobile_personnel_id = NULL;
}
$v_data['mobile_personnel_id'] = $mobile_personnel_id;

$notes = $this->load->view('nurse/patients/notes', $v_data, TRUE);

echo '<div id="nurse_notes_section">'.$notes.'</div>';
echo form_open('nurse/save_nurse_notes/'.$visit_id, array('id' => 'canvas_form'));
	
echo
'	<div class="row">
		<div class="col-sm-6" >
			<div class="form-group">
				<label class="control-label">Date</label>
				<input type="date" name="date" class="form-control">
			</div>
		</div>
		
		<div class="col-sm-6" >
			<div class="form-group">
				<label class="control-label">Time</label>
				<input type="time" name="time" class="form-control">
			</div>
		</div>
	</div>';
	

if($mobile_personnel_id != NULL)
{
	echo '
	<!--<div class="row">
		<div class="col-md-12 sigPad" >
			<label class="control-label">Signature</label>
			<div class="sig ">
				<div class="typed"></div>
				<canvas class="pad sigWrapper"></canvas>
				<a id="clear" class="btn btn-default">Clear signature</a>
				<input type="hidden" name="output" class="output">
			</div>
		</div>
	</div>-->
	<div class="row">
		<div class="col-md-12 sigPad" >
			<ul class="sigNav">
				<li class="typeIt"><a href="#type-it"></a></li>
				<li class="drawIt"><a href="#draw-it" class="current" >Click to sign</a></li>
				<li class="clearButton"><a href="#clear">Clear</a></li>
			</ul>
			<div class="sig ">
				<div class="typed"></div>
				<canvas class="pad sigWrapper"></canvas>
				<input type="hidden" name="output" class="output">
			</div>
		</div>
	</div>';
}

echo '	
	<div class="row" style="margin-top:10px;">
		<div class="col-md-12" >
			<textarea id="nurse_notes_item" class="cleditor" rows="10" name="nurse_notes"></textarea>
		</div>
	</div>
	
	<br>
	<div class="row">
	    <div class="col-md-12">
			<div class="center-align">
				<button type="submit" class="btn btn-large btn-primary">Update</button>
			</div>
	    </div>
	</div>
';
echo form_close();
?>

<script type="text/javascript">
	
	function save_nurse_notes(visit_id){
		var config_url = $('#config_url').val();
        var data_url = config_url+"nurse/save_nurse_notes/"+visit_id;
        //window.alert(data_url);
         var nurse_notes = $('#nurse_notes_item').val();//document.getElementById("vital"+vital_id).value;
        $.ajax({
			type:'POST',
			url: data_url,
			data:{notes: nurse_notes},
			dataType: 'text',
			success:function(data){
				alert("You have successfully updated the nurse notes");
			//obj.innerHTML = XMLHttpRequestObject.responseText;
			},
			error: function(xhr, status, error) {
				//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				alert(error);
			}

        });

		
	}
</script>