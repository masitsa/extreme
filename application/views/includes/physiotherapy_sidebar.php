<div class="sidebar sidebar-fixed">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <div class="sidebar-inner">
		
            <!--- Sidebar navigation -->
            <!-- If the main navigation has sub navigation, then add the class "has_submenu" to "li" of main navigation. -->
            <ul class="navi">

                <!-- Use the class nred, ngreen, nblue, nlightblue, nviolet or norange to add background color. You need to use this in <li> tag. -->
                <li><a href="<?php echo site_url();?>/physiotherapy"><i class="icon-list"></i> Dashboard</a></li>
                <li><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-home"></i> Control Panel</a></li>
                <li><a href="<?php echo site_url();?>/physiotherapy/physiotherapy_queue"><i class="icon-sitemap"></i> Physio Queue</a></li>

                <!-- Menu with sub menu -->
                <li class="has_submenu">
                    <a href="#">
                        <!-- Menu name with icon -->
                        <i class="icon-th"></i> Queues
                    </a>
                    <ul>
                        <li><a href="<?php echo site_url();?>/reception/visit_list/0/physiotherapy">General Queue</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>