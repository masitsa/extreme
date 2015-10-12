
<div class="row">
    <div class="col-md-12">
        <?php
            $success = $this->session->userdata('success_message');
            $error = $this->session->userdata('error_message');
            
            if(!empty($success))
            {
                echo '
                    <div class="alert alert-success">'.$success.'</div>
                ';
                
                $this->session->unset_userdata('success_message');
            }
            
            if(!empty($error))
            {
                echo '
                    <div class="alert alert-danger">'.$error.'</div>
                ';
                
                $this->session->unset_userdata('error_message');
            }
            
        ?>
    </div>
</div>

<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Schedule personnel</h2>
    </header>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                
                <?php echo form_open("hr/schedules/add_personnel_schedule/".$schedule_id);?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-lg-12 control-label">Personnel : </label>
                        
                            <div class="col-lg-12">
                                <select class="form-control" name="personnel_id">
                                    <option value="">--Select personnel--</option>
                                    <?php
                                        if($personnel->num_rows() > 0)
                                        {
                                            foreach($personnel->result() as $res)
                                            {
                                                $personnel_id = $res->personnel_id;
                                                $personnel_fname = $res->personnel_fname;
                                                $personnel_onames = $res->personnel_onames;
                                                
                                                if($old_personnel_id == $personnel_id)
                                                {
                                                    echo "<option value='".$personnel_id."' selected>".$personnel_fname." ".$personnel_onames."</option>";
                                                }
                                                
                                                else
                                                {
                                                    echo "<option value='".$personnel_id."'>".$personnel_fname." ".$personnel_onames."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-lg-12 control-label">Date : </label>
                        
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date" placeholder="Date" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-lg-12 control-label">Start time : </label>
                        
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </span>
                                    <input type="text" class="form-control" data-plugin-timepicker="" name="start_time">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-lg-12 control-label">End time : </label>
                            
                            <div class="col-lg-12">		
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </span>
                                    <input type="text" class="form-control" data-plugin-timepicker="" name="end_time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-3 col-md-offset-5">
                        <button type="submit" class="btn btn-primary">Add schedule</button>
                    </div>
                </div>
                <?php echo form_close();?> 
            </div>
        </div>
    </div>
</section>
<?php echo $this->load->view('schedules/doctors_schedule');?>