<?php

	if($emergency_contacts->num_rows() > 0)
	{
		$count = 0;
			
		$result = 
		'
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>First name</a></th>
					<th>Other names</a></th>
					<th>Relationship</a></th>
					<th>Phone</a></th>
					<th>Last editted</a></th>
					<th colspan="1">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($emergency_contacts->result() as $row)
		{
			$individual_emergency_id = $row->individual_emergency_id;
			$individual_emergency_fname = $row->individual_emergency_fname;
			$individual_emergency_onames = $row->individual_emergency_onames;
			$individual_emergency_phone = $row->individual_emergency_phone;
			$relationship_name = $row->relationship_name;
			$individual_emergency_status = $row->individual_emergency_status;
			$created = date('jS M Y H:i a',strtotime($row->created));
			$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
			
			//create deactivated status display
			if($individual_emergency_status == 0)
			{
				$status = '<span class="label label-important">Deactivated</span>';
				$button = '<a class="btn btn-info" href="'.site_url().'human-resource/activate-position/'.$individual_emergency_id.'" onclick="return confirm(\'Do you want to activate '.$individual_emergency_fname.'?\');" title="Activate '.$individual_emergency_fname.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($individual_emergency_status == 1)
			{
				$status = '<span class="label label-success">Active</span>';
				$button = '<a class="btn btn-default" href="'.site_url().'human-resource/deactivate-position/'.$individual_emergency_id.'" onclick="return confirm(\'Do you want to deactivate '.$individual_emergency_fname.'?\');" title="Deactivate '.$individual_emergency_fname.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$individual_emergency_fname.'</td>
					<td>'.$individual_emergency_onames.'</td>
					<td>'.$relationship_name.'</td>
					<td>'.$individual_emergency_phone.'</td>
					<td>'.$last_modified.'</td>
					<td><a href="'.site_url().'microfinance/delete-emergency/'.$individual_emergency_id.'/'.$individual_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$individual_emergency_fname.'?\');" title="Delete '.$individual_emergency_fname.'"><i class="fa fa-trash"></i></a></td>
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
		$result = "<p>No next of kin have been added</p>";
	}
	

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$relationship_id = set_value('relationship_id');
	$individual_emergency_fname = set_value('individual_emergency_fname');
	$individual_emergency_onames = set_value('individual_emergency_onames');
	$individual_emergency_phone = set_value('individual_emergency_phone');
	$individual_emergency_phone2 = set_value('individual_emergency_phone2');
	$individual_emergency_email = set_value('individual_emergency_email');
	$individual_emergency_email2 = set_value('individual_emergency_email2');
	$document_id = set_value('document_id');
	$document_number = set_value('document_number');
	$document_place = set_value('document_place');
	$individual_emergency_address = set_value('individual_emergency_address');
	$individual_emergency_city = set_value('individual_emergency_city');
	$individual_emergency_post_code = set_value('individual_emergency_post_code');
	$individual_emergency_dob = set_value('individual_emergency_dob');
}

else
{
	$relationship_id = '';
	$individual_emergency_fname = '';
	$individual_emergency_onames = '';
	$individual_emergency_phone = '';
	$individual_emergency_phone2 = '';
	$individual_emergency_email = '';
	$individual_emergency_email2 = '';
	$document_id = '';
	$document_number = '';
	$document_place = '';
	$individual_emergency_address = '';
	$individual_emergency_city = '';
	$individual_emergency_dob = '';
	$individual_emergency_post_code = '';
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Individual's next of kin list</h2>
                </header>
                <div class="panel-body">
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open('microfinance/add-nok/'.$individual_id, array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-4">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">First Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_fname" placeholder="First Name" value="<?php echo $individual_emergency_fname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Other Names: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_onames" placeholder="Other Names" value="<?php echo $individual_emergency_onames;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Date of Birth: </label>
            
            <div class="col-lg-7">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="individual_emergency_dob" placeholder="Date of Birth" value="<?php echo $individual_emergency_dob;?>">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Relationship : </label>
            
            <div class="col-lg-7">
            	<select class="form-control" name="relationship_id">
                	<?php
                    	if($relationships->num_rows() > 0)
						{
							$relationship = $relationships->result();
							
							foreach($relationship as $res)
							{
								$db_relationship_id = $res->relationship_id;
								$relationship_name = $res->relationship_name;
								
								if($db_relationship_id == $relationship_id)
								{
									echo '<option value="'.$db_relationship_id.'" selected>'.$relationship_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_relationship_id.'">'.$relationship_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_email" placeholder="Email Address" value="<?php echo $individual_emergency_email;?>">
            </div>
        </div>
    </div>
    
	<div class="col-md-4">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_email2" placeholder="Email Address 2" value="<?php echo $individual_emergency_email2;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_phone" placeholder="Phone" value="<?php echo $individual_emergency_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_phone2" placeholder="Phone 2" value="<?php echo $individual_emergency_phone2;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_address" placeholder="Address" value="<?php echo $individual_emergency_address;?>">
            </div>
        </div>
    </div>
    
	<div class="col-md-4">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">City: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_city" placeholder="City" value="<?php echo $individual_emergency_city;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Post code: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_emergency_post_code" placeholder="Post code" value="<?php echo $individual_emergency_post_code;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Document type: </label>
            
            <?php
            	if($document_id == 2)
				{
			?>
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="1" id="document_id1">
                        National ID
                    </label>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="2" checked="checked" id="document_id2">
                        Passport
                    </label>
                </div>
            </div>
            <?php } 
			
            	else
				{
			?>
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="1" checked="checked" id="document_id1">
                        National ID
                    </label>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="2" id="document_id2">
                        Passport
                    </label>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Document number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="document_number" placeholder="Document number" value="<?php echo $document_number;?>">
            </div>
        </div>
        
	</div>
</div>
<div class="row" style="margin:10px 0 10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
        	
            <button class="btn btn-primary" type="submit">
                Add next of kin
            </button>
        </div>
    </div>
</div>
            <?php echo form_close();?>
            <?php echo $result;?>
                </div>
            </section>