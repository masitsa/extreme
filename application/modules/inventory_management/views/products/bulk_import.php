      <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
          <div class="padd">
			<h3>Product Details</h3>
			<?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
         	<div class="row">
            	<div class="col-md-6">
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    <!-- product Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Upload CSV/XLS</label><br> <br>

                        <div class="col-lg-7">
                            
                            <input type="file" class="submit btn btn-success" name="img">
                        </div>
                    </div>
                   

<?php
                 /** Below is the code you gave me. Oh Snap, not displaying browse button, kinda all i need. I will therefore comment it out**/
            /*	$data = array(
					  'class'       => 'custom-file-input btn-red btn-width',
					  'name'        => 'import_csv',
					  'onchange'    => 'this.form.submit();',
					  'type'       	=> 'file'
					);

			 echo form_input($data);
			
			
			 echo form_close();*/
?>