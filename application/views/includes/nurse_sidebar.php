<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li><a href="<?php echo site_url();?>/nurse"><i class="icon-list"></i> Dashboard</a></li>
                <li><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                <li><a href="<?php echo site_url();?>/nurse/nurse_queue"><i class="icon-sitemap"></i> Nurse Queue</a></li>
                <li><a href="<?php echo site_url();?>/reception/general_queue/nurse"><i class="icon-home"></i> General Queue</a></li>
                <li><a href="<?php echo site_url();?>/reception/visit_list/1/nurse"><i class="icon-home"></i> Visit History</a></li>
                <li class="has_submenu">
                    <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-th"></i> Nurse Setup
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/nurse/inventory">Vaccine Inventory</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>