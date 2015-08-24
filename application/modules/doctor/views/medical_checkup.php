<?php

//patient details
$visit_type = $patient['visit_type'];
$patient_type = $patient['patient_type'];
$patient_othernames = $patient['patient_othernames'];
$patient_surname = $patient['patient_surname'];
$patient_surname = $patient['patient_surname'];
$patient_number = $patient['patient_number'];
$gender = $patient['gender'];

$today = date('jS F Y H:i a',strtotime(date("Y:m:d h:i:s")));
$visit_date = date('jS F Y',strtotime($this->accounts_model->get_visit_date($visit_id)));

//doctor
$doctor = $this->accounts_model->get_att_doctor($visit_id);

//served by
$served_by = $this->accounts_model->get_personnel($this->session->userdata('personnel_id'));



$title = 'Medical Checkup Form';
$heading = 'Checkup';
$number = 'SUMC/MED/00'.$visit_id;
?>

<!DOCTYPE html>
<html lang="en">
	<style type="text/css">
		.receipt_spacing{letter-spacing:0px; font-size: 12px;}
		.center-align{margin:0 auto; text-align:center;}
		
		.receipt_bottom_border{border-bottom: #888888 medium solid;}
		.row .col-md-12 table {
			border:solid #000 !important;
			border-width:1px 0 0 1px !important;
			font-size:10px;
			padding: 0px;
		}
		.row .col-md-12 th, .row .col-md-12 td {
			border:solid #000 !important;
			border-width:0 1px 1px 0 !important;
			padding-top: 0;
			padding-bottom: 0;
		}
		
		.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
		.title-img{float:left; padding-left:30px;}
	</style>
    <head>
        <title>SUMC | <?php echo $title;?></title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link href="<?php echo base_url();?>assets/bluish/style/bootstrap.css" rel="stylesheet" media="all">
        
         <script type="text/javascript" src="<?php echo base_url();?>assets/bluish/js/jquery.js"></script>
    </head>
    <body class="receipt_spacing">
    	<div class="row" >
        	<img src="<?php echo base_url();?>images/strathmore.gif" class="title-img"/>
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	Strathmore University Medical Center<br/>
                    P.O. Box 59857 00200, Nairobi, Kenya<br/>
                    E-mail: sumedicalcentre@strathmore.edu. Tel : +254703034011<br/>
                    Madaraka Estate<br/>
                </strong>
            </div>
        </div>
        
      <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<strong><?php echo $title;?></strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-6 pull-left">
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
            
        	<div class="col-md-6 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Checkup Number:</div>
                        
                    	<?php echo $number; ?>
                    </div>
                </div>
            	
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Att. Doctor::</div> 
                        
                    	<?php echo $doctor; ?>
                    </div>
                </div>
            </div>
        </div>
        
    
     <div style="padding-left:10px;padding-right:10px">
        		 <?php
$exam_categories = $this->nurse_model->medical_exam_categories();

		if($exam_categories->num_rows() > 0)
		{
			$exam_results = $exam_categories->result();
			
			foreach ($exam_results as $exam_res)
			{
				$mec_name = $exam_res->mec_name;
				$mec_id = $exam_res->mec_id;
				
				$illnesses = $this->nurse_model->get_illness($visit_id, $mec_id);
				
				if($illnesses->num_rows() > 0)
				{
					$illnesses_row = $illnesses->row();
					$mec_result= $illnesses_row->infor;
				}
				
				else
				{
					$mec_result= '';
				}
				
				if($mec_name=="Family History")
				{
					?>
		            <div class="row">
		                <div class="col-md-12">
		                    <!-- Widget -->
		                    <div class="widget">
		                        <!-- Widget head -->
		                        <div class="widget-head">
		                          <h5 class="pull-left" style="font-weight:bold"><?php echo $mec_name; ?></h5>
		                          <div class="widget-icons pull-right">
		                          </div>
		                          <div class="clearfix"></div>
		                        </div>              
		                        
		                        <!-- Widget content -->
		                        <div class="widget-content">
		                            <div class="padd">
		                                
		                                <div id="checkup_history"></div>
		                                
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		   			<?php 
				}
				
				else if(($mec_name=="Present Illness")||($mec_name=="Past Illness")) 
				{
					?>
		            <div class="row">
		                <div class="col-md-6">
		            		
		                      <!-- Widget -->
		                      <div class="widget boxed">
		                        <!-- Widget head -->
		                        <div class="widget-head">
		                          <h5 class="pull-left" style="font-weight:bold"><?php echo $mec_name; ?></h5>
		                          <div class="widget-icons pull-right">
		                          </div>
		                          <div class="clearfix"></div>
		                        </div>             
		    				
		                    <!-- Widget content -->
		                        <div class="widget-content">
		                            <div class="padd">
		                           		 <div class="col-md-6">
		                               		<?php echo $mec_result; ?>
		                                </div>

		                            </div>
		    
		                         </div>
		                    
		                    </div>
		                </div>
		            </div>
		            <?php
				}
				
				else if(($mec_name=="Physiological History")||($mec_name=="General Physical Examination")||($mec_name=="Head Physical Examination")||($mec_name=="Neck Physical Examination")||($mec_name=="Cardiovascular System Physical Examination")||($mec_name=="Respiratory System Physical Examination")||($mec_name=="Abdomen Physical Examination")||($mec_name=="Nervous System Physical Examination")) 
				{	
					?>

					        <div class="row">
					            <div class="col-md-12">

					              <!-- Widget -->
					              <div class="widget boxed">
					                    <!-- Widget head -->
					                      <div class="widget-head">
				                          <h5 class="pull-left" style="font-weight:bold"><?php echo $mec_name; ?></h5>
				                          <div class="widget-icons pull-right">
				                          </div>
				                          <div class="clearfix"></div>
				                        </div>            

					                <!-- Widget content -->
					                    <div class="widget-content">
					                        <div class="padd">
					                            <table class="table table-striped table-hover table-condensed">
					                            <?php
					                            $category_items = $this->nurse_model->mec_med($mec_id);
					                            
					                            if($category_items->num_rows() > 0)
					                            {
					                                $ab=0;
					                                $category_items_result = $category_items->result();
					                                
					                                foreach($category_items_result as $cat_res)
					                                {
					                                    $item_format_id = $cat_res->item_format_id;
					                                    $ab++;
					                                    
					                                    $cat_items = $this->nurse_model->cat_items($item_format_id, $mec_id);
					                                    
					                                    if($cat_items->num_rows() > 0)
					                                    {
					                                        $cat_items_result = $cat_items->result();
					                                        
					                                        foreach($cat_items_result as $items_res)
					                                        {
					                                            $cat_item_name = $items_res->cat_item_name;
					                                            $cat_items_id1 = $items_res->cat_items_id;
					                                            ?>
					                                            <tr> <td><?php echo $cat_item_name; ?> </td>
					                                            <?php
					                                            
					                                            $items_cat = $this->nurse_model->get_cat_items($item_format_id, $mec_id);
					                                            
					                                            if($items_cat->num_rows() > 0)
					                                            {
					                                                $items_result = $items_cat->result();
					                                                
					                                                foreach($items_result as $res)
					                                                {
					                                                    $cat_item_name = $res->cat_item_name;
					                                                    $cat_items_id = $res->cat_items_id;
					                                                    $item_format_id1 = $res->item_format_id;
					                                                    $format_name = $res->format_name;
					                                                    $format_id = $res->format_id;
																		
																		if($cat_items_id == $cat_items_id1)
																		{
																			if($item_format_id1 == $item_format_id)
																			{
																				$results = $this->nurse_model->cat_items2($cat_items_id, $format_id,$visit_id);
																				if($results->num_rows() > 0)
																				{
																					?><td > <input checked type="checkbox" value="" name="" onClick="del_medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $visit_id; ?>')"><?php echo '<strong>'.$format_name.'</strong>'; ?>  </td><?php 
																				} 
																			
																				else 
																				{ 
																				?><td > <input type="checkbox" value="" name="" onClick="medical_exam('<?php echo $cat_items_id; ?>','<?php echo $format_id ; ?>','<?php echo $visit_id; ?>')"><?php echo '<strong>'.$format_name.'</strong>'; ?></td><?php
																				}
																			}
																		}	
					                                                }
					                                            }
					                                            
					                                            else
					                                            {
					                                                echo 'There are no items';
					                                            }
					                                        }
					                                    }
					                                    
					                                    else
					                                    {
					                                        echo 'There are no category item results';
					                                    }
					                                }
					                            }
					                            
					                            else
					                            {
					                                echo 'There are no category items';
					                            }
												?>
												</table>
					                        </div>

					                     </div>
					                
					                </div>
					            </div>
					        </div>
					
					            <?php	
					  		} 
						}
					}
					?>
		<div class="row">
			<div class="col-md-12">
		        <div class="row">
		            <div class="col-md-12">

		              <!-- Widget -->
		              <div class="widget boxed">
		                    <!-- Widget head -->
		                      <div class="widget-head">
		                          <h5 class="pull-left" style="font-weight:bold"><?php echo $mec_name; ?></h5>
		                          <div class="widget-icons pull-right">
		                          </div>
		                          <div class="clearfix"></div>
		                        </div>            

		                <!-- Widget content -->
		                    <div class="widget-content">
		                        <div class="padd">
		                        	<?php echo $mec_result; ?>
		                        </div>

		                     </div>
		                
		                </div>
		            </div>
		        </div>
		    </div>
		   
		</div>
	</div>
        
        
    	
       
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-8 pull-left">
            <div class="col-md-4 pull-left">
            	Served by: <?php echo $served_by;?> 
            </div>
            <div class="col-md-4 pull-left">
              Signature by: ..................
            </div>
            <div class="col-md-4 pull-left">
              Patient Signature : ..............
            </div>
          </div>
        	<div class="col-md-4 pull-right">
            	<?php echo $today; ?>
            </div>
        </div>
    </body>
    
</html>

  <script type="text/javascript">
  	
	var config_url = '<?php echo site_url();?>';
		
	$(document).ready(function(){
	  	$.get( config_url+"/nurse/get_family_history/<?php echo $visit_id;?>", function( data ) {
			
			$("#checkup_history").html(data);
		});
	});
  </script>