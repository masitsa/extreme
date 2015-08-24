<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li class="current"><a href="<?php echo site_url();?>/reception"><i class="icon-list"></i> Dashboard</a></li>
                <li class="current"><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                
                <!--<li class="current"><a href="<?php echo base_url();?>index.php/welcome/control_panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-list"></i> Appointments</a></li>-->

                <!-- Menu with sub menu -->
                <li class="has_submenu">
                    <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-th"></i> Patients
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/reception/staff">Staff</a></li>
                        <li><a href="<?php echo site_url();?>/reception/staff_dependants">Staff Dependants</a></li>
                        <li><a href="<?php echo site_url();?>/reception/students">Students</a></li>
                        <li><a href="<?php echo site_url();?>/reception/all-patients">Others</a></li>
                        <li><a href="<?php echo site_url();?>/reception/add-patient">Add Patients</a></li>
                        <li><a href="<?php echo site_url();?>/reception/patients/1">Deleted Patients</a></li>
                    </ul>
                </li>

                <!-- Menu with sub menu -->
                <li class="has_submenu">
                    <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-th"></i> Visits
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/reception/appointment_list">Appointment List</a></li>
                        <li><a href="<?php echo site_url();?>/reception/visit_list/2">Deleted Visits</a></li>
                        <!--<li><a href="<?php echo site_url();?>/reception/visit_list/0">Ongoing Visits</a></li>-->
                        <li><a href="<?php echo site_url();?>/reception/general_queue/reception">General Queue</a></li>
                        <li><a href="<?php echo site_url();?>/reception/visit_list/3">Unclosed Visits</a></li>
                        <li><a href="<?php echo site_url();?>/reception/visit_list/1">Visit History</a></li>
                    </ul>
                </li>
                <li class="current"><a href="<?php echo site_url();?>/administration/export_charges"><i class="icon-list"></i> Charge List</a></li>

            </ul>

        </div>
    </div>