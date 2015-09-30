<?php echo $this->load->view('search/service_search', '', TRUE);?>
 <div class="row">
	<div class="col-md-12">
    	<div class="pull-left">
        	<?php
			$search = $this->session->userdata('service_search');
			
			if(!empty($search))
			{
				echo '<a href="'.site_url().'hospital_administration/services/close_service_search" class="btn btn-sm btn-warning"><i class="fa fa-times"></i> Close Search</a>';
			}
			?>
        </div>
        
		<div class="pull-right">
		 <a href="<?php echo site_url()?>hospital-administration/add-service" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add service </a>

		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">

      
 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
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
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th><a href="'.site_url().'hospital-administration/services/service.department_id/'.$order_method.'/'.$page.'">Department</a></th>
						  <th><a href="'.site_url().'hospital-administration/services/service.service_name/'.$order_method.'/'.$page.'">Service name</a></th>
						  <th><a href="'.site_url().'hospital-administration/services/service.last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						  <th><a href="'.site_url().'hospital-administration/services/service.modified_by/'.$order_method.'/'.$page.'">Modified by</a></th>
						  <th>Status</th>
						  <th colspan="4">Actions</th>
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
				
				$service_id = $row->service_id;
				$department_name = $row->department_name;
				$service_name = $row->service_name;
				$service_status = $row->service_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
				$created = date('jS M Y H:i a',strtotime($row->created));
				
				//create deactivated status display
				if($service_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info btn-sm" href="'.site_url().'hospital-administration/activate-service/'.$service_id.'" onclick="return confirm(\'Do you want to activate '.$service_name.'?\');" title="Activate '.$service_name.'"><i class="fa fa-thumbs-up"></i> Activate</a>';
				}
				//create activated status display
				else if($service_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default btn-sm" href="'.site_url().'hospital-administration/deactivate-service/'.$service_id.'" onclick="return confirm(\'Do you want to deactivate '.$service_name.'?\');" title="Deactivate '.$service_name.'"><i class="fa fa-thumbs-down"></i> Deactivate</a>';
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
				
				else
				{
				}
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$department_name.'</td>
							<td>'.$service_name.'</td>
							<td>'.$last_modified.'</td>
							<td>'.$modified_by.'</td>
							<td>'.$status.'</td>
							<td><a href="'.site_url().'hospital-administration/service-charges/'.$service_id.'" class="btn btn-sm btn-warning"><i class="fa fa-money"></i> Charges</a></td>
							<td><a href="'.site_url().'hospital-administration/import-charges/'.$service_id.'" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Import</a></td>
							<td>'.$button.'</td>
							<td><a href="'.site_url().'hospital-administration/edit-service/'.$service_id.'" class="btn btn-sm btn-info"> <i class="fa fa-pencil"></i> Edit</a></td>
							<td><a href="'.site_url().'hospital-administration/delete-service/'.$service_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this service?\')"><i class="fa fa-trash"></i> Delete</a></td>
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
			$result .= "There are no services";
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
        