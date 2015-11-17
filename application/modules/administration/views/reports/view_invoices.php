<?php

$row = $query->row();
$invoice_date = date('jS M Y H:i a',strtotime($row->debtor_invoice_created));
$debtor_invoice_id = $row->debtor_invoice_id;
$visit_type_name = $row->visit_type_name;
$visit_type_id = $row->visit_type_id;
//$patient_insurance_number = $row->patient_insurance_number;
$batch_no = $row->batch_no;
$status = $row->debtor_invoice_status;
$personnel_id = $row->debtor_invoice_created_by;
$date_from = date('jS M Y',strtotime($row->date_from));
$date_to = date('jS M Y',strtotime($row->date_to));
$total_invoiced = number_format($this->reports_model->calculate_debt_total($debtor_invoice_id, $where, $table), 2);

//creators and editors
if($personnel_query->num_rows() > 0)
{
	$personnel_result = $personnel_query->result();
	
	foreach($personnel_result as $adm)
	{
		$personnel_id2 = $adm->personnel_id;
		
		if($personnel_id == $personnel_id2)
		{
			$created_by = $adm->personnel_onames.' '.$adm->personnel_fname;
			break;
		}
		
		else
		{
			$created_by = '-';
		}
	}
}

else
{
	$created_by = '-';
}
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured">
            <header class="panel-heading">
            	<h2 class="panel-title">Debtor's reports for <?php echo $visit_type_name;?></h2>
            </header>             
        
            <!-- Widget content -->
            <div class="panel-body">
            	
                <div class="row">
                    <div class="col-md-12"> 
                        <h5 class="center-align">Invoice for services rendered between <?php echo $date_from;?> and <?php echo $date_to;?> as per the attached invoices</h5>
                    </div>
                </div>
            	
                <div class="row">
                    <div class="col-md-12"> 
                        <a href="<?php echo site_url('accounts/insurance-invoices/'.$visit_type_id)?>" class="btn btn-sm btn-info pull-right">Back to debtors</a>
                        <a href="<?php echo site_url('administration/reports/invoice/'.$debtor_invoice_id)?>" class="btn btn-sm btn-success pull-right" target="_blank" style="margin-right:10px;">Print</a>
                    </div>
                </div>
            	
                <div class="row">
                    <div class="col-md-12"> 
                        <table class="table table-hover table-bordered col-md-12">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Date</th>
                                    <th>Member Number</th>
                                    <th>Patient</th>
                                    <th>Invoice Number</th>
                                    <th>Total Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            
                            <tbody> 
                                <?php
                                $total_amount = 0;
                                if($debtor_invoice_items->num_rows() > 0)
                                {
                                    $count = 0;
                                    foreach($debtor_invoice_items->result() as $res)
                                    {
                                        $count++;
                                        $invoice_amount = $res->invoice_amount;
                                        $patient_surname = $res->patient_surname;
                                        $patient_othernames = $res->patient_othernames;
                                        $patient_number = $res->patient_number;
                                        $patient_insurance_number = $res->patient_insurance_number;
                                        $current_patient_number = $res->current_patient_number;
										$debtor_invoice_item_status = $res->debtor_invoice_item_status;
										$debtor_invoice_item_id = $res->debtor_invoice_item_id;
                                        $visit_id = $res->visit_id;
                                        $visit_date = date('jS F Y',strtotime($res->visit_date));
                                        $total_amount += $invoice_amount;
										
										if($debtor_invoice_item_status == 1)
										{
											$buttons = '<a href="'.site_url().'administration/reports/activate_debtor_invoice_item/'.$debtor_invoice_item_id.'/'.$debtor_invoice_id.'" class="btn btn-sm btn-success"onclick="return confirm(\'Do you want to activate invoice '.$patient_insurance_number.'?\');">Activate</a>';
										}
										else if($debtor_invoice_item_status == 0)
										{
											$buttons = '<a href="'.site_url().'administration/reports/deactivate_debtor_invoice_item/'.$debtor_invoice_item_id.'/'.$debtor_invoice_id.'" class="btn btn-sm btn-danger"onclick="return confirm(\'Do you want to deactivate invoice '.$patient_insurance_number.'?\');">Deactivate</a>';
										}
										else
										{
											$buttons = '';
										}
                                        ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td><?php echo $visit_date;?></td>
                                            <td><?php echo $patient_insurance_number;?></td>
                                            <td><?php echo $patient_surname;?> <?php echo $patient_othernames;?></td>
                                            <td><?php echo $this->session->userdata('branch_code').'-INV-00'.$visit_id; ?></td>
                                            <td><?php echo number_format($invoice_amount, 2);?></td>
                                            <td><?php echo $buttons;?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr>
                                    <th colspan="5" align="right">Total</th>
                                    <th><?php echo number_format($total_amount, 2);?></th>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            <div class="widget-foot">
                    
				<?php if(isset($links)){echo $links;}?>
            
        	</div>
        </section>
        <!-- Widget ends -->
    
    </div>
</div>