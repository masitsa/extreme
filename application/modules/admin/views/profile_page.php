<?php
$contacts = $this->site_model->get_contacts();
$logo = $contacts['logo'];
?>
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
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Leave Balance</h2>
    </header>
    <div class="panel-body">

<div class="row">
	
    <?php 
	
	if($personnel_query->num_rows() > 0)
	{
		$row = $personnel_query->row();
		$gender_id = $row->gender_id;
	}
	
	else
	{
		$gender_id = 0;
	}
	
	if($leave_types->num_rows() > 0)
	{
		foreach($leave_types->result() as $res)
		{
			$leave_type_id = $res->leave_type_id;
			$leave_type_name = $res->leave_type_name;
			$leave_balance = $res->leave_days;
			
			if($leave->num_rows() > 0)
			{
				foreach($leave->result() as $row)
				{
					$leave_type_id2 = $row->leave_type_id;
					$leave_duration_status = $row->leave_duration_status;
					
					if(($leave_type_id == $leave_type_id2) && ($leave_duration_status == 1))
					{
						$leave_type_count = $row->leave_type_count;
						$start_date = date('jS M Y',strtotime($row->start_date));
						$end_date = date('jS M Y',strtotime($row->end_date));
						$days_taken = $this->site_model->calculate_leave_days($start_date, $end_date, $leave_type_count);
						$leave_balance -= $days_taken;
					}
				}
			}
			
			//maternity & femail
			if(($leave_type_id == 2) && ($gender_id == 2))
			{
				echo '
				<div class="col-md-3 col-lg-3 col-xl-3">
					<section class="panel panel-featured-left panel-featured-tertiary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-tertiary">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">'.$leave_type_name.' Leave</h4>
										<div class="info">
											<strong class="amount">'.$leave_balance.' days</strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="'.site_url().'hr/leave/leave_application/'.$leave_type_id.'">(apply)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				';
			}
			
			//paternity & male
			else if(($leave_type_id == 1) && ($gender_id == 1))
			{
				echo '
				<div class="col-md-3 col-lg-3 col-xl-3">
					<section class="panel panel-featured-left panel-featured-tertiary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-tertiary">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">'.$leave_type_name.' Leave</h4>
										<div class="info">
											<strong class="amount">'.$leave_balance.' days</strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="'.site_url().'hr/leave/leave_application/'.$leave_type_id.'">(apply)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				';
			}
			
			else if($leave_type_id > 2)
			{
				echo '
				<div class="col-md-3 col-lg-3 col-xl-3">
					<section class="panel panel-featured-left panel-featured-tertiary">
						<div class="panel-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-tertiary">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">'.$leave_type_name.' Leave</h4>
										<div class="info">
											<strong class="amount">'.$leave_balance.' days</strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="'.site_url().'hr/leave/leave_application/'.$leave_type_id.'">(apply)</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				';
			}
		}
	}
	?>
    
</div>
	</div>
</section>
<?php echo $this->load->view('accounts/payroll/individual_payroll', '', true);?>

<section class="panel">
	<header class="panel-heading">
    	<h2 class="panel-title">Profile Details</h2>
    </header>
	<div class="panel-body">
	<?php
			$success = $this->session->userdata('success_message');
			$error = $this->session->userdata('error_message');
			
			if(!empty($success))
			{
				echo '
					<div class="alert alert-success">'.$success.'</div>
				';
				
				$this->session->unset_userdata('success_message');
			}
			
			if(!empty($error))
			{
				echo '
					<div class="alert alert-danger">'.$error.'</div>
				';
				
				$this->session->unset_userdata('error_message');
			}
			
		?>
		<div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3">
                <img src="<?php echo base_url();?>assets/logo/<?php echo $logo;?>">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <h5><strong>Profile Details</strong></h5>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong> Personnel Name:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('first_name');?>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"> <strong>Username:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('username');?>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong>Branch Name: </strong></label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('branch_name');?>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong> Branch Code:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('branch_code');?>
                    </div>
                    
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-6">
                <h5><strong>Change Password</strong></h5>
                <form enctype="multipart/form-data" action="<?php echo base_url();?>change-password"  id = "change_password" method="post">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"> <strong>Current Password </strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="current_password" placeholder="Current Password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"><strong>New Password</strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="new_password" placeholder="New Password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"><strong>Confirm New Password</strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm new password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                    <div class="form-group center-align">
                                        <button type="submit" class="btn btn-sm btn-success" name="submit" >Change Password</button>
                                    </div>
                                
                            </div>
                                
                        </div>
                        
                    </div>
                </form>
            </div>
		</div>
	</div>
</section>

	