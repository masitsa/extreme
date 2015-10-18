<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
    </header>             
    
    <div class="panel-body">
    	<div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <a href="<?php echo site_url();?>pharmacy/containers/<?php echo $page;?>" class="btn btn-primary pull-right btn-sm">Back to containers</a>
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
            if(!empty($container_type_id))
            {
                echo form_open("pharmacy/update_container_type/".$container_type_id.'/'.$page, array("class" => "form-horizontal"));

                if($container_type_details->num_rows() > 0)
                {
                    $container_type_details = $container_type_details->result();
                                    
                    foreach($container_type_details as $details)
                    {
                        $container_type_id = $details->container_type_id;
                        $container_type_name = $details->container_type_name;

                    
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Container name: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="container_type_name" placeholder="Container name" value="<?php echo $container_type_name;?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Update container</button>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            else
            {
                echo form_open("pharmacy/create_new_container_type/".$page, array("class" => "form-horizontal"));
            
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Container type name: </label>
                            
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="container_type_name" placeholder="Container name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Add new container</button>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            echo form_close();
            ?>
    </div>
</section>