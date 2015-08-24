<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Search Staff</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
            <p class="center-align">Enter the staff member's number to add their dependants</p>
            <form class="form-horizontal" role="form" method="POST" action="<?php echo site_url().'/reception/search_staff'?>">
                <input type="hidden" name="dependant" value="1" />
                <div class="form-group">
                    <label class="col-lg-2 control-label">Staff Number</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="staff_number" placeholder="Staff Number">
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-info btn-lg" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>