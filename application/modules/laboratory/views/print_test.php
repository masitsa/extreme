<?php

//patient details
$visit_type = $patient['visit_type'];
$patient_type = $patient['patient_type'];
$patient_othernames = $patient['patient_othernames'];
$patient_surname = $patient['patient_surname'];
$patient_surname = $patient['patient_surname'];
$patient_number = $patient['patient_number'];
$gender = $patient['gender'];
$patient_insurance_number = $patient['patient_insurance_number'];
$inpatient = $patient['inpatient'];
$visit_type_name = $patient['visit_type_name'];

$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
$visit_date = date('jS F Y',strtotime($this->accounts_model->get_visit_date($visit_id)));

//doctor
$doctor = $this->accounts_model->get_att_doctor($visit_id);

//served by
$served_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));

$rs3 = $this->lab_model->get_comment($visit_id);
$num_rows3 = count($rs3);
$comment = '';

if($num_rows3 > 0)
{
	foreach ($rs3 as $key3):
		$comment = $key3->lab_visit_comment;
	endforeach;
}

$lab_rs = $this->lab_model->get_lab_visit_item($visit_id);
$num_lab_visit = count($lab_rs);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $contacts['company_name'];?> | X Rray results</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css" media="all"/>
        <style type="text/css">
			.receipt_spacing{letter-spacing:0px; font-size: 12px;}
			.center-align{margin:0 auto; text-align:center;}
			
			.receipt_bottom_border{border-bottom: #888888 medium solid;}
			.row .col-md-12 table {
				border:solid #000 !important;
				border-width:1px 0 0 1px !important;
				font-size:10px;
			}
			.row .col-md-12 th, .row .col-md-12 td {
				border:solid #000 !important;
				border-width:0 1px 1px 0 !important;
			}
			.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td
			{
				 padding: 2px;
			}
			
			.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
			.title-img{float:left; padding-left:30px;}
			img.logo{max-height:70px; margin:0 auto;}
		</style>
    </head>
    <body class="receipt_spacing">
    	<div class="row">
        	<div class="col-xs-12">
            	<img src="<?php echo base_url().'assets/logo/'.$contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="img-responsive logo"/>
            </div>
        </div>
    	<div class="row">
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	<?php echo $contacts['company_name'];?><br/>
                    P.O. Box <?php echo $contacts['address'];?> <?php echo $contacts['post_code'];?>, <?php echo $contacts['city'];?><br/>
                    E-mail: <?php echo $contacts['email'];?>. Tel : <?php echo $contacts['phone'];?><br/>
                    <?php echo $contacts['location'];?>, <?php echo $contacts['building'];?>, <?php echo $contacts['floor'];?><br/>
                </strong>
            </div>
        </div>
        
      <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<strong>LABORATORY RESULTS</strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-4 pull-left">
            	<div class="row">
                	<div class="col-md-12">
                    	
                    	<div class="title-item">Patient Name:</div>
                        
                    	<?php echo $patient_surname.' '.$patient_othernames; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Patient Number:</div> 
                        
                    	<?php echo $patient_number; ?>
                    </div>
                </div>
            
            </div>
            
        	<div class="col-md-4">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Number:</div>
                    	<?php echo $this->session->userdata('branch_code').'-INV-00'.$visit_id; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Att. Doctor:</div> 
                        
                    	<?php echo $doctor; ?>
                    </div>
                </div>
            </div>
            
        	<div class="col-md-4 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Date:</div>
                        
                    	<?php echo $visit_date; ?>
                    </div>
                </div>
                <?php if($visit_type != 1){?>
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Insurance:</div>
                        
                    	<?php echo $visit_type_name; ?> - <?php echo $patient_insurance_number; ?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
    	<div class="row receipt_bottom_border">
        	<div class="col-md-12 center-align">
            	<strong>TESTS DONE</strong>
            </div>
        </div>
        
        <?php
		if($num_lab_visit > 0)
		{
		?>
        
		<div class='row'>
			<div class='col-md-12'>
		
				<div class='panel-body'>
					<table class='table table-striped table-hover table-condensed'>
						<tr>
							<th>Test</th>
							<th>Class</th>
							<th>Format</th>
							<th>Result</th>
							<th>Units</th>
							<th>Level</th>
							<th>Male Range</th>
							<th>Female Range</th>
						<tr>
						<?php
						
						foreach ($lab_rs as $key)
						{
							$visit_lab_test_id = $key->visit_lab_test_id;

							// get invoiced charge for this test
								// parameters
							$service_charge_id = $key->service_charge_id;
								// check if test is in visit charge
							$actual_visit_charge = $this->lab_model->check_visit_charge_lab_test($visit_lab_test_id,$visit_id);
							// end of geting the actual charge id
							
							$test_formats = $this->lab_model->get_lab_formats($service_charge_id);
							$num_formats = $test_formats->num_rows();
							
							if($num_formats > 0)
							{
								$r = 0;
								//get test
								$test = $this->lab_model->get_format_test_details($service_charge_id);
								$row = $test->row();
								
								$lab_test_name =$row->lab_test_name;
								$lab_test_class_name =$row->lab_test_class_name;
								
								//$format_rs = $this->lab_model->get_lab_visit_result($visit_lab_test_id);
								foreach ($test_formats->result() as $key2)
								{
									$test_format =$key2->lab_test_formatname;
									$lab_test_format_id =$key2->lab_test_format_id;
									$lab_test_format_units =$key2->lab_test_format_units;
									$lab_test_format_malelowerlimit =$key2->lab_test_format_malelowerlimit;
									$lab_test_format_maleupperlimit =$key2->lab_test_format_maleupperlimit;
									$lab_test_format_femalelowerlimit =$key2->lab_test_format_femalelowerlimit;
									$lab_test_format_femaleupperlimit =$key2->lab_test_format_femaleupperlimit;
									
									$lab_visit_result = '';
									$rs = $this->lab_model->get_format_test_results($lab_test_format_id, $visit_id);
									
									if($rs->num_rows() > 0)
									{
										$row2 = $rs->row();
										$lab_visit_result = $row2->lab_visit_results_result;
									}
									
									if($r > 0)
									{
										$lab_test_name = '';
										$lab_test_class_name = '';
									}
									
									echo "
									<tr>
										<td>".$lab_test_name."</td>
										<td>".$lab_test_class_name."</td>
										<td>".$test_format."</td>
										<td>".$lab_visit_result."</td>
										<td>".$lab_test_format_units."</td>
										<td></td>
										<td>".$lab_test_format_malelowerlimit." - ".$lab_test_format_maleupperlimit ."</td>
										<td>".$lab_test_format_femalelowerlimit." - ".$lab_test_format_femaleupperlimit ."</td>
									</tr>";
									$r++;
								}
							}
							
							//no formats
							else
							{
								//get test
								$test = $this->lab_model->get_test_details($service_charge_id);
								$row = $test->row();
								
								$lab_test_name =$row->lab_test_name;
								$lab_test_class_name =$row->lab_test_class_name;
								
								$rs = $this->lab_model->get_m_test($visit_lab_test_id);//die();
								foreach ($rs as $key2)
								{
									$lab_test_units =$key2->lab_test_units;
									$lab_test_upper_limit =$key2->lab_test_malelowerlimit;
									$lab_test_lower_limit =$key2->lab_test_malelupperlimit;
									$lab_test_upper_limit1 =$key2->lab_test_femalelowerlimit;
									$lab_test_lower_limit1 =$key2->lab_test_femaleupperlimit;
									$lab_visit_result =$key2->lab_visit_result;
									$visit_charge_id =$key2->lab_visit_id;
									
									echo "
									<tr >
										<td>".$lab_test_name."</td>
										<td>".$lab_test_class_name."</td>
										<td></td>
										<td>".$lab_visit_result."</td>
										<td>".$lab_test_units."</td>
										<td></td>
										<td>".$lab_test_upper_limit." - ".$lab_test_lower_limit."</td>
										<td>".$lab_test_upper_limit1." - ".$lab_test_lower_limit1."</td>
									</tr>";
								}
								
								$rsy2 = $this->lab_model->get_test_comment($visit_charge_id);
								$num_rowsy2 = count($rsy2);
								$comment4 = '';

								if($num_rowsy2 > 0){

									foreach ($rsy2 as $keyy):
										$comment4= $keyy->lab_visit_format_comments;
									endforeach;
								}
								echo '
									<tr>
										<td colspan="8"><p class="text-justify">'.$comment4.'</td>
									</tr>';
							}
						}
					?>
        		</table>
			</div>
		</div>
        
		<div class='row' style='margin-bottom: 20px;'>
			<div class='col-md-12'>
				<div class='center-align'><h3>General Comments</h3></div>
				<p class="text-justify"><?php echo $comment;?>
			</div>
		</div>
		
        <?php
		}
		?>
    	
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-10 pull-left">
                <div class="col-md-6 pull-left">
                    Prepared by: <?php echo $served_by;?> 
                </div>
          	</div>
        	<div class="col-md-6 pull-right">
            	<?php echo date('jS M Y H:i a'); ?> Thank you
            </div>
        </div>
    </body>
    
</html>