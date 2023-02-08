<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="images/favicon.png" rel="icon" />
        <title>Invoice | CIAO Rides </title>

        <!-- Web Fonts
        ======================= -->
        <!-- <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Stylesheet
        ======================= -->
        <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/website/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/website/font-awesome_css_all.min.css"/>
        <link href="<?= base_url(); ?>assets/website/css/fontawesome-all.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/website/stylesheet.css"/> -->
        <style type="text/css">
            *, ::after, ::before {
                box-sizing: border-box;
            }
            .row {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
            }
            .t-sm-left {
                text-align: left !important;
            }
            img {
                vertical-align: inherit;
                border-style: none;
            }

            .col-sm-5 {
                -ms-flex: 0 0 41.666667%;
                flex: 0 0 41.666667%;
                max-width: 41.666667%;
            }
            .mb-0, .my-0 {
                margin-bottom: 0 !important;
            }
            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }
            p {
                line-height: 1.9;
            }
            h1, h2, h3, h4, h5, h6 {
                color: #0c2f54;
                /*font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";*/
            }
            .h4, h4 {
                font-size: 1.5rem;
            }
            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
                margin-bottom: .5rem;
                font-weight: 500;
                line-height: 1.2;
            }
            h1, h2, h3, h4, h5, h6 {
                margin-top: 0;
                margin-bottom: .5rem;
            }

            .mt-3, .my-3 {
                margin-top: 1rem !important;
            }
            article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
                display: block;
            }
            hr {
                margin-top: 1rem;
                margin-bottom: 1rem;
                border: 0;
                border-top-color: currentcolor;
                border-top-style: none;
                border-top-width: 0px;
                border-top: 1px solid rgba(0,0,0,.1);
                box-sizing: content-box;
                height: 0;
                overflow: visible;
            }
            article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
                display: block;
            }

            .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
                position: relative;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
            }
            .col-lg-12 {
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
            body {
                margin: 0;
                /*font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";*/
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
            }

            .container-fluid {
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
                margin-right: auto;
                margin-left: auto;
            }
            .invoice-container {
                margin: 15px auto;
                padding: 70px;
                max-width: 850px;
                background-color: #fff;
                border: 1px solid #ccc;
                -moz-border-radius: 6px;
                -webkit-border-radius: 6px;
                -o-border-radius: 6px;
                border-radius: 6px;
            }
            article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
                display: block;
            }
            a, a:focus {
                color: #0071cc;
                -webkit-transition: all 0.2s ease;
                transition: all 0.2s ease;
            }
            a {
                color: #007bff;
                text-decoration: none;
                background-color: transparent;
            }
            .table-bordered td, .table-bordered th {
                border: 1px solid #dee2e6;
            }
            .table-sm td, .table-sm th {
                padding: .3rem;
            }
            .table td, .table th {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }
            .font-weight-600 {
                font-weight: 600 !important;
            }
            .text-right {
                text-align: right !important;
            }
            .col-4 {
                -ms-flex: 0 0 33.333333%;
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
            .table-responsive > .table-bordered {
                border: 0;
            }
            .table {
                color: #535b61;
            }
            .table-bordered {
                border: 1px solid #dee2e6;
            }
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }
            table {
                border-collapse: collapse;
            }
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .col-sm-3 {
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%;
            }
            .col-sm-4 {
                -ms-flex: 0 0 33.333333%;
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
            .text-3 {
                font-size: 16px !important;
                font-size: 1rem !important;
            }
            .font-weight-500 {
                font-weight: 500 !important;
            }
            .text-uppercase {
                text-transform: uppercase !important;
            }
            .text-black-50 {
                /*color: rgba(0,0,0,.5) !important;*/
            }
            .align-items-center {
                -ms-flex-align: center !important;
                align-items: center !important;
            }
            .col-sm-7 {
                -ms-flex: 0 0 58.333333%;
                flex: 0 0 58.333333%;
                max-width: 58.333333%;
            }
            .mb-3, .my-3 {
                margin-bottom: 1rem !important;
            }
            .mb-sm-0, .my-sm-0 {
                margin-bottom: 0 !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-sm-left {
                text-align: left !important;
            }
            .text-sm-right {
                text-align: right !important;
            }
            body {
                background: #e7e9ed;
                color: #535b61;
                /*font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";*/
                font-size: 14px;
                line-height: 22px;
            }
            :root {
                --blue: #007bff;
                --indigo: #6610f2;
                --purple: #6f42c1;
                --pink: #e83e8c;
                --red: #dc3545;
                --orange: #fd7e14;
                --yellow: #ffc107;
                --green: #28a745;
                --teal: #20c997;
                --cyan: #17a2b8;
                --white: #fff;
                --gray: #6c757d;
                --gray-dark: #343a40;
                --primary: #007bff;
                --secondary: #6c757d;
                --success: #28a745;
                --info: #17a2b8;
                --warning: #ffc107;
                --danger: #dc3545;
                --light: #f8f9fa;
                --dark: #343a40;
                --breakpoint-xs: 0;
                --breakpoint-sm: 576px;
                --breakpoint-md: 768px;
                --breakpoint-lg: 992px;
                --breakpoint-xl: 1200px;
                /*--font-family-sans-serif: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                --font-family-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;*/
            }
            html {
                font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                line-height: 1.15;
                -webkit-text-size-adjust: 100%;
                font-style: normal;
                font-weight: 900;
                /*src: local('Poppins Black'), local('Poppins-Black'), url(https://fonts.gstatic.com/s/poppins/v9/pxiByp8kv8JHgFVrLBT5Z1xlFQ.woff2) format('woff2');
                unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;*/
            }
            *, ::after, ::before {
                box-sizing: border-box;
            }

        </style>

    </head>
    <body>

        <!-- Container -->
        <div class="container-fluid invoice-container" style="
             margin: 15px auto;
             padding: 70px;
             max-width: 850px;
             background-color: #fff;
             border: 1px solid #ccc;
             -moz-border-radius: 6px;
             -webkit-border-radius: 6px;
             -o-border-radius: 6px;
             border-radius: 6px;
             ">
            <!-- Header -->
            <header>
                <div class="row align-items-center" style="align-items: center !important;">
                    <div class="col-sm-7 text-center text-sm-left mb-3 mb-sm-0"> <img id="logo" src="<?= base_url(); ?>assets/images/logo.png" title="CIAO Rides" alt="CIAO Rides" height="120" width="120" /> </div>
                    <div class="col-sm-5 text-center text-sm-right">
                        <h4 class="mb-0">Invoice</h4>
                        <p class="mb-0"><span class="text-black-50">Invoice Number - <?= $details['booking_id']; ?></span></p>
                    </div>
                </div>
                <hr class="my-3">
            </header>

            <!-- Main Content -->
            <main>
                <div class="row">
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Rider User ID:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['r_userid']; ?></span></div>
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Rider First Name:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['r_first_name']; ?></span></div>
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Rider Last Name:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['r_last_name']; ?></span></div>
                    <div class="col-sm-3"> <span class="text-black-50 text-uppercase">Rider Ratings:</span><br>
                        <span class="font-weight-600 text-3">
                            <span class="font-weight-600 text-3">
                                <?php
                                echo $details['ratings'];
                                for ($i = 1; $i <= round($details['ratings']); $i++) {
                                    ?>
                                    <span class="fa fa-star checked" ></span>
                                    <?php
                                }
                                ?>
                            </span>
                        </span> </div>
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Make & Model:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['vehicle_model']; ?> - <?= $details['vehicle_make']; ?></span></div>
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase"><?= ucfirst($details['vehicle_type']); ?> Type:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['car_type'] ? $details['car_type'] : "-"; ?></span></div>
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Color - Year:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['color']; ?> - <?= $details['year']; ?></span> </div>
                    <div class="col-sm-3 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Car Regn. No:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['number_plate']; ?></span> </div>
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="col-sm-4 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Ride Start Time:</span><br>
                        <span class="font-weight-600 text-3"><?= date("d-M-Y h:i A", strtotime($details['ride_start_time'])); ?></span> </div>
                    <div class="col-sm-4 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Ride End Time:</span><br>
                        <span class="font-weight-600 text-3"><?= date("d-M-Y h:i A", strtotime($details['ride_end_time'])); ?></span> </div>
                      <div class="col-sm-4"> <span class="text-black-50 text-uppercase">Total Time<!-- <small>(s)</small> -->:</span><br>
                        <?php
                        $date1 = strtotime($details['ride_start_time']);
                        $date2 = strtotime($details['ride_end_time']);
                        $diff = abs($date2 - $date1);
                        $years = floor($diff / (365 * 60 * 60 * 24));
                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
                                $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
// printf("%d years, %d months, %d days, %d hours, "
//   . "%d minutes, %d seconds", $years, $months,
//       $days, $hours, $minutes, $seconds);
                        ?>

                        <span class="font-weight-600 text-3"><?php printf("%d days, %d hours, %d minutes, %d seconds", $days, $hours, $minutes, $seconds); ?> </span> </div>
                </div>
                <hr class="my-3">
                <div class="row">
                    <div class="col-sm-4 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Trip Distance <small>(KM)</small>:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['trip_distance']; ?></span> </div>
                    <div class="col-sm-4 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Travel Mode:</span><br>
                        <span class="font-weight-600 text-3"><?= $details['mode']; ?></span> </div>
                    <div class="col-sm-4 mb-3 mb-sm-0"> <span class="text-black-50 text-uppercase">Ride Type:</span><br>
                        <span class="font-weight-600 text-3"><?php
                            if ($details['ride_type'] == "now") {
                                echo "Instant";
                            } else {
                                echo "Scheduled";
                            }
                            ?></span> </div>
                      <!-- <div class="col-sm-3"> <span class="text-black-50 text-uppercase">Security Deposit:</span><br>
                        <span class="font-weight-600 text-3">$5000</span> </div> -->
                </div>
                <hr class="my-3">
                <div class="table-responsive d-print-none">

                    <table class="table table-bordered table-sm">
                        <tbody>
                            <tr>
                                <td class="col-4 text-right font-weight-600" style="width: 35%"><span class="font-weight-600 text-black-50">Passenger User ID:</span></td>
                                <td class="col-8"><span class="text-black-50"><?= $details['userid']; ?> </span></td>
                            </tr>
                            <tr>
                                <td class="col-4 text-right font-weight-600" style="width: 35%"><span class="font-weight-600 text-black-50">Passenger Name:</span></td>
                                <td class="col-8"><span class="text-black-50"><?= $details['first_name']; ?> <?= $details['last_name']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Mobile Number:</span></td>
                                <td><span class="text-black-50"><?= $details['mobile']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Email ID:</span></td>
                                <td><span class="text-black-50"><?= $details['email_id']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Gender:</span></td>
                                <td><span class="text-black-50"><?php echo($details['gender'] == "men") ? "Male" : "Female"; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Date of Birth:</span></td>
                                <td><span class="text-black-50"><?= date("d-M-Y", strtotime($details['dob'])); ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Address1:</span></td>
                                <td><span class="text-black-50"><?= $details['address1']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Address2:</span></td>
                                <td><span class="text-black-50"><?= $details['address2']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Ride Source Address:</span></td>
                                <td><span class="text-black-50"><?= $details['from_address']; ?></span></td>
                            </tr>
                            <tr>
                                <td class="text-right font-weight-600"><span class="font-weight-600 text-black-50">Ride Destination Address:</span></td>
                                <td><span class="text-black-50"><?= $details['to_address']; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card">
                    <div class="card-header py-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <td class="col-6 border-0 font-weight-600" style="width: 50%"><span class="text-black-50">Rate Sheet</span></td>
                                    <td class="col-3 text-right border-0 font-weight-600"><span class="text-black-50">Rate</span></td>
                                    <td class="col-3 text-right border-0 font-weight-600"><span class="text-black-50">Amount</span></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="col-6 border-0"><span class="text-black-50">Amount</span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['amount'] - $details['base_fare']; ?></span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['amount'] - $details['base_fare']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-6 border-0"><span class="text-black-50">Base Fare</span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['base_fare']; ?></span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['base_fare']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="col-6 border-0"><span class="text-black-50">Booking Charges</span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['payment_gateway_commision'] + $details['ciao_commission']; ?></span></td>
                                        <td class="col-3 text-right border-0"><span class="text-black-50"><?= $details['payment_gateway_commision'] + $details['ciao_commission']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-light-2 text-right"><strong><span class="text-black-50">Sub Total:</span></strong></td>
                                        <td class="bg-light-2 text-right"><span class="text-black-50"><?= $details['amount'] + $details['payment_gateway_commision'] + $details['ciao_commission']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-light-2 text-right"><strong><span class="text-black-50">Tax:</span></strong></td>
                                        <td class="bg-light-2 text-right"><span class="text-black-50"><?= $details['tax']; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="bg-light-2 text-right"><strong><span class="text-black-50">Total:</span></strong></td>
                                        <td class="bg-light-2 text-right"><span class="text-black-50"><?= $details['total_amount']; ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Footer -->
            <footer class="text-center mt-4">
                <p><strong><span class="text-black-50">About CIAO Rides</span></strong><br>
                    <span class="text-black-50">CIAO Rides is a Car/Bike Ride Sharing application for both In-Station  and Out-Station rides. <br> Happy to serve you always.</span></p>
                <hr>
                <!-- Note -->
                <p class="text-1"><strong><span class="text-black-50">NOTE :</span></strong> <span class="text-black-50">This is computer generated receipt and does not require physical signature.</span></p>
                <!-- Button -->
                <!-- <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print</a> <a href="" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-download"></i> Download</a> </div> -->

                <p class="text-center d-print-none"><span class="text-black-50">Website: <a href="https://www.ciaorides.com" target="_blank">www.ciaorides.com</a> | Email ID: <a href="mailto:support@ciaorides.com"> support@ciaorides.com </a></span></p>

                <div class="col-lg-12">
                    <?php
                    $social = $this->db->get('socia_media_links')->result();
                    foreach ($social as $s) {
                        ?>
                        <a href="<?= $s->link ?>" target="_blank"> <i class="fa <?= $s->image ?> fa-2x" style="color:<?= $s->color ?>"></i></a>
                    <?php }
                    ?>


                </div>
            </footer>
        </div>
        <!-- Back to My Account Link -->
    </body>
    <style type="text/css">
        .fab
        {
            font-size: 22px;
        }
    </style>
</html>