<?php
//repopulate data if validation errors occur
$validation_errors = validation_errors();
				
if(!empty($validation_errors))
{
	$old_personnel_id = set_value('personnel_id');
	$start_date = set_value('start_date');
	$end_date = set_value('end_date');
	$old_leave_type_id = set_value('leave_type_id');
}

else
{
	$old_personnel_id = '';
	$start_date = '';
	$end_date = '';
	$old_leave_type_id = '';
}
?>      	
        <div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-12">
		                    <h2 class="panel-title"><?php echo $title;?></h2>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                    
                    <div class="row">
                    	<div class="col-md-12">
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
                            <?php
							if(!empty($validation_errors))
							{
								echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
							}
							
							?>
                        	<?php echo form_open("human-resource/add-calender-leave");?>
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control" name="personnel_id">
                                        <option value="">--Select personnel--</option>
                                        <?php echo $personnel;?>
                                        <?php
                                            if($personnel->num_rows() > 0)
                                            {
                                                foreach($personnel->result() as $res)
                                                {
                                                    $personnel_id = $res->personnel_id;
                                                    $personnel_fname = $res->personnel_fname;
                                                    $personnel_onames = $res->personnel_onames;
                                                    
                                                    if($old_personnel_id == $personnel_id)
                                                    {
                                                        echo "<option value='".$personnel_id."' selected>".$personnel_fname." ".$personnel_onames."</option>";
                                                    }
                                                    
                                                    else
                                                    {
                                                        echo "<option value='".$personnel_id."'>".$personnel_fname." ".$personnel_onames."</option>";
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start date" value="<?php echo $start_date;?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End date" value="<?php echo $end_date;?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="leave_type_id">
                                        <option value="">--Select leave type--</option>
                                        <?php
                                            if($leave_types->num_rows() > 0)
                                            {
                                                foreach($leave_types->result() as $res)
                                                {
                                                    $leave_type_id = $res->leave_type_id;
                                                    $leave_type_name = $res->leave_type_name;
                                                    
                                                    if($old_leave_type_id == $leave_type_id)
                                                    {
                                                        echo '<option value="'.$leave_type_id.'" selected>'.$leave_type_name.'</option>';
                                                    }
                                                    
                                                    else
                                                    {
                                                        echo '<option value="'.$leave_type_id.'" >'.$leave_type_name.'</option>';
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-top:10px;">
                                <div class="col-md-3 col-md-offset-5">
                                    <button type="submit" class="btn btn-primary">Add leave</button>
                                </div>
                            </div>
                            <?php echo form_close();?> 
                             <?php echo $this->calendar->generate($year, $month, $data);?>
                        </div>
                    </div>
                </div>
            </section>
        </div>