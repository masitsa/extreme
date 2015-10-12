<?php
$personnel_type_id = $this->session->userdata('personnel_type_id');
		
$result = '';

//if users exist display them
if ($query->num_rows() > 0)
{
	$count = $page;
	
	$result .= 
	'
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th><a href="'.site_url().'doctor/dashboard/schedule_date/'.$order_method.'/'.$page.'">Date</a></th>
				<th><a href="'.site_url().'doctor/dashboard/schedule_start_time/'.$order_method.'/'.$page.'">Start time</a></th>
				<th><a href="'.site_url().'doctor/dashboard/schedule_end_time/'.$order_method.'/'.$page.'">End time</a></th>
				<th>Units</th>
				<th>Unit cost</th>
				<th>Total</th>
				<th><a href="'.site_url().'doctor/dashboard/created/'.$order_method.'/'.$page.'">Created</a></th>
				<th><a href="'.site_url().'doctor/dashboard/created_by/'.$order_method.'/'.$page.'">Created by</a></th>
				<th colspan="5">Actions</th>
			</tr>
		</thead>
		  <tbody>
		  
	';
	
	//get all administrators
	$administrators = $this->users_model->get_active_users();
	if ($administrators->num_rows() > 0)
	{
		$admins = $administrators->result();
	}
	
	else
	{
		$admins = NULL;
	}
	
	foreach ($query->result() as $row)
	{
		$personnel_id = $row->personnel_id;
		$schedule_item_id = $row->schedule_item_id;
		$schedule_date = $row->schedule_date;
		$schedule_start_time = $row->schedule_start_time;
		$schedule_end_time = $row->schedule_end_time;
		$created_by = $row->created_by;
		$created = $row->created;
		
		//consultant
		if($personnel_type_id == 2)
		{
			$schedule_total = $this->reports_model->get_total_collected($personnel_id, $schedule_date, $schedule_date);
			$units = 1;
			$unit_cost = $schedule_total;
		}
		
		//radiographer
		elseif($personnel_type_id == 3)
		{
			$schedule_total = $this->reports_model->get_total_collected($personnel_id, $schedule_date, $schedule_date);
			$units = 0.3;
			$unit_cost = $schedule_total;
		}
		
		//medical officer
		elseif($personnel_type_id == 4)
		{
			$units = (strtotime($schedule_end_time) - strtotime($schedule_start_time)) / 3600;
			$unit_cost = 500;
		}
		
		//clinic officer
		elseif($personnel_type_id == 5)
		{
			$units = 1;
			$unit_cost = 1000;
		}
		
		$total = $units * $unit_cost;
		
		//creators & editors
		if($admins != NULL)
		{
			foreach($admins as $adm)
			{
				$user_id = $adm->personnel_id;
				
				if($user_id == $created_by)
				{
					$created_by = $adm->personnel_fname;
				}
			}
		}
		
		else
		{
		}
		$count++;
		$result .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td>'.date('jS M Y',strtotime($schedule_date)).'</td>
				<td>'.date('h:i a',strtotime($schedule_start_time)).'</td>
				<td>'.date('h:i a',strtotime($schedule_end_time)).'</td>
				<td>'.$units.'</td>
				<td>'.$unit_cost.'</td>
				<td>'.number_format($total, 2).'</td>
				<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
				<td>'.$created_by.'</td>
				<td><a href="'.site_url().'doctor/delete_time/'.$schedule_item_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this entry?\');" title="Delete entry"><i class="fa fa-trash"></i></a></td>
			</tr> 
		';
	}
	
	$result .= 
	'
				  </tbody>
				</table>
	';
}

else
{
	$result .= "There are no hours";
}

//invoices
$result_invoices = '';
$count = 0;

//if users exist display them
if ($invoices->num_rows() > 0)
{
	$count = $page;
	
	$result_invoices .= 
	'
	<table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th>#</th>
				<th>Invoice number</th>
				<th>Description</th>
				<th>Total</th>
				<th>Created</th>
				<th>Created by</th>
				<th colspan="5">Actions</th>
			</tr>
		</thead>
		 
		<tbody>
		  
	';
	
	//get all administrators
	$administrators = $this->users_model->get_active_users();
	if ($administrators->num_rows() > 0)
	{
		$admins = $administrators->result();
	}
	
	else
	{
		$admins = NULL;
	}
	
	foreach ($invoices->result() as $row)
	{
		$doctor_invoice_id = $row->doctor_invoice_id;
		$invoice_date = $row->created;
		$created_by = $row->created_by;
		$personnel_id = $row->personnel_id;
		$doctor_invoice_number = $row->doctor_invoice_number;
		$doctor_invoice_description = $row->doctor_invoice_description;
		$total = $this->doctor_model->get_invoice_total($doctor_invoice_id);
		
		//creators & editors
		if($admins != NULL)
		{
			foreach($admins as $adm)
			{
				$user_id = $adm->personnel_id;
				
				if($user_id == $created_by)
				{
					$created_by = $adm->personnel_fname;
				}
			}
		}
		
		else
		{
		}
		$count++;
		$result_invoices .= 
		'
			<tr>
				<td>'.$count.'</td>
				<td>'.$doctor_invoice_number.'</td>
				<td>'.$doctor_invoice_description.'</td>
				<td>'.$doctor_invoice_number.'</td>
				<td>'.$unit_cost.'</td>
				<td>'.number_format($total, 2).'</td>
				<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
				<td>'.$created_by.'</td>
				<td><a href="'.site_url().'doctor/print_invoice/'.$doctor_invoice_id.'" class="btn btn-sm btn-warning" title="Print invoice" target="_blank"><i class="fa fa-print"></i> Print</a></td>
			</tr> 
		';
	}
	
	$result_invoices .= 
	'
				  </tbody>
				</table>
	';
}

