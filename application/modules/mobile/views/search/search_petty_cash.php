 <section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">Search</h2>
    </header>
    
    <!-- Widget content -->
    <div class="panel-body">
        <div class="padd">
            <form id="petty_cash_search" method="post">
            <div class="row">
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date from: </label>
                        
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="date" class="form-control" name="date_from" placeholder="Date from">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Date to: </label>
                        
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input type="date" class="form-control" name="date_to" placeholder="Date to">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Branch: </label>
                        
                        <div class="col-lg-8">
                            <select class="form-control" name="branch_code">
                                <option value="_">---Select branch---</option>
                                <?php
                                    if($branches->num_rows() > 0){
                                        foreach($branches->result() as $row):
                                            $branch_name = $row->branch_name;
                                            $branch_code = $row->branch_code;
                                            echo "<option value=".$branch_code.">".$branch_name."</option>";
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <br>
                    <div class="center-align">
                        <button type="submit" class="btn btn-info btn-sm">Search</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section>