<?php $uri = $this->uri->segment(3); ?>
<div class="page-wrap">
    <div class="app-sidebar colored">
        <div class="sidebar-header text-center">
            <a class="header-brand" href="<?= base_url('admin'); ?>">
                <div class="logo-img">
                    <img src="<?= base_url(''); ?>admin_assets/ciao.jpg" class="header-brand-img" alt="" style="width:48px;"><br>
                </div>
                <span class="text">&nbsp;&nbsp; CIAO Rides</span>
            </a>
            <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
            <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
        </div>

        <div class="sidebar-content">
            <div class="nav-container">
                <nav id="main-menu-navigation" class="navigation-main">
                    <div class="nav-lavel">Navigation</div>

                <?php if(!empty($roleResponsible['dashboard'])){ ?>
                    <div class="nav-item <?= ($uri == "" || $uri == 'index') ? 'active' : '' ?>">
                        <a href="<?= base_url('admin/') ?>"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                    </div>
                <?php }?>

 <?php if((!empty($roleResponsible['taxi_drivers'])) || (!empty($roleResponsible['passengers'])) || (!empty($roleResponsible['private_drivers'])) || (!empty($roleResponsible['inactive_users'])) ){ ?>    
                    <div class="nav-lavel">User Management</div>
                    <div class="nav-item has-sub active open <?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="ik ik-users"></i><span>Users Management </span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['taxi_drivers'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-users  active "></span>&nbsp;&nbsp; Taxi Drivers</a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['passengers'])){ ?>
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-users  active"></span>&nbsp;&nbsp; Passengers </a>

                            <?php }?>
                            <?php if(!empty($roleResponsible['private_drivers'])){ ?>
                            <a href="" class="menu-item   <?= ($uri == 'user_feedback' ) ? 'active' : '' ?>"> <span class="fa fa-users  active"></span>&nbsp;&nbsp; Private Drivers</a>  
                            <?php }?>
                            <?php if(!empty($roleResponsible['inactive_users'])){ ?>
                           <a href="" class="menu-item   <?= ($uri == 'user_feedback' ) ? 'active' : '' ?>"> <span class="fa fa-users  active"></span>&nbsp;&nbsp; Inactive Users</a>
                            <?php }?>
                        </div>
                    </div>
                  <?php }?>  
            <?php if((!empty($roleResponsible['vehicle_models'])) || (!empty($roleResponsible['vehicle_brands']))  ) { ?> 
                 <div class="nav-lavel">Vehicle Management</div>
                    <div class="nav-item has-sub  active open<?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="fa fa-car"></i><span>Vehicle Management</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['vehicle_models'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Brands</a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['vehicle_brands'])){ ?>
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Models </a>
                            <?php }?>
                       
                        </div>
                    </div>  
            <?php }?>
        
         <?php if((!empty($roleResponsible['city_rides'])) || (!empty($roleResponsible['city_bookings']))  || (!empty($roleResponsible['city_cancellation'])) || (!empty($roleResponsible['city_refunds'])) ) { ?> 
                    <div class="nav-lavel">City Management</div>
                    <div class="nav-item has-sub  active open<?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="fa fa-car"></i><span>City Management</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['city_rides'])){ ?>
                            <a href="<?php echo base_url().'admin/city_management/rides/car'?>" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Rides (Car, Auto & Bike) </a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['city_bookings'])){ ?>
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Bookings (Car, Auto & Bike) </a>  
                            <?php }?>
                            <?php if(!empty($roleResponsible['city_cancellation'])){ ?>
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Cancellation (Car, Auto & Bike) </a>  
                            <?php }?>
                            <?php if(!empty($roleResponsible['city_refunds'])){ ?> 
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Refunds (Car, Auto & Bike) </a>
                            <?php }?>
                       
                        </div>
                    </div>
                    <?php }?>

                <?php if((!empty($roleResponsible['taxi_rides'])) || (!empty($roleResponsible['taxi_bookings']))  || (!empty($roleResponsible['taxi_cancellation'])) || (!empty($roleResponsible['taxi_refunds'])) ) { ?> 
                 <div class="nav-lavel">Intercity Management</div>
                    <div class="nav-item has-sub active open <?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="fa fa-car"></i><span>Intercity Management</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['taxi_rides'])){ ?> 
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Taxi Rides</a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_bookings'])){ ?> 
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Taxi Bookings </a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_cancellation'])){ ?>   
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Taxi Cancellation </a> 
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_refunds'])){ ?>   
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Taxi Refunds </a> 
                            <?php }?>
                        
                       
                        </div>
                    </div>
                    <?php }?>
