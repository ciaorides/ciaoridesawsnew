<script src="https://recsakp.com/hr/admin_assets/plugins/chart.js/Chart.bundle.min.js"></script>
<link rel="stylesheet" href="https://recsakp.com/hr/admin_assets/plugins/chartist/css/chartist.min.css">
<link rel="stylesheet" href="https://recsakp.com/hr/admin_assets/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
<div class="main-content">
    <div class="container-fluid">
        <div class="row clearfix">

            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url(); ?>admin/register/all_users">
                    <div class="widget">


                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Active Users</h6>
                                    <h2 style="font-size:20px"><?= $active_users_count; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="ik ik-users"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">6% higher than last month</small>-->
                        </div>

                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100" style="width: 62%;"></div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url(); ?>admin/register/inactiveusers">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Inactive Users</h6>
                                    <h2 style="font-size:20px"><?= $inactive_users_count; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="ik ik-user-x"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">61% higher than last month</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100" style="width: 78%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <a href="<?= base_url(); ?>admin/register/rides">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Rides</h6>
                                    <h2 style="font-size:20px"><?= $rides_count; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-car"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Events</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100" style="width: 31%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!--all_bookings-->
                <a href="<?= base_url(); ?>admin/register/all_bookings">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>All Bookings</h6>
                                    <h2 style="font-size:20px"><?= $orders_count; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cab"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Comments</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!--all_bookings-->
                <a href="#">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Bookings Completed</h6>
                                    <h2 style="font-size:20px"><?= $orders_count_completed; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cab"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Comments</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!--all_bookings-->
                <a href="#">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Bookings Accepted</h6>
                                    <h2 style="font-size:20px"><?= $orders_count_accepted; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cab"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Comments</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!--all_bookings-->
                <a href="#">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Bookings Cancelled By User</h6>
                                    <h2 style="font-size:20px"><?= $orders_count_can_us; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cab"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Comments</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
                <!--all_bookings-->
                <a href="#">
                    <div class="widget">
                        <div class="widget-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="state">
                                    <h6>Bookings Cancelled By  Rider</h6>
                                    <h2 style="font-size:20px"><?= $orders_count_can_rider; ?></h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-cab"></i>
                                </div>
                            </div>
                            <!--<small class="text-small mt-10 d-block">Total Comments</small>-->
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!--BoOKINGS-->
        <form method="get">
            <div class="row">
                <div  class="col-lg-12 col-md-12 col-sm-12 form-group">
                    <h2>Date Range </h2>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 form-group">
                    <input type="date" class="form-control"  name="from_date" value="<?= (!empty($this->input->get_post('from_date')) && $this->input->get_post('from_date') !== null) ? $this->input->get_post('from_date') : '' ?>">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 form-group">
                    <input type="date" class="form-control" name="to_date"  value="<?= (!empty($this->input->get_post('to_date')) && $this->input->get_post('to_date') !== null) ? $this->input->get_post('to_date') : '' ?>">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 form-group">
                    <input type="submit" class="btn btn-md btn-success" >
                </div>

            </div>
        </form>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h3> Bookings </h3>
            </div>


            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-green">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>All Rides</h6>
                                <h2 style="font-size:20px"><?= $stat_data->total_rides ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-car"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-green">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>All Bookings</h6>
                                <h2 style="font-size:20px"><?= $stat_data->total_bookings ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cab"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-facebook">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Amount</h6>
                                <h2 style="font-size:20px"> <?= number_format($stat_data->total_amount, 2) ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-inr"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-danger">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Rider Payment</h6>
                                <h2 style="font-size:20px"><?= number_format($stat_data->total_rider_commission, 2) ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-car"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-facebook">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>CIAO Payments</h6>
                                <h2 style="font-size:20px"><?= number_format($stat_data->total_ciao_commission, 2) ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-inr"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="widget bg-danger">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Tax + Gateway Fee</h6>
                                <h2 style="font-size:20px"><?= number_format($stat_data->total_tax, 2) ?> + <?= number_format($stat_data->total_payment_gateway, 2) ?></h2>
                            </div>
                            <div class="icon">
                                <i class="fa fa-inr"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">

                    <div class="card-block">
                        <!-- Bar Chart -->
                        <div class="row">
                            <div class="col-lg-6">
                                <canvas id="barChart" width="500" height="500"></canvas>
                            </div>
                            <div class="col-lg-6">
                                <canvas id="singelBarChart" width="500" height="500"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>

            //bar chart
            var current_type_data = {"labels": ["All Rides", "All Bookings"], "counts": [<?= $stat_data->total_rides ?>,<?= $stat_data->total_bookings ?>]};
            var ctx = document.getElementById("barChart");
            ctx.height = 250;
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: current_type_data.labels,
                    datasets: [
                        {
                            label: "Rides  & Bookings ",
                            data: current_type_data.counts,
                            borderColor: "rgba(117, 113, 249, 0.9)",
                            borderWidth: "0",
                            backgroundColor: ["#26c281", "#26c281", "#3b579d", "#e8c3b9", "#f5365c"],
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                        xAxes: [{
                                // Change here
                                barPercentage: 0.2
                            }]
                    }
                }
            });

            var employee_type_data = {"labels": ["Total Amount", "Rider Payment", "CIAO Payments", "TAX & Gateway Fee"], "counts": [<?= $stat_data->total_amount ?>, <?= $stat_data->total_rider_commission ?>, <?= $stat_data->total_ciao_commission ?>, <?= $stat_data->total_tax + $stat_data->total_payment_gateway ?>]};

            console.log('labb : ' + employee_type_data.labels);
