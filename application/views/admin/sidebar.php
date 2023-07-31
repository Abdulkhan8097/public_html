<?php
   $menuLevel1 = $this->uri->segment(1);
   $menuLevel2 = $this->uri->segment(2);
   $menuLevel3 = $this->uri->segment(3);
   
   
   $this->CI = &get_instance();
   
   
   ?>
<!--ASIDE 
   Keep it outside of #wrapper (responsive purpose)
   -->
<aside id="aside">
   <!--
      Always open:
      <li class="active alays-open">
      
      LABELS:
         <span class="label label-danger pull-right">1</span>
         <span class="label label-default pull-right">1</span>
         <sp
         an class="label label-warning pull-right">1</span>
         <span class="label label-success pull-right">1</span>
         <span class="label label-info pull-right">1</span>
      -->
   <nav id="sideNav">
      <!-- MAIN MENU -->
      <ul class="nav nav-list">
 <?php 
         $this->CI = &get_instance();
         $shop_id = $this->session->userdata('user_id');
      $this->CI->load->model('admin/User_model', 'user_model');
      $user_id = $this->session->userdata('user_id');
      $userdetails = $this->CI->user_model->getUserDetails($shop_id);
         if($userdetails['user_type']=='3'){
          ?>
<li class="<?php echo (strtolower($menuLevel2) == strtolower('dashboard')) ? ' active' : ''; ?>">
            <!-- dashboard -->
            <a class="dashboard" href="<?php echo ADMIN_DASHBOARD_URL; ?>">
               <!-- warning - url used by default by ajax (if eneabled) -->
               <i class="main-icon fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
         </li>
         <li
            class="<?php echo (strtolower($menuLevel2) == strtolower('User'))  ? ' active' : ''; ?>">
            <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-users"></i> <span>User Management</span>
            </a>
            <ul>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('User')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_USER_URL; ?>">
                  <i class=" fa fa-list"></i> User List</a>
               </li>
            </ul>
         </li>
      <?php }elseif($userdetails['user_type']=='1'){ ?>
         <li class="<?php echo (strtolower($menuLevel2) == strtolower('dashboard')) ? ' active' : ''; ?>">
            <!-- dashboard -->
            <a class="dashboard" href="<?php echo ADMIN_DASHBOARD_URL; ?>">
               <!-- warning - url used by default by ajax (if eneabled) -->
               <i class="main-icon fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
         </li>
         <li
            class="<?php echo (strtolower($menuLevel2) == strtolower('User'))  ? ' active' : ''; ?>">
            <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fa fa-users"></i> <span>User Management</span>
            </a>
            <ul>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('User')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_USER_URL; ?>">
                  <i class=" fa fa-list"></i> User List</a>
               </li>
            </ul>
         </li>
         <li class="<?php echo (strtolower($menuLevel2) == strtolower('Product') || strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Series') || strtolower($menuLevel2) == strtolower('Model') || strtolower($menuLevel2) == strtolower('Competitor') || strtolower($menuLevel2) == strtolower('Category') || strtolower($menuLevel2) == strtolower('CompPart')) ? ' active' : ''; ?>">
            <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fas fa-hdd"></i> <span>Product Management</span>
            </a>
            <ul>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Category')) ? ' active' : ''; ?> ">
                <a href="<?php echo ADMIN_CATEGORY_URL; ?>">
                   
                    <i class="fa fa-key"></i> <span>Category List</span>
                </a>    
               </li>
<li class="<?php echo (strtolower($menuLevel2) == strtolower('Seriescategory')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_SERIES_CATEGORY_URL; ?>">
                  <i class=" fa fa-arrows-alt"></i> <span>Series Category</span>      
                  </a>                 
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Series')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_SERIES_URL; ?>">
                  <i class=" fa fa-arrows-alt"></i> <span>Series</span>      
                  </a>                 
               </li>
<li class="<?php echo (strtolower($menuLevel2) == strtolower('Series')) ? ' active' : ''; ?> ">
                  <a href="<?php echo CATEGORY_MODEL_URL; ?>">
                  <i class=" fa fa-heart"></i> <span>Model Category</span>      
                  </a>                 
               </li>
               <li
                  class="<?php echo (strtolower($menuLevel2) == strtolower('Model')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_MODEL_URL; ?>">
                     <!-- <i class="fa fa-menu-arrow pull-right"></i> -->
                     <i class=" fa fa-heart"></i> <span>Model</span>
                  </a>
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Competitor') || strtolower($menuLevel2) == strtolower('loan')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_COMPETITOR_URL; ?>">
                     <!-- <i class="fa fa-menu-arrow pull-right"></i> -->
                     <i class=" fa fa fa-adjust"></i> <span>Competitor</span>
                  </a>
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('CompPart') || strtolower($menuLevel2) == strtolower('CompPart')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_ADD_COMPTITOR_PART_URL; ?>">
                     <!-- <i class="fa fa-menu-arrow pull-right"></i> -->
                     <i class=" fa fa fa-adjust"></i> <span>Competitor Part</span>
                  </a>
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Product')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_PRODUCT_URL; ?>">
                  <i class=" fa fa-list"></i> Product List</a>
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_RETAIL_URL; ?>">
                  <i class=" fa fa-list"></i> <span>Related Product</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/Company/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>Company</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/Gallery_category_m/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>Gallery Category Master</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/Gallery_subcategory_m/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>Gallery Subcategory Master</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/Gallery/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>Gallery</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/Country/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>Country</span>
                  </a>  
               </li>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Retail') || strtolower($menuLevel2) == strtolower('Retail')) ? ' active' : ''; ?>">
                  <a href="<?php echo base_url('admin/State/index/'); ?>">
                  <i class=" fa fa-list"></i> <span>State</span>
                  </a>  
               </li>
               
            </ul>
         </li>

         <li class="<?php echo (strtolower($menuLevel2) == strtolower('comp') || strtolower($menuLevel2) == 
         strtolower('Discount') || strtolower($menuLevel2) == strtolower('Sale')) ? ' active' : ''; ?> ">
            <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fas fa-percentage"></i> <span>Discount</span>
            </a>
            <ul>
              <!--  <li class="<?php echo (strtolower($menuLevel2) == strtolower('Comp')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_COMP_URL; ?>">
                  <i class="fas fa-tags"></i> <span>Competitor Discount</span>
                  </a>
               </li> -->
              <!--  <li class="<?php echo (strtolower($menuLevel2) == strtolower('Discount')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_DISCOUNT_URL; ?>">
                  <i class="fas fa-tags"></i> <span>Everest Discount</span>
                  </a>
               </li> -->
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Sale')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_SALE_URL; ?>">
                  <i class="fas fa-tags"></i> <span>Sales Discount</span>
                  </a>
               </li>
            </ul>
         </li>
         <li class="<?php echo (strtolower($menuLevel2) == strtolower('Banner') || strtolower($menuLevel2) == strtolower('Banner')) ? ' active' : ''; ?>">
            <a href="<?php echo ADMIN_BANNER_URL; ?>">
            <i class="main-icon fas fa-image"></i><span>Banner</span>
            </a>
         </li>
         <li class="<?php echo (strtolower($menuLevel2) == strtolower('Download')) ? ' active' : ''; ?>"><a href="<?php echo ADMIN_DOWNLOAD_URL; ?>">
            <i class="main-icon fas fa-file-download"></i> <span>Downloads</span></a>
         </li>
         <li class="<?php echo (strtolower($menuLevel2) == strtolower('Parameter')) ? ' active' : ''; ?>">
            <a href="<?php echo ADMIN_PARAMETER_URL; ?>">
            <i class="main-icon fas fa-search-minus"></i> 
            <span>Add Parameter</span>
            </a>
         </li>
          <li class="<?php echo (strtolower($menuLevel2) == strtolower('comp') || strtolower($menuLevel2) == 
         strtolower('Discount') || strtolower($menuLevel2) == strtolower('Sale')) ? ' active' : ''; ?> ">
            <a href="#">
            <i class="fa fa-menu-arrow pull-right"></i>
            <i class="main-icon fas fa-layer-group"></i> <span>Stock Management</span>
            </a>
            <ul>
               <li class="<?php echo (strtolower($menuLevel2) == strtolower('Comp')) ? ' active' : ''; ?> ">
                  <a href="<?php echo ADMIN_Stock_Adjustment_LIST_URL; ?>">
                  <i class="fas fa-sliders-h"></i> <span>Stock Adjustment</span>
                  </a>
               </li>

              
              
               <!--  <li class="<?php echo (strtolower($menuLevel2) == strtolower('Sale')) ? ' active' : ''; ?>">
                  <a href="<?php echo ADMIN_SALE_URL; ?>">
                  <i class="fas fa-calendar-check"></i> <span>Stock Report</span>
                  </a>
               </li> -->
            </ul>
         </li>

         <li class="<?php echo (strtolower($menuLevel2) == strtolower('Parameter')) ? ' active' : ''; ?>">
            <a href="<?php echo base_url('admin/Redeem_request/index/'); ?>">
            <i class="main-icon fas fa-search-minus"></i> 
            <span>Redeem Request</span>
            </a>
         </li>

   <?php } ?>
      </ul>
   </nav>
   <span id="asidebg">
      <!-- aside fixed background -->
   </span>
</aside>
<!-- /ASIDE