<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);
$nurse_notes = '';
$get_medical_rs = $this->nurse_model->get_nurse_notes($patient_id,$visit_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;

if($num_rows > 0){
	foreach ($get_medical_rs as $key):
		$nurse_notes = $key->nurse_notes;
	endforeach;
}

echo form_open('nurse/save_nurse_notes/'.$visit_id, array('id' => 'canvas_form'));
	
echo
'	<div class="row">
		<div class="col-sm-6" >
			<div class="form-group">
				<label class="control-label">Date</label>
				<input type="date" id="date" class="form-control">
			</div>
		</div>
		
		<div class="col-sm-6" >
			<div class="form-group">
				<label class="control-label">Time</label>
				<input type="time" id="time" class="form-control">
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 sigPad" >
			<label class="control-label">Signature</label>
			<div class="sig ">
				<div class="typed"></div>
				<canvas class="pad sigWrapper"></canvas>
				<a id="clear" class="btn btn-default">Clear signature</a>
				<input type="hidden" name="output" class="output">
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12" >
			<textarea id="nurse_notes_item" class="cleditor" rows="10"></textarea>
		</div>
	</div>
	
	<br>
	<div class="row">
	    <div class="col-md-12">
			<div class="center-align">
				<a hred="#" class="btn btn-large btn-primary" onclick="save_nurse_notes('.$visit_id.')">Update Nurse Notes</a>
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