<?php if((!empty($roleResponsible['sharing_rides'])) || (!empty($roleResponsible['sharing_bookings']))  || (!empty($roleResponsible['sharing_cancellation'])) || (!empty($roleResponsible['sharing_refunds'])) ) { ?> 
                   <div class="nav-lavel">Sharing Management</div>
                      <div class="nav-item has-sub active open <?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="fa fa-car"></i><span>Sharing Management</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['taxi_refunds'])){ ?>  

                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Rides (Car & Bike)</a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_refunds'])){ ?>  
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Bookings (Car & Bike) </a>  
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_refunds'])){ ?>  
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Cancellation (Car & Bike)s </a>  
                            <?php }?>
                            <?php if(!empty($roleResponsible['taxi_refunds'])){ ?>   
                            <a href="" class="menu-item   <?= ($uri == 'user_bank_details' ) ? 'active' : '' ?>"><span class="fa fa-car  active"></span>&nbsp;&nbsp; Refunds (Car & Bike)</a> 
                            <?php }?>
                        
                       
                        </div>
                    </div>
                    <?php }?>
                               
 <?php if((!empty($roleResponsible['roles'])) || (!empty($roleResponsible['employees']))  ) { ?> 

         <div class="nav-lavel">Roles & Responsibilites</div>
                      <div class="nav-item has-sub  active open<?= ($uri == 'roles' || $uri == "add_roles" || $uri == "edit_roles" || $uri == "view_roles" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="fa fa-lock"></i><span>Roles & Responsibilites</span></a>
                        <div class="submenu-content">
                            
                            <?php if(!empty($roleResponsible['roles'])){ ?>
                            <a href="<?php echo base_url() ?>admin/roles/list_roles" class="menu-item  <?= ($uri == 'roles' || $uri == "list_roles" || $uri == "edit_roles" || $uri == "view_roles") ? 'active' : '' ?>"><span class="fa fa-lock  active "></span>&nbsp;&nbsp; Roles List</a>
                             <?php }?>

                            <?php if(!empty($roleResponsible['employees'])){ ?>
                            <a href="<?php echo base_url() ?>admin/roles/employees" class="menu-item   <?= ($uri == 'employees' || $uri == "add_employees" || $uri == "edit_employees" || $uri == "view_employee") ? 'active' : '' ?>"><span class="fa fa-users  active"></span>&nbsp;&nbsp;Employees  </a>
                             <?php }?>
                               
                      
                        </div>
        </div>
                          
<?php }?>
                
            <?php if((!empty($roleResponsible['roles'])) || (!empty($roleResponsible['employees']))  ) { ?>    
                  <div class="nav-lavel">Amount Calculations</div>
                      <div class="nav-item has-sub  active open<?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="ik ik-dollar-sign"></i><span>Amount Calculations</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['roles'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp;Taxi Car, Bike & Auto</a>
                            <?php }?>
                            <?php if(!empty($roleResponsible['employees'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Private Sharing Car & Bike</a>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
                    
            <?php if((!empty($roleResponsible['passenger_feedbacks'])) || (!empty($roleResponsible['private_driver_feedbacks'])) || (!empty($roleResponsible['taxi_driver_feedbacks'])) || (!empty($roleResponsible['inactive_user_feedbacks']))  ) { ?>  
                 <div class="nav-lavel">Feedbacks & Ratings</div>
                      <div class="nav-item has-sub active open <?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="ik ik-package"></i><span> Feedbacks & Ratings</span></a>
                        <div class="submenu-content">
                             <?php if(!empty($roleResponsible['taxi_driver_feedbacks'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp;Taxi Drivers </a>
                            <?php }?>
                             <?php if(!empty($roleResponsible['passenger_feedbacks'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Passengers</a>
                            <?php }?>
                             <?php if(!empty($roleResponsible['private_driver_feedbacks'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Private Drivers</a>
                            <?php }?>
                             <?php if(!empty($roleResponsible['inactive_user_feedbacks'])){ ?>
                            <a href="" class="menu-item  <?= ($uri == 'all_users' ) ? 'active' : '' ?>"><span class="fa fa-car  active "></span>&nbsp;&nbsp; Inactive users</a>
                            <?php }?>
                        </div>
                    </div>
                     <?php }?>

        <?php if((!empty($roleResponsible['push_notifications'])) || (!empty($roleResponsible['emergency_contacts'])) || (!empty($roleResponsible['admin_chart'])) || (!empty($roleResponsible['support']))  ) { ?>  

           <div class="nav-lavel">Others</div>
                      <div class="nav-item has-sub  active open <?= ($uri == 'all_users' || $uri == "user_bank_details" || $uri == "user_feedback" || $uri == "user_ratings" ) ? 'active open' : '' ?>">
                        <a href="javascript:void(0)"><i class="ik ik-command"></i><span>Others</span></a>
                        <div class="submenu-content">
                            <?php if(!empty($roleResponsible['push_notifications'])){ ?>
                  