 <div class="row">
    <div class="col-md-12">
        <a href="<?php echo site_url();?>/pharmacy/brands" class="btn btn-primary pull-right">Back to brands</a>
    </div>
</div>
<!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title?> </h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
    <div class="widget-content">
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
            if(!empty($brand_id))
            {
                echo form_open("pharmacy/update_brand/".$brand_id, array("class" => "form-horizontal"));

                if($brand_details->num_rows() > 0)
                {
                    $brand_details = $brand_details->result();
                                    
                    foreach($brand_details as $details)
                    {
                        $brand_id = $details->brand_id;
                        $brand_name = $details->brand_name;

                    
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Brand name: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="brand_name" placeholder="Brand name" value="<?php echo $brand_name;?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="center-align">
                    <button type="submit" class="btn btn-info btn-lg">Update brand</button>
                </div>
                
                <?php
            }
            else
            {
                echo form_open("pharmacy/create_new_brand", array("class" => "form-horizontal"));
            
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Brand name: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="brand_name" placeholder="Brand name">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="center-align">
                	<button type="submit" class="btn btn-info btn-lg">Add new brand</button>
                </div>
                
                <?php
            }
            echo form_close();
            ?>
    	</div>
    </div>
</div>