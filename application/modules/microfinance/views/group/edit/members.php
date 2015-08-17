<?php
//group data
$row = $group->row();

$group_id = $row->group_id;

	if($group_members->num_rows() > 0)
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
					<th>Phone</a></th>
					<th>Last editted</a></th>
					<th>Status</a></th>
					<th colspan="3">Actions</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		
		foreach ($group_members->result() as $row)
		{
			$individual_id = $row->individual_id;
			$individual_onames = $row->individual_onames;
			$individual_fname = $row->individual_fname;
			$individual_dob = $row->individual_dob;
			$individual_email = $row->individual_email;
			$individual_phone = $row->individual_phone;
			$individual_address = $row->individual_address;
			$civil_status_id = $row->civilstatus_id;
			$individual_locality = $row->individual_locality;
			$title_id = $row->title_id;
			$gender_id = $row->gender_id;
			$individual_city = $row->individual_city;
			$individual_number = $row->individual_number;
			$individual_post_code = $row->individual_post_code;
			$individual_status = $row->individual_status;
			$created = date('jS M Y H:i a',strtotime($row->created));
			$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
			
			//create deactivated status display
			if($individual_status == 0)
			{
				$status = '<span class="label label-default">Deactivated</span>';
				$button = '<a class="btn btn-info" href="'.site_url().'human-resource/activate-position/'.$individual_id.'" onclick="return confirm(\'Do you want to activate '.$individual_fname.'?\');" title="Activate '.$individual_fname.'"><i class="fa fa-thumbs-up"></i></a>';
			}
			//create activated status display
			else if($individual_status == 1)
			{
				$status = '<span class="label label-success">Active</span>';
				$button = '<a class="btn btn-default" href="'.site_url().'human-resource/deactivate-position/'.$individual_id.'" onclick="return confirm(\'Do you want to deactivate '.$individual_fname.'?\');" title="Deactivate '.$individual_fname.'"><i class="fa fa-thumbs-down"></i></a>';
			}
			
			$count++;
			$result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$individual_fname.'</td>
					<td>'.$individual_onames.'</td>
					<td>'.$individual_phone.'</td>
					<td>'.$last_modified.'</td>
					<td>'.$status.'</td>
					<td><a href="'.site_url().'microfinance/edit-individual/'.$individual_id.'" class="btn btn-sm btn-success" title="Edit '.$individual_fname.'"><i class="fa fa-pencil"></i></a></td>
					<td>'.$button.'</td>
					<td><a href="'.site_url().'human-resource/delete-group/'.$individual_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$individual_fname.'?\');" title="Delete '.$individual_fname.'"><i class="fa fa-trash"></i></a></td>
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
		$result = "<p>No members have been added</p>";
	}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Group's members</h2>
                </header>
                <div class="panel-body">
            		<?php echo $result;?>
                </div>
            </section>