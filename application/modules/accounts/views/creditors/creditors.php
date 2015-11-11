<!-- search -->
<?php //echo $this->load->view('search/creditors_search', '', TRUE);?>
<!-- end search -->
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?> </h2>
    </header>

    <!-- Widget content -->
    <div class="panel-body">
    	<div class="padd">
          <?php
            	$error = $this->session->userdata('error_message');
				$success = $this->session->userdata('success_message');
				
				if(!empty($error))
				{
					echo '<div class="alert alert-danger">'.$error.'</div>';
					$this->session->unset_userdata('error_message');
				}
				
				if(!empty($success))
				{
					echo '<div class="alert alert-success">'.$success.'</div>';
					$this->session->unset_userdata('success_message');
				}
			?>
            
           	<div style="min-height:30px;">
            	<div class="pull-right">
                	<a href="<?php echo site_url();?>accounts/creditors/add_creditor" class="btn btn-primary">Add creditors</a>
                	<?php
					$search = $this->session->userdata('search_creditors');
		
					if(!empty($search))
					{
						echo '<a href="'.site_url().'accounts/creditors/close_search_creditors" class="btn btn-warning btn-sm">Close Search</a>';
					}
					?>
                </div>
            </div>
                
<?php
		
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= '
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Creditor name</th>
						  <th>Total payments</th>
						  <th>Total invoice</th>
						  <th colspan="2">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				$count++;
				$creditor_id = $row->creditor_id;
				$creditor_name = $row->creditor_name;
				$invoice_total = 0;
				$payments_total = 0;
				$creditor_status = $row->creditor_status;
				
				if($creditor_status == 1)
				{
					$checked_active = 'checked';
					$checked_inactive = '';
				}
				else
				{
					$checked_active = '';
					$checked_inactive = 'checked';
				}
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$creditor_name.'</td>
							<td>'.number_format($invoice_total, 2).'</td>
							<td>'.number_format($payments_total, 2).'</td>
							<td><a href="'.site_url().'accounts/creditors/statement/'.$creditor_id.'" class="btn btn-sm btn-info">Statement</a></td>
							<td><a href="'.site_url().'accounts/creditors/edit_creditor/'.$creditor_id.'" class="btn btn-sm btn-success">Edit</a></td>
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
			$result .= "There are no creditors";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
</section>