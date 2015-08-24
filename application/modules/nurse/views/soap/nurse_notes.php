<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);

$get_medical_rs = $this->nurse_model->get_nurse_notes($patient_id,$visit_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;
echo '<div class="row">
		    <div class="col-md-12">
		       <div class="alert alert-danger">Nurse notes entery has been changed. Please find a button named <a hred="#" class="btn btn-sm btn-success" >Save Nurse Notes</a> or <a hred="#" class="btn btn-sm btn-primary" >Update Nurse Notes</a> to save the keyed in notes.. ~ development team </div>
		    </div>
		</div>';
if($num_rows > 0){
	foreach ($get_medical_rs as $key):
		$nurse_notes = $key->nurse_notes;
	endforeach;
	
echo
'

	<div class="row">
		<div class="col-md-12">
			<table align="center">
			 	<tr>
					<td><textarea id="nurse_notes_item" rows="10" cols="100" class="form-control col-md-6">'.$nurse_notes.'</textarea></td>
		         </tr>
			</table>
		</div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="form-group">
	            <div class="col-lg-12">
	                <div class="center-align">
	                      <a hred="#" class="btn btn-large btn-primary" onclick="save_nurse_notes('.$visit_id.')">Update Nurse Notes</a>
	                  </div>
	            </div>
	        </div>
	    </div>
	</div>
';
}

else{
echo
'
	<div class="row">
	    <div class="col-md-12">
		 <table align="center">
		 	<tr>
				<td><textarea id="nurse_notes_item" rows="10" cols="100" class="form-control col-md-6" ></textarea></td>
	         </tr>
		</table>
		</div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="form-group">
	            <div class="col-lg-12">
	                <div class="center-align">
	                      <a hred="#" class="btn btn-large btn-success" onclick="save_nurse_notes('.$visit_id.')">Save Nurse Notes</a>
	                  </div>
	            </div>
	        </div>
	    </div>
	</div>
';
}
	
?>
<script type="text/javascript">
	
	function save_nurse_notes(visit_id){
		var config_url = $('#config_url').val();
        var data_url = config_url+"/nurse/save_nurse_notes/"+visit_id;
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