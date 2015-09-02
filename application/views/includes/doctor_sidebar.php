<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li><a href="<?php echo site_url();?>/doctor"><i class="icon-list"></i> Dashboard</a></li>
                <li><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                <li><a href="<?php echo site_url();?>/doctor/doctor_queue"><i class="icon-sitemap"></i> Doctor Queue</a></li>
                <li><a href="<?php echo site_url();?>/reception/general_queue/doctor"><i class="icon-home"></i> General Queue</a></li>
                <li><a href="<?php echo site_url();?>/reception/visit_list/1/doctor"><i class="icon-home"></i> Visit History</a></li>
                <li><a href="<?php echo site_url();?>/nurse/patient_treatment_statement/doctor"><i class="icon-home"></i> Patients Treatment</a></li>
            </ul>

        </div>
    </div>