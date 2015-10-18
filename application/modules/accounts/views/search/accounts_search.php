 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search</h2>
    </header>
    
    <!-- Widget content -->
    <div class="panel-body">
        <div class="padd">
            <?php
            echo form_open("accounts/hospital_accounts/search_hospital_accounts", array("class" => "form-horizontal"));
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label">Account: </label>
                        
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="account_name" placeholder="Account"/>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="center-align">
                        <button type="submit" class="btn btn-info btn-sm">Search</button>
                    </div>
                </div>
            </div>
            
            
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</section>