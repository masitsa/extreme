<?php echo $this->load->view('search/service_charge_search', '', TRUE);?>

<div class="row">
	<div class="col-md-12">
    	<div class="pull-left">
        	<?php
			$search = $this->session->userdata('service_charge_search');
			
			if(!empty($search))
			{
				echo '<a href="'.site_url().'hospital_administration/services/close_service_charge_search/'.$service_id.'" class="btn btn-warning btn-sm"><i class="fa fa-times"></i> Close search</a>';
			}
			?>
        </div>
        
		<div class="pull-right">
		 <a href="<?php echo site_url()?>hospital-administration/services" class="btn btn-sm btn-primary"><i class="fa fa-angle-left"></i> Back to services </a>
		 <a href="<?php echo site_url()?>hospital-administration/add-service-charge/<?php echo $service_id;?>" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add <?php echo strtolower($service_name);?> charge </a>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">

 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
              </header>             

          <!-- Widget content -->
                <div class="panel-body">
          
<?php
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		
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
		$result = '';
		
		//if users exist display them
		//echo $query->num_rows();
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Patient type</th>
						  <th>Charge name</th>
						  <th>Amount</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			//get all administrators
			$administrators = $this->personnel_model->retrieve_personnel();
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
				$service_charge_id = $row->service_charge_id;
				$service_charge_name = $row->service_charge_name;
				$service_charge_status = $row->service_charge_status;
				$visit_type_name = $row->visit_type_name;
				$service_charge_amount = $row->service_charge_amount;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
				$created = date('jS M Y H:i a',strtotime($row->created));
				
				//create deactivated status display
				if($service_charge_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'hospital-administration/activate-service-charge/'.$service_id.'/'.$service_charge_id.'" onclick="return confirm(\'Do you want to activate '.$service_charge_name.'?\');" title="Activate '.$service_charge_name.'"><i class="fa fa-thumbs-up"></i> Activate</a>';
				}
				//create activated status display
				else if($service_charge_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'hospital-administration/deactivate-service-charge/'.$service_id.'/'.$service_charge_id.'" onclick="return confirm(\'Do you want to deactivate '.$service_charge_name.'?\');" title="Deactivate '.$service_charge_name.'"><i class="fa fa-thumbs-down"></i> Deactivate</a>';
				}
				
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
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
						}
					}
				}
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$visit_type_name.'</td>
							<td>'.$service_charge_name.'</td>
							<td>'.$service_charge_amount.'</td>
							<td><a href="'.site_url().'hospital-administration/edit-service-charge/'.$service_id.'/'.$service_charge_id.'" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</a></td>
							<td>'.$button.'</td>
							<td><a href="'.site_url().'hospital-administration/delete-service-charge/'.$service_id.'/'.$service_charge_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this charge?\')"><i class="fa fa-trash"></i> Delete </a></td>
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
			$result .= "There are no service charges";
		}
?>
            <?php echo $result; ?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        
		</section>
        </div>
        </div>
        