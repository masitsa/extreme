<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li class="current"><a href="<?php echo site_url();?>/administration"><i class="icon-list"></i> Dashboard</a></li>
                <li class="current"><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                <li class="current"><a href="<?php echo site_url();?>/administration/patient_statement"><i class="icon-list"></i> Patients</a></li>
                <li class="current"><a href="<?php echo site_url();?>/reception/general_queue/administration"><i class="icon-list"></i> General Queue</a></li>
               
                <!--<li class="current"><a href="<?php echo base_url();?>index.php/welcome/control_panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-list"></i> Appointments</a></li>-->
             	<li class="has_submenu">
                    <a href="#">
                        <i class="icon-th"></i> Reports
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/administration/reports/all_reports">All Transactions</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/cash_report">Cash Report</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/debtors_report">Debtors Report</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/department_reports">Department Report</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/doctor_reports">Doctor Report</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/time_reports">Time Report</a></li>
                    </ul>
                </li>

                <!-- Menu with sub menu -->
             	<li class="has_submenu">
                    <a href="#">
                        <i class="icon-th"></i> Administration
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/administration/services">Services</a></li>
                        <li><a href="<?php echo site_url();?>/administration/personnel">Personnel</a></li>
                        <li><a href="<?php echo site_url();?>/administration/import_data">Staff/ Student Import</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>