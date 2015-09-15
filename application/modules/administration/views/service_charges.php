<?php echo $this->load->view('search/service_charge_search', '', TRUE);?>

<div class="row">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>administration/services" class="btn btn-sm btn-primary"> Back to services </a>
		 <a href="<?php echo site_url()?>administration/add_service_charge/<?php echo $service_id;?>" class="btn btn-sm btn-success"> Add service Charge </a>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">

 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?> for <?php echo $service_name;?></h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
              </header>             

          <!-- Widget content -->
                <div class="panel-body">
          
<?php
		$error = $this->session->userdata('service_charge_error_message');
		$success = $this->session->userdata('service_charge_success_message');
		
		if(!empty($success))
		{
			echo '
				<div class="alert alert-success">'.$success.'</div>
			';
			$this->session->unset_userdata('service_charge_success_message');
		}
		
		if(!empty($error))
		{
			echo '
				<div class="alert alert-danger">'.$error.'</div>
			';
			$this->session->unset_userdata('service_charge_error_message');
		}
		$search = $this->session->userdata('service_charge_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'administration/close_service_charge_search" class="btn btn-warning">Close Search</a>';
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
						  <th>Visit Type</th>
						  <th>Service Charge Name</th>
						  <th>Service Charge Amount</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				$service_charge_id = $row->service_charge_id;
				$service_charge_name = $row->service_charge_name;
				$visit_type_name = $row->visit_type_name;
				$service_charge_amount = $row->service_charge_amount;
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$visit_type_name.'</td>
							<td>'.$service_charge_name.'</td>
							<td>'.$service_charge_amount.'</td>
							<td><a href="'.site_url().'administration/edit_service_charge/'.$service_id.'/'.$service_charge_id.'" class="btn btn-sm btn-info"> Edit </a></td>
							<td><a href="'.site_url().'administration/delete_service_charge/'.$service_id.'/'.$service_charge_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this charge?\'"> Delete </a></td>
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
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        
		</section>
        </div>
        </div>
        