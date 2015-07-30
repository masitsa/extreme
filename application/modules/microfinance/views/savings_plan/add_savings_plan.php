<?php
//savings_plan data
$savings_plan_name = set_value('savings_plan_name');
$savings_plan_min_opening_balance = set_value('savings_plan_min_opening_balance');
$savings_plan_min_account_balance = set_value('savings_plan_min_account_balance');
$charge_withdrawal = set_value('charge_withdrawal');
$compounding_period_name = set_value('compounding_period_name');
?>          
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>microfinance/savings_plan" class="btn btn-info pull-right">Back to savings_plan</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-5 control-label">Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="savings_plan_name" placeholder="Name" value="<?php echo $savings_plan_name;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Mininum opening balance: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="savings_plan_min_opening_balance" placeholder="Mininum opening balance" value="<?php echo $savings_plan_min_opening_balance;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Mininum account balance: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="savings_plan_min_account_balance" placeholder="Mininum account balance" value="<?php echo $savings_plan_min_account_balance;?>">
            </div>
        </div>
        
        
	</div>
    
    <div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Charge withdrawal: </label>
            
            <div class="col-lg-7">
            	<div class="radio">
                    <label>
                        <input type="radio" checked="checked" value="1" id="charge_withdrawal" name="charge_withdrawal">
                        Yes
                    </label>
                </div>
                
                <div class="radio">
                    <label>
                        <input type="radio" checked="" value="0" id="charge_withdrawal" name="charge_withdrawal">
                        No
                    </label>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Interest compounding period: </label>
            
            <div class="col-lg-7">
                <select class="form-control" name="compounding_period_id">
                	<?php
                    	if($compounding_period->num_rows() > 0)
						{
							foreach($compounding_period->result() as $res)
							{
								$db_compounding_period_id = $res->compounding_period_id;
								$compounding_period_name = $res->compounding_period_name;
								
								if($db_compounding_period_id == $compounding_period_id)
								{
									echo '<option value="'.$db_compounding_period_id.'" selected>'.$compounding_period_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_compounding_period_id.'">'.$compounding_period_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Add savings plan
            </button>
        </div>
    </div>
</div>
                    <?php echo form_close();?>
                </div>
            </section>