//    console.log(employee_type_data);
            // single bar chart
            var ctx = document.getElementById("singelBarChart");
            ctx.height = 250;
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: employee_type_data.labels,
                    datasets: [
                        {
                            label: "Amount Calculations",
                            data: employee_type_data.counts,
                            borderColor: "rgba(127, 113,249, 0.9)",
                            borderWidth: "0",
                            backgroundColor: ["#3b579d", "#f5365c", "#3b579d", "#f5365c", "#f5365c"],
                        },
                    ]
                },
                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    },
                }
            });
        </script>
    </div>
    <form method="get">
        <div class="row">
            <div  class="col-lg-12 col-md-12 col-sm-12 form-group">
                <h2>Search User </h2>
            </div>

            <div class="col-lg-4 col-md-3 col-sm-3 form-group">
                <input type="text" class="form-control" placeholder="Search with Mobile Number "  name="search_string" value="<?= (!empty($this->input->get_post('search_string')) && $this->input->get_post('search_string') !== null) ? $this->input->get_post('search_string') : '' ?>">
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 form-group" style="margin-top:4px;">
                <input type="submit" value="Search" class="btn btn-md btn-success" >
            </div>

        </div>
    </form>
    <div class="row">
        <div  class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="card">
                <?php
//                    echo '<pre>';
//                    print_r($user_data);
//                    echo '</pre>'
                ?>
                <?php
                if (!empty($user_data)) {
                    $user_info = $user_data['info'];
                    $bookings = $user_data['bookings'];
                    $rides = $user_data['rides'];
                }
                ?>
                <!--<div class="card-header"><h3>Basic Information</h3></div>-->
                <div class="card-body">
                    <h3>User Details</h3>
                    <table class="table table-striped table-striped table-bordered">
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Dob</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Profile Percentage</th>

                        </tr>
                        <?php if (!empty($user_info)) { ?>
                            <tr>

                                <th><a target="_blank" href="<?= base_url('admin/register/user_details/' . $user_info->id) ?>" class="text-green"><?= $user_info->id ?></a></th>
                                <th><?= ucwords($user_info->first_name) . ' ' . ucwords($user_info->last_name) ?></th>
                                <th><?= $user_info->email_id ?></th>
                                <th><?= $user_info->mobile ?></th>
                                <th><?= date('d-m-Y', strtotime($user_info->dob)) ?> <br>(DD-MM-YYYY)</th>
                                <th><?= get_age($user_info->dob) ?> </th>

                                <th><?= ucwords($user_info->gender) ?></th>
                                <th><?= $user_info->address1 ?><br><?= $user_info->address2 ?></th>
                                <th>
                                    <?php
                                    $percentage = 0;
                                    if ($user_info->aadhar_card_verified == 'yes') {
                                        $percentage += 40;
                                    }
                                    if ($user_info->address_verified == 'yes') {
                                        $percentage += 20;
                                    }
                                    if ($user_info->mobile_verified == 'yes') {
                                        $percentage += 10;
                                    }
                                    if ($user_info->photo_verified == 'yes') {
                                        $percentage += 10;
                                    }

                                    if ($user_info->email_id_verified == 'yes') {
                                        $percentage += 10;
                                    }

                                    if ($user_info->office_email_id_verified == 'yes') {
                                        $percentage += 10;
                                    }
                                    ?> <p class=" fw-700"><?= $percentage ?>%<span class="text-green ml-10"></span></p>
                                    <div class="progress" style="height:4px">
                                        <div class="progress-bar bg-green" style="width:<?= $percentage ?>%"></div>
                                    </div>
                                </th>
                            </tr>
                        <?php } ?>
                    </table>
                    <h3>Booking List</h3>
                    <table class="table table-striped table-striped table-bordered">

                        <tr>
                            <th>BOOKING ID</th>
                            <th> Rider Name</th>
                            <th> MODE</th>
                            <th> Vehicle Type</th>
                            <th> Seats</th>
                            <th> Ride  Type</th>
                            <th>AMOUNT</th>
                            <th>PAYMENT STATUS</th>
                            <th>CREATED DATE</th>
                            <th>STATUS</th>
                        </tr>
                        <?php
                        if (!empty($bookings)) { // register/order_details/3437
                            foreach ($bookings as $item) {
                                ?>
                                <tr>
                                    <th>
                                        <a target="_blank"  href="<?= base_url('admin/register/order_details/' . $item->id) ?>" class="text-green"> <?= $item->booking_id ?> </a>

                                    </th>
                                    <th><?php
                                        if (!empty($item->rider_id)) {
                                            $u = $this->db->where('id', $item->rider_id)->get('users')->row();
                                            echo ' <a target="_blank"  href="' . base_url('admin/register/user_details/' . $u->id) . '" class="text-blue">' . ucwords($u->first_name) . ' ' . ucwords($u->last_name) . '</a>';
                                        }
                                        ?>
                                    </th>
                                    <th><?= $item->mode ?></th>
                                    <th><?= $item->vehicle_type ?></th>
                                    <th><?= $item->seats_required ?></th>
                                    <th><?= $item->ride_type ?></th>
                                    <th><?= $item->total_amount ?></th>

                                    <th><?= $item->payment_status ?></th>
                                    <th><?= $item->created_on ?></th>
                                    <th><?= $item->status ?></th>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <hr>
                    <h3>Rides List</h3>

                    <table class="table table-striped table-striped table-bordered">
                        <tr>
                            <th>TRIP ID &nbsp;&nbsp;&nbsp;	</th>
                            <th> Rider Name</th>


                            <th> MODE</th>
                            <th>VEHICLE TYPE</th>
                            <th> Seats</th>
                            <th> Ride  Type</th>
                            <th>TRIP DISTANCE</th>
                            <th>AMOUNT PER HEAD</th>
                            <th>CREATED DATE</th>
                            <th>STATUS</th>
                        </tr>
                        <?php
                        if (!empty($rides)) {
                            foreach ($rides as $item) {
                                ?>
                                <tr>
                                    <th>
                                        <a target="_blank"  href="<?= base_url('admin/register/ride_details/' . $item->id) ?>" class="text-green"> <?= $item->trip_id ?> </a>
                                    </th>
                                    <th>
                                        <a target="_blank" href="<?= base_url('admin/register/user_details/' . $user_info->id) ?>" class="text-blue"><?= ucwords($user_info->first_name) . ' ' . ucwords($user_info->last_name) ?></a>
                                    </th>
                                    <th><?= $item->mode ?></th>
                                    <th><?= $item->vehicle_type ?></th>


                                    <th><?= $item->seats ?></th>
                                    <th><?= $item->ride_type ?></th>
                                    <th><?= $item->trip_distance ?></th>
                                    <th><?= $item->amount_per_head ?></th>
                                    <th><?= $item->created_on ?></th>
                                    <th><?= $item->status ?></th>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

