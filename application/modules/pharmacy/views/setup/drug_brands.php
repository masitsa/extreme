<?php echo $this->load->view('search/search_brand', '', TRUE);?>
<!-- end search -->
 <div class="row">
	<div class="col-md-12">
		<a href="<?php echo site_url();?>pharmacy/add_brand/<?php echo $page;?>" class="btn btn-success pull-right btn-sm">Add new brand</a>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title"><?php echo $title;?></h2>
            </header>             
            
            <div class="panel-body">
          
<?php
		$search = $this->session->userdata('brands_search');
		
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
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'pharmacy/close_brand_search/'.$page.'" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
	
		//if users exist display them

		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered table-striped table-condensed">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Brand</th>
						  <th colspan=3>Action</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				$count++;
				$brand_id = $row->brand_id;
				$brand_name = $row->brand_name;
				$brand_delete = $row->brand_delete;
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$brand_name.'</td>
							<td><a href="'.site_url().'pharmacy/add_brand/'.$page.'/'.$brand_id.'" class="btn btn-sm btn-success">Edit</a></td>
							<td><a href="'.site_url().'pharmacy/delete_brand/'.$brand_id.'/'.$page.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete '.$brand_name.'?\');">Delete brand</a></td>
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
			$result .= "There are no brands";
		}
		
		echo $result;
?>
          </div>
          
          <div class="panel-foot">
                                
			<?php if(isset($links)){echo $links;}?>
        
            <div class="clearfix"></div> 
        
        </div>

      </section>
    </div>
  </div>