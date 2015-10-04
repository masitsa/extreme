<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);

$get_medical_rs = $this->nurse_model->get_nurse_notes($patient_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;
if($num_rows > 0){
	foreach ($get_medical_rs as $key):
		$nurse_notes = $key->nurse_notes;
	endforeach;
	
echo
'
	 <table align="center">
	 	<tr>
			<td><textarea id="nurse_notes_item" rows="10" cols="100" class="form-control col-md-6" onkeyup="save_nurse_notes('.$visit_id.')">'.$nurse_notes.'</textarea></td>
         </tr>
	</table>
';
}

else{
echo
'
	 <table align="center">
	 	<tr>
			<td><textarea id="nurse_notes_item" rows="10" cols="100" class="form-control col-md-6" onkeyup="save_nurse_notes('.$visit_id.')"></textarea></td>
         </tr>
	</table>
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
        //obj.innerHTML = XMLHttpRequestObject.responseText;
        },
        error: function(xhr, status, error) {
        //alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
        alert(error);
        }

        });

		
	}
</script>