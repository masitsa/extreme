<?php
	
	if($individual_savings->num_rows() > 0)
	{
		$count = 0;
			
		$result = 
		'
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Plan</a></th>
					<th>Opening balance</a></th>
					<th>Start date</a></th>
					<th>Last editted</a></th>
					<th>Status</a></th>
					<th colspan="5">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($individual_savings->result() as $row)
		{
			$individual_savings_id = $row->individual_savings_id;
			$savings_plan_name = $row->savings_plan_name;
			$individual_savings_opening_balance = number_format($row->individual_savings_opening_balance);
			$individual_savings_status = $row->individual_savings_status;
			$start_date = date('jS M Y',strtotime($row->start_date));
			$individual_savings_status = $row->individual_savings_status;
			$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
			
			//create deactivated status display
			if($individual_savings_status == 0)
			{
				$status = '<span class="label label-default">Deactivated</span>';
				$button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-individual-plan/'.$individual_savings_id.'/'.$individual_id.'" onclick="return confirm(\'Do you want to activate '.$savings_plan_name.'?\');" title="Activate '.$savings_plan_name.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($individual_savings_status == 1)
			{
				$status = '<span class="label label-success">Active</span>';
				$button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-individual-plan/'.$individual_savings_id.'/'.$individual_id.'" onclick="return confirm(\'Do you want to deactivate '.$savings_plan_name.'?\');" title="Deactivate '.$savings_plan_name.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$savings_plan_name.'</td>
					<td>'.$individual_savings_opening_balance.'</td>
					<td>'.$start_date.'</td>
					<td>'.$last_modified.'</td>
					<td>'.$status.'</td>
					<td><a href="'.site_url().'microfinance/edit-individual-plan/'.$individual_savings_id.'/'.$individual_id.'" class="btn btn-sm btn-success" title="Edit '.$savings_plan_name.'"><i class="fa fa-pencil"></i></a></td>
					<td>'.$button.'</td>
					<td><a href="'.site_url().'microfinance/delete-individual-plan/'.$individual_savings_id.'/'.$individual_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$savings_plan_name.'?\');" title="Delete '.$savings_plan_name.'"><i class="fa fa-trash"></i></a></td>
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
		$result = "<p>No plans have been added</p>";
	}
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$savings_plan_id = set_value('savings_plan_id');
	$individual_savings_status = set_value('individual_savings_status');
	$individual_savings_opening_balance = set_value('individual_savings_opening_balance');
	$start_date = set_value('start_date');
}

else
{
	$savings_plan_id = '';
	$individual_savings_status = '';
	$individual_savings_opening_balance = 0;
	$start_date = '';
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Savings details</h2>
                </header>
                <div class="panel-body">
                <!-- Adding Errors -->
            
            
            <?php echo form_open('microfinance/add-individual-plan/'.$individual_id, array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-5 control-label">Savings plan: </label>
            
            <div class="col-lg-7">
            	<select class="form-control" name="savings_plan_id">
                	<?php
                    	if($savings_plans->num_rows() > 0)
						{
							$savings_plan = $savings_plans->result();
							
							foreach($savings_plan as $res)
							{
								$db_savings_plan_id = $res->savings_plan_id;
								$savings_plan_name = $res->savings_plan_name;
								
								if($db_savings_plan_id == $savings_plan_id)
								{
									echo '<option value="'.$db_savings_plan_id.'" selected>'.$savings_plan_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_savings_plan_id.'">'.$savings_plan_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Opening balance: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_savings_opening_balance" placeholder="Opening balance" value="<?php echo $individual_savings_opening_balance;?>">
            </div>
        </div>
        
        
    </div>
     
	<div class="col-md-6">
    	<div class="form-group">
            <label class="col-lg-5 control-label">Start date: </label>
            
            <div class="col-lg-7">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start date" value="<?php echo $start_date;?>">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Activate plan: </label>
            
            <div class="col-lg-7">
            	<?php
                	if($individual_savings_status == 1)
					{
						?>
                        <div class="radio">
                            <label>
                                <input type="radio" checked="checked" value="1" id="individual_savings_status" name="individual_savings_status">
                                Active
                            </label>
                        </div>
                        
                        <div class="radio">
                            <label>
                                <input type="radio" checked="" value="0" id="individual_savings_status" name="individual_savings_status">
                                Disabled
                            </label>
                        </div>
                        <?php
					}
					
					else
					{
						?>
                        <div class="radio">
                            <label>
                                <input type="radio" checked="" value="1" id="individual_savings_status" name="individual_savings_status">
                                Active
                            </label>
                        </div>
                        
                        <div class="radio">
                            <label>
                                <input type="radio" checked="checked" value="0" id="individual_savings_status" name="individual_savings_status">
                                Disabled
                            </label>
                        </div>
                        <?php
					}
				?>
            </div>
        </div>
        
	</div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="btn btn-primary" type="submit">
               	Add plan
            </button>
        </div>
    </div>
</div>
            <?php 
				echo form_close();
			?>
			
            <h4>Added plans</h4>
            <?php 
				echo $result;
			?>
            
                    
                </div>
            </section>