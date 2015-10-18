<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title"><?php echo $title;?></h2>
    </header>             
    
    <div class="panel-body">
    	<div class="row" style="margin-bottom:10px;">
            <div class="col-md-12">
                <a href="<?php echo site_url();?>pharmacy/types/<?php echo $page;?>" class="btn btn-primary pull-right btn-sm">Back to types</a>
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
            if(!empty($drug_type_id))
            {
                echo form_open("pharmacy/update_type/".$drug_type_id.'/'.$page, array("class" => "form-horizontal"));

                if($type_details->num_rows() > 0)
                {
                    $type_details = $type_details->result();
                                    
                    foreach($type_details as $details)
                    {
                        $drug_type_id = $details->drug_type_id;
                        $drug_type_name = $details->drug_type_name;

                    
                    }
                }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Type name: </label>
                            
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="drug_type_name" placeholder="type name" value="<?php echo $drug_type_name;?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Update type</button>
                        </div>
                    </div>
                </div>
                <?php
            }
            else
            {
                echo form_open("pharmacy/create_new_type/".$page, array("class" => "form-horizontal"));
            
                ?>
                <div class="row">
                    <div class="col-md-10">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Type name: </label>
                            
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="drug_type_name" placeholder="type name">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                    	<div class="center-align">
                            <button type="submit" class="btn btn-info btn-sm">Add type</button>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            echo form_close();
            ?>
    </div>
</section>