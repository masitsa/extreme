<?php
		
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
						<th><a href="'.site_url().'hospital-administration/companies/insurance_company_name/'.$order_method.'/'.$page.'">Company name</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/insurance_company_contact_person_name/'.$order_method.'/'.$page.'">Contact person</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/insurance_company_contact_person_phone1/'.$order_method.'/'.$page.'">Primary phone</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/insurance_company_contact_person_email1/'.$order_method.'/'.$page.'">Primary email</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/modified_by/'.$order_method.'/'.$page.'">Modified by</a></th>
						<th><a href="'.site_url().'hospital-administration/companies/company_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
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
				$insurance_company_id = $row->insurance_company_id;
				$insurance_company_name = $row->insurance_company_name;
				$insurance_company_contact_person_name = $row->insurance_company_contact_person_name;
				$insurance_company_contact_person_phone1 = $row->insurance_company_contact_person_phone1;
				$insurance_company_contact_person_phone2 = $row->insurance_company_contact_person_phone2;
				$insurance_company_contact_person_email1 = $row->insurance_company_contact_person_email1;
				$insurance_company_contact_person_email2 = $row->insurance_company_contact_person_email2;
				$insurance_company_description = $row->insurance_company_description;
				$insurance_company_status = $row->insurance_company_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				
				//create deactivated status display
				if($insurance_company_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'hospital-administration/activate-insurance-company/'.$insurance_company_id.'" onclick="return confirm(\'Do you want to activate '.$insurance_company_name.'?\');" title="Activate '.$insurance_company_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($insurance_company_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'hospital-administration/deactivate-insurance-company/'.$insurance_company_id.'" onclick="return confirm(\'Do you want to deactivate '.$insurance_company_name.'?\');" title="Deactivate '.$insurance_company_name.'"><i class="fa fa-thumbs-down"></i></a>';
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
						<td>'.$insurance_company_name.'</td>
						<td>'.$insurance_company_contact_person_name.'</td>
						<td>'.$insurance_company_contact_person_phone1.'</td>
						<td>'.$insurance_company_contact_person_email1.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'hospital-administration/edit-insurance-company/'.$insurance_company_id.'" class="btn btn-sm btn-success" title="Edit '.$insurance_company_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'hospital-administration/delete-insurance-company/'.$insurance_company_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$insurance_company_name.'?\');" title="Delete '.$insurance_company_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no insurance companies";
		}
?>

						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>hospital-administration/add-insurance-company" class="btn btn-success pull-right btn-sm">Add Company</a>
                                    </div>
                                </div>
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
								?>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
						</section>