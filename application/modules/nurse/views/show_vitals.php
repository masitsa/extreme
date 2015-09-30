<div class="row">
	<div class="col-md-3">
      
        <div class="form-group">
            <label class="col-lg-8 control-label">Systolic: </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital5"  class="form-control"/>
          
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-8 control-label">Diastolic: </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital6"  class="form-control"/>
           
            </div>
        </div>
        
        <!-- Body mass index  -->

        <div class="form-group">
            <label class="col-lg-8 control-label">Weight (kg): </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital8"  class="form-control"/>
                
            </div>
        </div>
	</div>
	<div class="col-md-3">
        <div class="form-group">
            <label class="col-lg-8 control-label">Height (m) : </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital9"  class="form-control"/>
            </div>
        </div>
        <div id="bmi_out"></div>
        <!-- hip/ Weist -->

        <div class="form-group">
            <label class="col-lg-8 control-label">Hip : </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital4"  class="form-control"/>

            </div>

        </div>
        <div class="form-group">
            <label class="col-lg-8 control-label">Waist : </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital3" class="form-control"/>
                
            </div>
        </div>
        <div id="hwr_out"></div>
	</div>
	<div class="col-md-3">

        <!-- temparature -->

        <div class="form-group">
            <label class="col-lg-8 control-label">Temp: </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital1"  class="form-control"/>
              
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-8 control-label">Pulse: </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital7" class="form-control"/>
              
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-8 control-label">Respiration: </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital2" class="form-control"/>
                
            </div>
        </div>
	</div>
	<div class="col-md-3">

        <div class="form-group">
            <label class="col-lg-8 control-label">Oxygen Saturation : </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital11"  class="form-control"/>
                
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-8 control-label">Pain (0 - 10): </label>
            
            <div class="col-lg-4">
                <input type="text" id="vital10"  class="form-control"/>
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-lg-12">
                <div class="center-align">
                      <a hred="#" class="btn btn-sm btn-success" onclick="save_vital(<?php echo $visit_id;?>)">Save Vitals</a>
                  </div>
            </div>
        </div>
    </div>
</div>





		
		
		
	