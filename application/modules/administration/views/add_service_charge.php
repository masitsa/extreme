<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
		<div class="pull-right">
		 <a href="<?php echo site_url()?>administration/service_charges/<?php echo $service_id;?>" class="btn btn-sm btn-primary"> Back to <?php echo $service_name;?> Service List </a>

		</div>
	</div>
</div>
<div class="row">
    <div class="col-md-12">

 <section class="panel">
    <header class="panel-heading">
                <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?> for <?php echo $service_name;?></h4>
                <div class="widget-icons pull-right">
                  <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
                </div>
                <div class="clearfix"></div>
              </header>             

          <!-- Widget content -->
                <div class="panel-body">
            <div class="center-align">
              <?php
                $error = $this->session->userdata('error_message');
                $validation_error = validation_errors();
                $success = $this->session->userdata('success_message');
                
                if(!empty($error))
                {
                  echo '<div class="alert alert-danger">'.$error.'</div>';
                  $this->session->unset_userdata('error_message');
                }
                
                if(!empty($validation_error))
                {
                  echo '<div class="alert alert-danger">'.$validation_error.'</div>';
                }
                
                if(!empty($success))
                {
                  echo '<div class="alert alert-success">'.$success.'</div>';
                  $this->session->unset_userdata('success_message');
                }
              ?>
            </div>
            <?php
            if($service_charge_id > 0){
                $res = $this->administration_model->get_service_charge_data($service_charge_id);

                if(count($res) > 0){
                    foreach ($res as $key_res):
                          $service_charge_name = $key_res->service_charge_name;
                          $visit_type_id = $key_res->visit_type_id;
                          $service_charge_amount = $key_res->service_charge_amount;
                    endforeach;
                }
              ?>
             <?php echo form_open('administration/update_service_charge/'.$service_id."/".$service_charge_id, array('class' => 'form-horizontal'));?>
                 <div class="row">
                    <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service Charge name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_charge_name" placeholder="Service Charge Name" value="<?php echo $service_charge_name;?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service Name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_name" placeholder="" value="<?php echo $service_name;?>" readonly='readonly'>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Visit Type</label>
                              <div class="col-lg-8">
                                   <select name="patient_type" id="patient_type"  class="form-control">
                                      <option value="">--- Select Visit Type---</option>
                                        <?php
                                          if(count($type) > 0){
                                                      foreach($type as $row):
                                              $type_name = $row->visit_type_name;
                                              $type_id= $row->visit_type_id;
                                              
                                              if($type_id == $visit_type_id)
                                              {
                                                echo "<option value='".$type_id."' selected='selected'>".$type_name."</option>";
                                              }
                                              
                                              else
                                              {
                                                echo "<option value='".$type_id."'>".$type_name."</option>";
                                              }
                                            endforeach;
                                          }
                                        ?>
                                      </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Amount</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="charge" placeholder="Charge" value="<?php echo $service_charge_amount;?>">
                              </div>
                          </div>
                      </div>
                </div>

                <div class="center-align">
                  <button type="submit" class="btn btn-info btn-lg">Update Service Charge</button>
                </div>
            <?php echo form_close();
            
            }
			
			else
			{
            ?>
            <?php echo form_open('administration/service_charge_add/'.$service_id, array('class' => 'form-horizontal'));?>
                 <div class="row">
                    <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service Charge name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_charge_name" placeholder="Service Charge Name" value="">
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Service Name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="service_name" placeholder="" value="<?php echo $service_name;?>" readonly='readonly'>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Visit Type</label>
                              <div class="col-lg-8">
                                   <select name="patient_type" id="patient_type"  class="form-control">
                                      <option value="">--- Select Visit Type---</option>
                                        <?php
                                          if(count($type) > 0){
                                                      foreach($type as $row):
                                              $type_name = $row->visit_type_name;
                                              $type_id= $row->visit_type_id;
                                              
                                              if($type_id == set_value('patient_type'))
                                              {
                                                echo "<option value='".$type_id."' selected='selected'>".$type_name."</option>";
                                              }
                                              
                                              else
                                              {
                                                echo "<option value='".$type_id."'>".$type_name."</option>";
                                              }
                                            endforeach;
                                          }
                                        ?>
                                      </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-lg-4 control-label">Amount</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control" name="charge" placeholder="Charge" value="">
                              </div>
                          </div>
                      </div>
                </div>

                <div class="center-align">
                  <button type="submit" class="btn btn-info">Add New Service Charge</button>
                </div>
            <?php echo form_close();?>
            <?php
            }
            ?>
            </div>
        
		</section>
  </div>
</div>