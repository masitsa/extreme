<?php
$history =  "<table class='table table-condensed table-striped table-hover'> ";
$count_disease = 0;

if($family_disease_query->num_rows() > 0)
{
	$family_diseases = $family_disease_query->result();
	
	foreach($family_diseases as $dis)
	{	
		$fd_id = $dis->family_disease_id;
		$disease = $dis->family_disease_name;
		
		if($family_query->num_rows() > 0)
		{
			$count_family = 0;
			$family = $family_query->result();
	
			foreach($family as $fam)
			{
				$family = $fam->family_relationship;
				$family_id = $fam->family_id;
				
				if($count_family == 0)
				{	
					if($count_disease == 0)
					{	
						$history =  $history."<tr> <td> </td>";
					}
					
					else
					{
						$history =  $history."<tr> <td>".$disease."</td>";
					}
				}
				
				else if($count_disease == 0)
				{	
					$history =  $history."<td>".$family."</td>";
				}
				
				else{
					
					$rs_history = $this->nurse_model->get_family_history($family_id, $patient_id, $fd_id);
					$num_history = $rs_history->num_rows();
					
					if($num_history == 0)
					{
						$checked = "";
						if($family_id==70)
						{
							$history =  $history."<td  style='background:red;  opacity:0.8;'><input type='checkbox' id='checkbox".$fd_id.$family_id."' onclick='save_condition1(".$fd_id.",".$family_id.", ".$patient_id.")' ".$checked."></td>  ";
						}
						
						else 
						{
							$history =  $history."<td><input type='checkbox' id='checkbox".$fd_id.$family_id."' onclick='save_condition1(".$fd_id.",".$family_id.", ".$patient_id.")' ".$checked."></td>  ";	
						}
					}
					
					else{
						//$fh_id = mysql_result($rs_history, 0, "family_history_id");
						$checked = "checked='checked'";
						
						$history =  $history."<td  style='background:blue; opacity:0.8;'><input type='checkbox' id='checkbox".$fd_id.$family_id."' onclick='delete_condition(".$fd_id.",".$family_id.", ".$patient_id.")' ".$checked."></td>  ";
					}
				}
				$count_family++;
			}
		}
		
		else
		{
			echo 'Family not found';
			break;
		}
		
		$count_disease++;
	}
}
$history .= "</table>";

echo $history;
?>
<script type="text/javascript">
	
	var config_url = $("#config_url").val();
	
	function save_condition1(cond, family, patient_id){
		
		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		//var condition = document.getElementById("checkbox"+cond+family);
			
		url = config_url+"/nurse/save_family_disease/"+cond+"/"+family+"/"+patient_id;
	
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					$.get( config_url+"/nurse/get_family_history/<?php echo $visit_id;?>", function( data ) {
						$("#new-nav").html(data);
					});
				}
			}
			
			XMLHttpRequestObject.send(null);
		}
	}
	
	function delete_condition(cond, family, patient_id){
		
		var XMLHttpRequestObject = false;
			
		if (window.XMLHttpRequest) {
		
			XMLHttpRequestObject = new XMLHttpRequest();
		} 
			
		else if (window.ActiveXObject) {
			XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
		}
		//var condition = document.getElementById("checkbox"+cond+family);
			
		url = config_url+"/nurse/delete_family_disease/"+cond+"/"+family+"/"+patient_id;
	
		if(XMLHttpRequestObject) {
					
			XMLHttpRequestObject.open("GET", url);
					
			XMLHttpRequestObject.onreadystatechange = function(){
				
				if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
					
					$.get( config_url+"/nurse/get_family_history/<?php echo $visit_id;?>", function( data ) {
						$("#new-nav").html(data);
					});
				}
			}
			
			XMLHttpRequestObject.send(null);
		}
	}
	
</script>