<!DOCTYPE html>
<html lang="en">

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- META SECTION -->
        <title>CIAO Rides</title>

        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/img/icon.png">
        <!-- END META SECTION -->

        <!-- CSS INCLUDE -->
        <link rel="stylesheet" type="text/css" id="theme" href="<?= base_url(); ?>assets/css/theme-default.css"/>
        <script src="<?= base_url(); ?>assets/js/jquery-3.0.0.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/tableToExcel.js"></script>
        <script src="http://maps.google.com/maps/api/js?key=AIzaSyC_u0v1SDu6-BkV6RaDcXaSgRnTuMJlgQY&libraries=places&region=ind&language=en&sensor=false"></script>

        <!-- EOF CSS INCLUDE -->
        <style type="text/css">
            .alert-error, .error {
                color: #ff0000;
            }
            .alert-success {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            .x-navigation.x-navigation-horizontal li.user{
                padding: 10px;
                line-height: 30px;
                color: #fff;
                font-weight: bold;
            }

        </style>
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">

            <div class="page-sidebar">
                <ul class="x-navigation">
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
                            </style>             <img src="<?= base_url(); ?>assets/images/logo.png" alt="Logo"/></a>
                        <div class="profile">
                            <div class="profile-image"><img src="<?= base_url(); ?>assets/images/logo.png" alt="Logo"/>
                            </div>
                        </div>
                    </li>
                    <?php $uri = $this->uri->segment(3); ?>
                    <li class="xn-title">Navigation</li>
                    <li <?php
                    if ($uri == "index"): echo "class='active'";
                    endif;
                    ?>>

                        <?php echo anchor('admin/home/index', '<span class="fa fa-tachometer"></span> <span class="xn-text"> Dashboard</span>'); ?>

                    </li>
                    <li class="xn-openable <?php
                    if ($uri == "otherusers" || $uri == "add_otherusers" || $uri == "edit_otherusers" || $uri == "user_bank_details" || $uri == "add_user_bank_details" || $uri == "edit_user_bank_details" || $uri == "user_feedback" || $uri == "add_user_feedback" || $uri == "edit_user_feedback" || $uri == "user_ratings" || $uri == "add_user_ratings" || $uri == "edit_user_ratings" || $uri == "user_vehicles" || $uri == "add_user_vehicles" || $uri == "edit_user_vehicles"): echo "active";
                    endif;
                    ?>">
                        <a href="#"><span class="fa fa-bars"></span> User Details </a>
                        <ul>

                            <li <?php
                            if ($uri == "otherusers"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/otherusers', '<span class="fa fa-users"></span> <span class="xn-text"> Users</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "user_bank_details"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/user_bank_details', '<span class="fa fa-university"></span> <span class="xn-text"> User bank details</span>'); ?>
                            </li>

                            <li <?php
                            if ($uri == "user_feedback"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/user_feedback', '<span class="fa fa-comment"></span> <span class="xn-text"> User feedback</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "user_ratings"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/user_ratings', '<span class="fa fa-star-half-o"></span> <span class="xn-text"> User Ratings</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "user_vehicles"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/user_vehicles', '<span class="fa fa-car"></span> <span class="xn-text"> User Vehicles</span>'); ?>
                            </li>



                        </ul>

                    <li class="xn-openable <?php
                    if ($uri == "vehicle_makes" || $uri == "add_vehicle_makes" || $uri == "edit_vehicle_makes" || $uri == "vehicle_models" || $uri == "add_vehicle_models" || $uri == "edit_vehicle_models"): echo "active";
                    endif;
                    ?>">

                        <a href="#"><span class="fa fa-bars"></span> Brands & Models</a>

                        <ul>


                            <li <?php
                            if ($uri == "vehicle_makes"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/vehicle_makes', '<span class="fa fa-car"></span> <span class="xn-text"> Vehicle Brands</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "vehicle_models"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/vehicle_models', '<span class="fa fa-car"></span> <span class="xn-text"> Vehicle Models</span>'); ?>
                            </li>


                        </ul>

                    </li>

                    </li>
                    <li <?php
                    if ($uri == "amount_calculations"): echo "class='active'";
                    endif;
                    ?>>
                            <?php echo anchor('admin/register/amount_calculations', '<span class="fa fa-calculator"></span> <span class="xn-text"> Amount Calculations</span>'); ?>
                    </li>

                    <!--  <li <?php
                    if ($uri == "chats"): echo "class='active'";
                    endif;
                    ?>>
                    <?php echo anchor('admin/register/chats', '<span class="fa fa-commenting"></span> <span class="xn-text"> Chats</span>'); ?>
                  </li>  -->

                    <li <?php
                    if ($uri == "emergency_contacts"): echo "class='active'";
                    endif;
                    ?>>
                            <?php echo anchor('admin/register/emergency_contacts', '<span class="fa fa-phone-square"></span> <span class="xn-text"> Emergency Contacts</span>'); ?>
                    </li>
                    <!--    <li <?php
                    if ($uri == "favourite_locations"): echo "class='active'";
                    endif;
                    ?>>
                    <?php echo anchor('admin/register/favourite_locations', '<span class="fa fa-star"></span> <span class="xn-text"> Favourite locations</span>'); ?>
               </li> -->
                    <li <?php
                    if ($uri == "push_notifications"): echo "class='active'";
                    endif;
                    ?>>
                            <?php echo anchor('admin/register/push_notifications', '<span class="fa fa-bell"></span> <span class="xn-text">Push Notifications</span>'); ?>
                    </li>

                    <!--
                               <li <?php
                    if ($uri == "rider_current_location"): echo "class='active'";
                    endif;
                    ?>>
                    <?php echo anchor('admin/register/rider_current_location', '<span class="fa fa-location-arrow"></span> <span class="xn-text"> Rider Current Location</span>'); ?>
                              </li>   -->

                    <li class="xn-openable <?php
                    if ($uri == "rides" || $uri == "ride_details" || $uri == "ongoing" || $uri == "ongoing_details" || $uri == "completed" || $uri == "completed_details" || $uri == "shceduled" || $uri == "shceduled_details"): echo "active";
                    endif;
                    ?>">

                        <a href="#"><span class="fa fa-bars"></span> Rides</a>

                        <ul>

                            <li <?php
                            if ($uri == "rides"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/rides', '<span class="fa fa-car"></span> <span class="xn-text">All Rides</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "ongoing"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/ongoing', '<span class="fa fa-car"></span> <span class="xn-text"> Ongoing Rides</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "completed"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/completed', '<span class="fa fa-car"></span> <span class="xn-text"> Completed</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "shceduled"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/shceduled', '<span class="fa fa-car"></span> <span class="xn-text"> Scheduled</span>'); ?>
                            </li>
                            <!-- <li <?php
                            if ($uri == "outstation"): echo "class='active'";
                            endif;
                            ?>>
                            <?php echo anchor('admin/register/outstation', '<span class="fa fa-car"></span> <span class="xn-text"> Outstation</span>'); ?>
                            </li> -->
                        </ul>

                    </li>

                    <!--  <li <?php
                    if ($uri == "ride_lat_lngs"): echo "class='active'";
                    endif;
                    ?>>
                    <?php echo anchor('admin/register/ride_lat_lngs', '<span class="fa fa-compass"></span> <span class="xn-text"> Ride Lat Lngs</span>'); ?>
                    </li> -->


                    <li class="xn-openable <?php
                    if ($uri == "otherorders" || $uri == "order_details" || $uri == "cancelled_user" || $uri == "cancelled_user_details" || $uri == "cancelled_rider" || $uri == "cancelled_rider_details" || $uri == "order_details" || $uri == "instantorders"): echo "active";
                    endif;
                    ?>">

                        <a href="#"><span class="fa fa-bars"></span> All Bookings</a>

                        <ul>


                            <li <?php
                            if ($uri == "instantorders"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/instantorders', '<span class="fa fa-book"></span> <span class="xn-text"> Instant Bookings</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "otherorders"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/otherorders', '<span class="fa fa-book"></span> <span class="xn-text"> Scheduled Bookings</span>'); ?>
                            </li>





                            <li <?php
                            if ($uri == "cancelled_user"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/cancelled_user', '<span class="fa fa-book"></span> <span class="xn-text"> Cancelled by user</span>'); ?>
                            </li>
                            <li <?php
                            if ($uri == "cancelled_rider"): echo "class='active'";
                            endif;
                            ?>>
                                    <?php echo anchor('admin/register/cancelled_rider', '<span class="fa fa-book"></span> <span class="xn-text"> Cancelled by rider</span>'); ?>
                            </li>
                        </ul>

                    </li>
                    <!-- <li <?php
                    if ($uri == "user_payments"): echo "class='active'";
                    endif;
                    ?>>
                    <?php echo anchor('admin/register/user_payments', '<span class="fa fa-credit-card"></span> <span class="xn-text"> User Payments</span>'); ?>
                                                                                    </li> -->

                    <!-- <li <?php
                    if ($uri == "user_refunds"): echo "class='active'";
                    endif;
                    echo anchor('admin/register/user_refunds', '<span class="fa fa-credit-card"></span> <span class="xn-text"> User Refunds</span>');
                    ?>
          </li> -->

                    <li <?php
                    if ($uri == "rider_pending_payments"): echo "class='active'";
                    endif;
                    ?>>
                            <?php echo anchor('admin/register/rider_pending_payments', '<span class="fa fa-credit-card"></span> <span class="xn-text"> Rider Pending Payments</span>'); ?>
                    </li>
                    <li <?php
                    if ($uri == "rider_paid_payments"): echo "class='active'";
                    endif;
                    ?>>
                            <?php echo anchor('admin/register/rider_paid_payments', '<span class="fa fa-credit-card"></span> <span class="xn-text"> Rider Paid Payments</span>'); ?>
                    </li>

                </ul>

                </li>


                </ul>
                </li>
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

            <!-- PAGE CONTENT -->
            <div class="page-content">
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->

                    <!-- POWER OFF -->
                    <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="<?= base_url(); ?>admin/register/ChangePassword"><span class="fa fa-key"></span> Change Password</a></li>
                            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>
                    </li>

                    <!-- <li class="xn-icon-button pull-right">
                      <a href="<?= base_url(); ?>admin/register/vendor_delete_requests" title="Vendor Delete Requests"><span class="fa fa-comments"></span></a>
                      <div class="informer informer-danger" id="delete_request_counts" style="display: none"></div>
                    </li> -->
                </ul>

                <!-- END X-NAVIGATION VERTICAL -->

    <!-- <script type="text/javascript">
      $(document).ready(function()
      {
        window.setInterval(function(){
          $.ajax( {
            type: 'POST',
            url: "<?= base_url(); ?>admin/register/get_venue_delete_requests_count",
            //data: {category_id:category_id},
            beforeSend: function( xhr ) {
                xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
                //$("#wait").css("display", "block");
            },
            success: function(data) { //alert(data);exit;
              var present_count = $("#delete_request_counts").html();
              //alert(present_count);
              if(data > present_count)
              {
                document.getElementById('audio-alert').play();
              }
              if(data > 0)
              {
                $("#delete_request_counts").show().html(data);
              }
              //$("#wait").css("display", "none");
              //location.reload();
              return false;
            }
          });

        }, 50);
      });
    </script> -->

