
<a href="" class="logo"> 
    <span class="logo-lg">
        <img src="<?php echo base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png')) ?>" alt="">
    </span>
    <span class="logo-mini">

<img src="<?php echo base_url((!empty($setting->favicon)?$setting->favicon:'assets/img/icons/mini-logo.png')) ?>" alt="">
</span>
</a>
<div class="se-pre-con"></div>
<!-- Header Navbar -->
<?php $gui_p = $this->uri->segment(1);
         if($gui_p != 'gui_pos'){
         ?> 
<nav class="navbar navbar-static-top"  style="background-color:#B2BEB5">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
        <span class="sr-only">Toggle navigation</span>
        <span class="pe-7s-keypad" ></span>
    </a>


    <div class="navbar-custom-menu" >

        <ul class="nav navbar-nav">
            <!-- Messages -->
           
              <?php if($max_version > $current_version){?>
                <li > <blink><a href="<?php echo base_url('autoupdate/Autoupdate')?>" class="btn-danger update-btn"> <?php echo $max_version.' Version Available'; ?></a></blink>
                </li>
              <?php }?>
         
            <!-- settings -->
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle"  data-toggle="dropdown"> <i class="pe-7s-settings" ></i></a>
                <ul class="dropdown-menu" >
                    <li><a href="<?php echo base_url('edit_profile') ?>"><i class="pe-7s-users"></i> View Profile</a></li>
                    <li><a href="<?php echo base_url('logout') ?>"><i class="pe-7s-key" style="margin-right: 17px;"></i>  <?php echo display('logout') ?></a></li>
                </ul>
            </li>
        </ul>
    </div>
  
</nav>
<?php }?>