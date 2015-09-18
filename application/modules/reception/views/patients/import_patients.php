<section class="panel">

 
        <!-- Widget head -->
        <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
          	<a href="<?php echo base_url().'reception/patients-list';?>" class="btn btn-sm btn-info">Back to patients</a>
          </div>
          <div class="clearfix"></div>
        </header>             

        <!-- Widget content -->
         <div class="panel-body">
          <div class="padd">
            
            <div class="row">
                <div class="col-md-12">
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
            <?php
                if(isset($import_response))
                {
                    if(!empty($import_response))
                    {
                        echo $import_response;
                    }
                }
                
                if(isset($import_response_error))
                {
                    if(!empty($import_response_error))
                    {
                        echo '<div class="center-align alert alert-danger">'.$import_response_error.'</div>';
                    }
                }
            ?>
                </div>
            </div>
            <?php echo form_open_multipart('reception/validate-import', array("class" => "form-horizontal", "role" => "form"));?>
            
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li>Download the import template <a href="<?php echo site_url().'reception/import-template';?>">here.</a></li>
                        
                        <li>Save your file as a <strong>csv</strong> file before importing</li>
                        <li>After adding your patients to the import template please import them using the button below</li>
                    </ul>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <?php
                    /*$data = array(
                          'class'       => 'custom-file-input btn-red btn-width',
                          'name'        => 'import_csv',
                          'onchange'    => 'this.form.submit();',
                          'type'       	=> 'file'
                        );
                
                    echo form_input($data);*/
                    ?>
                    <div class="fileUpload btn btn-primary">
                        <span>Import patients</span>
                        <input type="file" class="upload" onChange="this.form.submit();" name="import_csv" />
                    </div>
                </div>
            </div>
            <?php echo form_close();?>
		</div>
      </div>
</section>