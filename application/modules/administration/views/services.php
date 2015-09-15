<?php echo $this->load->view('search/service_search', '', TRUE);?>
 <div class="row">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>administration/new_service" class="btn btn-sm btn-success">Add a New Service </a>

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
		$error = $this->session->userdata('service_error_message');
		$success = $this->session->userdata('service_success_message');
		
		if(!empty($success))
		{
			echo '
				<div class="alert alert-success">'.$success.'</div>
			';
			$this->session->unset_userdata('service_success_message');
		}
		
		if(!empty($error))
		{
			echo '
				<div class="alert alert-danger">'.$error.'</div>
			';
			$this->session->unset_userdata('service_error_message');
		}
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'nurse/close_queue_search" class="btn btn-warning">Close Search</a>';
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
						  <th>Service Name</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				
				$service_id = $row->service_id;
				$service_name = $row->service_name;
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$service_name.'</td>
							<td><a href="'.site_url().'administration/service_charges/'.$service_id.'" class="btn btn-sm btn-success">Service Charges</a></td>
							<td><a href="'.site_url().'administration/edit_service/'.$service_id.'" class="btn btn-sm btn-info"> Edit </a></td>
							<td><a href="'.site_url().'administration/delete_service/'.$service_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this service?\'"> Delete </a></td>
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
        