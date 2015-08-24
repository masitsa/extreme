<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li class="current"><a href="<?php echo site_url();?>/accounts"><i class="icon-list"></i> Dashboard</a></li>
                <li class="current"><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                <li class="current"><a href="<?php echo site_url();?>/accounts/accounts_queue"><i class="icon-home"></i> Accounts Queue</a></li>
                <li class="current"><a href="<?php echo site_url();?>/accounts/accounts_unclosed_queue"><i class="icon-home"></i> Unclosed Visits</a></li>
                 <li class="current"><a href="<?php echo site_url();?>/accounts/accounts_closed_visits"><i class="icon-home"></i> Closed  Visits</a></li>
                <li><a href="<?php echo site_url();?>/reception/general_queue/accounts"><i class="icon-home"></i> General Queue</a></li>

                <!-- Menu with sub menu -->
                <li class="has_submenu">
                    <a href="#">
                        <i class="icon-th"></i> Reports
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/administration/reports/all_reports/accounts">All Transactions</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/cash_report/accounts">Cash Report</a></li>
                        <li><a href="<?php echo site_url();?>/administration/reports/debtors_report/accounts">Debtors Report</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>