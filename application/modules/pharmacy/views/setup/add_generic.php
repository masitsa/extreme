<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
    </header>             
    
    <div class="panel-body">
    	<div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <a href="<?php echo site_url();?>pharmacy/generics/<?php echo $page;?>" class="btn btn-primary pull-right btn-sm">Back to generics</a>
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
            if(!empty($generic_id))
            {
                echo form_open("pharmacy/update_generic/".$generic_id.'/'.$page, array("class" => "form-horizontal"));

                if($generic_details->num_rows() > 0)
                {
                    $generic_details = $generic_details->result();
                                    
                    foreach($generic_details as $details)
                    {
                        $generic_id = $details->generic_id;
                        $generic_name = $details->generic_name;
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Generic name: </label>
                            
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="generic_name" placeholder="Generic name" value="<?php echo $generic_name;?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Update generic</button>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            else
            {
                echo form_open("pharmacy/create_new_generic/".$page, array("class" => "form-horizontal"));
            
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Generic name: </label>
                            
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="generic_name" placeholder="Generic name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Add new generic</button>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            echo form_close();
            ?>
    </div>
</section>