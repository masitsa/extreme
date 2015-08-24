
<div class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">

<div class="containerk">
<!-- Menu button for smallar screens -->
<div class="navbar-header">
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a href="index.html" class="navbar-brand"><span class="bold">S</span>UMC</a>
</div>
<!-- Site name for smallar screens -->


<!-- Navigation starts -->
<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">


<!-- Notifications -->
<ul class="nav navbar-nav navbar-right">

    <li class="statistics-toggle"><a href="#"><i class="icon-resize-vertical"></i></a></li>

    <!-- Profile Links -->
    <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <i class="icon-male"></i>
            <span class="username"><?php echo $this->session->userdata('first_name');?></span>
            <b class="caret"></b>
        </a>
        <!-- Dropdown menu -->
        <ul class="dropdown-menu">
            <li><a href="<?php echo site_url();?>/change-password/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-user"></i> Change Password</a></li>
            <?php
            if(!isset($module))
            {
                $module = '';
            }
           
            if($module == 'user')
            {

            }
            else
            {
                ?>
                  <li><a href="<?php echo site_url();?>/control-panel/<?php echo $this->session->userdata('personnel_id');?>"><i class="icon-cogs"></i> Control Panel</a></li>
                <?php
            }
            ?>
            <li class="divider"></li>
            <li><a href="<?php echo site_url();?>/auth/login/logout_user"><i class="icon-off"></i> Logout</a></li>
        </ul>
    </li>
</ul>

</nav>

</div>
</div>