else
{
	$result_invoices .= "There are no invoices";
}

if(!empty($personnel_type_id) && ($personnel_type_id != 1))
{
?>

    <section class="panel panel-default">
        <header class="panel-heading">
            <h2 class="panel-title">Total summary</h2>
        </header>
        <div class="panel-body">
            <div class="alert alert-info center-align">In order to create an invoice, filter your timesheets by date in this section then create an invoice</div>
            <div class="row">
            	<div class="col-md-4">
                	<?php echo form_open('doctor/filter_timesheets', array(''));?>
                    <div class="row">
                        <div class="col-md-12" style="margin-bottom:15px;">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Date from: </label>
                                
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date_from" placeholder="Date" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12" style="margin-bottom:15px;">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Date to: </label>
                                
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date_to" placeholder="Date" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8 col-md-offset-4">
                        	<div class="center-align">
                            	<?php 
								
								$timesheet_search = $this->session->userdata('timesheet_search');
								
								if(!empty($timesheet_search))
								{
									?>
                                    <a href="<?php echo site_url().'doctor/close_timesheet_search';?>" class="btn btn-success">Close filter</a>
                                    <?php
								}
								
								?>
                            	<button type="submit" class="btn btn-info">Filter</button>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close();?>
                </div>
                
                <div class="col-md-4">
                	<section class="panel panel-featured-left panel-featured-primary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-primary">
                                        <i class="fa fa-life-ring"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Patients seen</h4>
                                        <div class="info">
                                            <strong class="amount"><?php echo number_format($patients_seen, 0);?></strong>
                                        </div>
                                    </div>
                                    <div class="summary-footer">
                                        <a href="<?php echo site_url('doctor/visit-history');?>" class="text-muted text-uppercase">(view all)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
                <div class="col-md-4">
                	<section class="panel panel-featured-left panel-featured-secondary">
                        <div class="panel-body">
                            <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                    <div class="summary-icon bg-secondary">
                                        <i class="fa fa-usd"></i>
                                    </div>
                                </div>
                                <div class="widget-summary-col">
                                    <div class="summary">
                                        <h4 class="title">Total due</h4>
                                        <div class="info">
                                            <strong class="amount">Kes <?php echo number_format($total_due, 2);?></strong>
                                        </div>
                                    </div>
                                    <div class="summary-footer">
                                        <a href="<?php echo site_url('doctor/create_invoice');?>" class="text-muted text-uppercase" target="_blank">(Create invoice)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            
        </div>
    </section>
    
<section class="panel">
	<header class="panel-heading">
    	<h2 class="panel-title">Timesheet</h2>
    </header>
	<div class="panel-body">
    	<div class="alert alert-info center-align">
        	Please fill in your timesheet in order to complete your monthly invoices online
        </div>
        
        <?php echo form_open('human-resource/fill-timesheet/3/'.$this->session->userdata('personnel_id'), array(''));?>
        	<div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Date : </label>
                    
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date" placeholder="Date" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Start time : </label>
                    
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                                <input type="text" class="form-control" data-plugin-timepicker="" name="start_time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-12 control-label">End time : </label>
                        
                        <div class="col-md-12">		
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </span>
                                <input type="text" class="form-control" data-plugin-timepicker="" name="end_time">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" style="margin-top:10px;">
                <div class="col-md-3 col-md-offset-5">
                    <button type="submit" class="btn btn-primary">Add hours</button>
                </div>
            </div>
        <?php echo form_close();?>
    </div>
</section>
	
    <?php
	$success = $this->session->userdata('success_message');
		
	if(!empty($success))
	{
		echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
		$this->session->unset_userdata('success_message');
	}
	
	$error = $this->session->userdata('error_message');
	
	if(!empty($error))
	{
		echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
		$this->session->unset_userdata('error_message');
	}
	?>

    <section class="panel panel-default">
        <header class="panel-heading">
            <h2 class="panel-title">Invoices</h2>
        </header>
        <div class="panel-body">
            <div class="table-responsive">
                
                <?php echo $result_invoices;?>
        
            </div>
        </div>
    </section>

    <section class="panel panel-default">
        <header class="panel-heading">
            <h2 class="panel-title">Total due</h2>
        </header>
        <div class="panel-body">
            <h4 class="center-align" style="margin-bottom:10px;"><?php echo $title;?></h4>
            <div class="table-responsive">
                
                <?php echo $result;?>
        
            </div>
        </div>
        <div class="panel-footer">
            
            <?php if(isset($links)){echo $links;}?>
        </div>
    </section>
<?php }

$this->load->view('admin/profile_page');
?>

