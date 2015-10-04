 <section class="panel">
    <header class="panel-heading">
      <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
      <div class="widget-icons pull-right">
        <a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-default">Back to inventory</a>
        </div>
      <div class="clearfix"></div>
    </header>  
     <div class="panel-body">
        <div class="padd">

        	 <div class="clearfix"></div>

			     <div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs nav-justified">
                <li class="active">
                	<a href="#purchases" data-toggle="tab"><?php echo $title;?> Purchases</a>
                </li>
                <li>
                	<a href="#orders" data-toggle="tab"><?php echo $title;?> Sub Store Orders</a>
                </li>
                <li>
                	<a href="#deductions" data-toggle="tab"><?php echo $title;?> Deductions</a>
                </li>
              </ul>
              <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
                <div class="tab-pane active" id="purchases">
                	 <?php echo $this->load->view("purchases", '', TRUE);?>
                </div>
                <div class="tab-pane" id="orders">
                	 <?php echo $this->load->view("orders", '', TRUE);?>
                </div>
                <div class="tab-pane" id="deductions">
                  	 <?php echo $this->load->view("deductions", '', TRUE);?>
                </div>

                
              </div>
            </div>
        </div>
     </div>
 </section> 

 <script>
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function (e) {
        var id = $(e.target).attr("href").substr(1);
        window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');
</script>