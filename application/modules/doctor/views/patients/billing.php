<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Procedure' onclick='myPopup3(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="procedures"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Vaccines</h2>
            </header>
            <div class="panel-body">
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Vaccine' onclick='myPopup4(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="vaccines_to_patients"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Consumables</h2>
            </header>
            <div class="panel-body">
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Consumables' onclick='myPopup5(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="consumables_to_patients"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>