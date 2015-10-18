        
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
    </header>             
    
    <div class="panel-body">
    	<div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <a href="<?php echo site_url();?>pharmacy/brands/<?php echo $page;?>" class="btn btn-primary pull-right btn-sm">Back to brands</a>
            </div>
        </div>
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
                echo form_open("pharmacy/update_brand/".$brand_id.'/'.$page, array("class" => "form-horizontal"));

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
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Update brand</button>
                        </div>
                    </div>
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
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Add new brand</button>
                        </div>
                    </div>
                </div>
                
                
                
                <?php
            }
            echo form_close();
            ?>
    </div>
</section>