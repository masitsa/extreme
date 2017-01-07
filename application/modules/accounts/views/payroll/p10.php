<?php
$personnel_id = $this->session->userdata('personnel_id');
//$kra_pin = $contacts['branch_kra_pin'];

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
		}
		.row .col-md-12 th, .row .col-md-12 td {
			border:solid #000 !important;
			border-width:0 1px 1px 0 !important;
		}
		
		.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
		.title-img{float:left; padding-left:30px;}
		img.logo{max-height:70px; margin:0 auto;}
	</style>
    <head>
        <title>P10 Form</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
    </head>
    <body class="receipt_spacing">
        
            <div align="center">
            	<strong>
				<img src="<?php echo base_url();?>/assets/img/kra.jpg" alt="kra.jpg"><br> 
                <!--KENYA REVENUE AUTHORITY </br>-->				
                INCOME TAX DEPARTMENT </br>                
                </strong>
			</div>
			<div class="col">
				<div class="col-offset-6 col-md-6">
					<label>Employer's pin<br> <input type="text"></label>
				</div><br><br><br>
			</div>	
			<div align="center">
				<p style="font-size:130%"><b>P.A.Y.E - EMPLOYER'S CERTIFICATE </b></p>
				<p><b>YEAR __________________________</b></p>			
		    </div>			
            </br>
			<p><b>To Senior Assistant Commissioner</b></p>
			<p><b>.............................................................................</b></p>
            <p><b>We/I forward herewith.......................Tax Deduction Cards (P9A/P9B) showing the total tax deducted (as listed on P.10A) <br> amounting to Ksh.........................................</p><br>
			<p>This total tax has been paid as follows:-</p>
			<!--<div>
            	<div class="col-md-6">
                	<div class="form-group">
                    	<label class="col-md-5">Employer's Name:</label>
                        <div class="col-md-7">
                        ..............................................
                        </div>
                    </div>
                    <div>
                    	<div class="form-group">
                        	
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                	 <div class="form-group">
                    	<label class="col-md-5">Employer's Pin:</label>
                        <div class="col-md-7">
                        	<input type="text">
                        </div>
                    </div>
                    </br>
                     <div class="form-group">
                    	<label class="col-md-5">Employee's Pin:</label>
                        <div class="col-md-7">
                        	<input type="text">
                        </div>
                    </div>
                    </br>
                </div>
            </div>-->
        	<table class="table table-bordered table-striped table-condensed">
            	<thead>
                <strong>
                	<tr rowspan="2">
                    	<td>MONTH</td>
                        <td>PAYE TAX</br>  (KSHS.)</td>
                        <td>AUDIT TAX, </br> INTEREST/PENALTY</br> (KSHS.)</td>
                        <td>FRINGE </br> BENEFIT TAX</br> (KSHS.)</td>
                        <td align="center">DATE PAID PER </br> RECEIVING BANK</br> Kshs.</td>
                     </tr>
                     </strong>
                <tbody>
                <?php 
					$months = $this->payroll_model->get_months();
					$result = '';
					$total_paye = 0;
					if ($months ->num_rows()>0)
					{
						foreach($months->result() as $month)
						{
							
							$month_id = $month->month_id;
							$month_name = $month->month_name;
							
							if(($month_id >= $from_month_id) &&($month_id <= $to_month_id))
							{
								//select payroll id for that year & month
								$active_branch = $this->session->userdata('branch_id');
								$this->db->where(array('payroll_year' => $year, 'month_id' => $month_id, 'payroll_status' => 1, 'branch_id' => $active_branch));
								$payroll_data = $this->db->get('payroll');
								
								if($payroll_data->num_rows() > 0)
								{
									$row = $payroll_data->row();
									$payroll_id = $row->payroll_id;
									
									//paye
									$total_paye = 0;
									$table = $this->payroll_model->get_table_id("paye");
									$paye =$this->payroll_model->get_p10_payroll_amount($payroll_id, $table, 1);
									
									//relief
									$table = $this->payroll_model->get_table_id("relief");
									$relief = $this->payroll_model->get_p10_payroll_amount($payroll_id, $table, 1);
									
									//insurance_relief
									$table = $this->payroll_model->get_table_id("insurance_relief");
									$insurance_relief = $this->payroll_model->get_p10_payroll_amount($payroll_id, $table, 1);
									
									$paye -= ($relief + $insurance_relief);
													
									if($paye < 0)
									{
										$paye = 0;
									}
									$total_paye += $paye;
								}
							
							
							$result .='
								<tr>
									<td>'.$month_name.'</td>
									<td>'.number_format($total_paye,2).'</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>';
						}
					}
						echo $result;
					}
				?>
               	</tbody>
			</table>
			<!--footer -->
			<footer>
				<div>
					<div>						
						<h1 style="font-size:100%;"><b>NOTE:-</b></h1>
							<ol type="1","4", "5">
								<li>Attach Photostat copies of ALL the Pay-In Credit Slips (P11s) for the year </li>
								<li>Complete  this certificate in triplicate sending the top two copies with the enclosures to your<u><b> Income<br>Tax Office not later than 28TH FEBRUARY</b></u></li>
								<li>Provide Statistical information required by Central Bureau of Statistics</li>
							</ol>
						<p>We/I certify that the particulars entered above are correct. </p>	
					</div>
					<div>						
							<div class="form-group">
								<label>NAME OF EMPLOYER:									
									.....................................................................................................................................................
								</label>
							</div>
							<div class="form-group">
								<label>ADDRESS:
								..........................................................................................................................................................................
								</label>
							</div>
							<div class="form-group">
								<label>SIGNATURE:						
									......................................................................................................................................................................
								</label>
							</div>
							<div class="form-group">
								<label>DATE:</label>								
									.................................................................................................................................................................................
								</label>
							</div>						
					</div>	
			</footer>		
		</div>
	</body>
</html>
