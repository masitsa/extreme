<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);

$vaccine_rs = $this->nurse_model->get_vaccines();
$num_vaccines = count($vaccine_rs);

echo "
			<table align='center' class='table table-striped table-hover table-condensed'>
				<tr>
                        <th>Vaccine</th>
                        <th>Yes</th>  
                        <th>No</th> 
						</tr>
					";
						foreach ($vaccine_rs as $key):
							
							$vaccine_id = $key->vaccine_id;
							$vaccine_name = $key->vaccine;
							
							$rs = $this->nurse_model->check_vaccine($patient_id, $vaccine_id);
							$num_patient_vaccine = count($rs);
							
							if($num_patient_vaccine ==0 ){
							
								echo "	
						                             <tr>  
						                                <td align='left'>".$vaccine_name."</td>
						                                <td ><input id='yes".$vaccine_id."' type='checkbox' value='".$vaccine_id."'onclick='save_vaccine(".$vaccine_id.", 1, ".$visit_id.")'/></td>
														<td align='right'><input id='no".$vaccine_id."' type='checkbox' value='".$vaccine_id."' onclick='save_vaccine(".$vaccine_id.", 0, ".$visit_id.")'/></td>
						                              </tr>
														";
							}
								
							else {
									foreach ($rs as $key2):
										$status = $key2->status_id;
										$patient_vaccine_id = $key2->patient_vaccine_id;
									endforeach;
									if ($status == 1){
										
										echo "	
						                             <tr>  
						                                <td align='left'>".$vaccine_name."</td>
						                                <td ><input id='yes".$vaccine_id."' type='checkbox' value='".$vaccine_id."'onclick='delete_vaccine(".$patient_vaccine_id.", ".$visit_id.")' checked='checked'/></td>
														<td align='right'><input id='no".$vaccine_id."' type='checkbox' value='".$vaccine_id."' onclick='save_vaccine(".$vaccine_id.", 0, ".$visit_id.")'/></td>
						                              </tr>
														";
									}
										
									else{
												echo "	
						                             <tr>  
						                                <td align='left'>".$vaccine_name."</td>
						                                <td ><input id='yes".$vaccine_id."' type='checkbox' value='".$vaccine_id."'onclick='save_vaccine(".$vaccine_id.", 1, ".$visit_id.")' /></td>
														<td align='right'><input id='no".$vaccine_id."' type='checkbox' value='".$vaccine_id."' onclick='delete_vaccine(".$patient_vaccine_id.", ".$visit_id.")' checked='checked'/></td>
						                              </tr>
														";
											}
									}
			
							endforeach;
                      echo "
                        </table>";
						
                     