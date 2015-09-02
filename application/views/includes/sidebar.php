<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->

                <li class="current"><a href="#"><i class="icon-desktop"></i> Dashboard</a></li>
                <li class="current"><a href="<?php echo base_url();?>index.php/welcome/control_panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>

                <!-- Menu with sub menu -->
                <li class="has_submenu">
                    <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-th"></i> Administration
                    </a>
                    <ul>
                        <li><a href="<?php echo base_url();?>index.php/administration/personnel">Personnel</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/services">Services</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/patient_type">Patient Type</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/add_credit">Set Personnel Allowance</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/supportstaff">Strathmore Staff</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/companies">Company</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/insurance_company">Insurance Company</a></li>
                    </ul>
                </li>

                <li class="has_submenu">
                    <a href="#">
                        <i class="icon-file-alt"></i> Reports
                    </a>

                    <ul>
                        <li><a href="<?php echo base_url();?>data/reports/reports.php" target="_blank">Accounts</a></li>
                        <li><a href="<?php echo base_url();?>data/reports/cash_reports.php" target="_blank">Cash Reports</a></li>
                        <li><a href="<?php echo base_url();?>data/reports/debtors.php" target="_blank">Debtors</a></li>
                        <li><a href="<?php echo base_url();?>data/reports/expenses.php" target="_blank">Expenses</a></li>
                        <li><a href="<?php echo base_url();?>index.php/reports/patient_reports">Patients</a></li>
                        <li><a href="<?php echo base_url();?>data/reports/summary.php" target="_blank">Summary</a></li>
                    </ul>
                </li>

                <li class="has_submenu">
                    <a href="#">
                        <i class="icon-file-alt"></i> Logs
                    </a>

                    <ul>
                        <li><a href="<?php echo base_url();?>index.php/administration/personnel">Login Sessions</a></li>
                        <li><a href="<?php echo base_url();?>index.php/administration/consultation_charges">Usage</a></li>
                    </ul>
                </li>

                <li class="has_submenu">
                    <a href="#">
                        <i class="icon-list"></i> Export
                    </a>

                    <ul>
                        <li><a href="<?php echo base_url();?>data/export/invoice.php" target="_blank">Invoices</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>