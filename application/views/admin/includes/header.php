<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>CIAO Rides</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/img/icon.png">
        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>icon-kit/dist/css/iconkit.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>ionicons/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>perfect-scrollbar/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>datatables.net-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>jvectormap/jquery-jvectormap.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>weather-icons/css/weather-icons.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>c3/c3.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>owl.carousel/dist/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/plugins/') ?>owl.carousel/dist/assets/owl.theme.default.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/') ?>dist/css/theme.min.css">
        <link rel="stylesheet" href="<?= base_url('admin_assets/') ?>plugins/chartist/dist/chartist.min.css">
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/fontawesome/font-awesome.min.css">
        <script src="<?= base_url(); ?>assets/js/jquery-3.0.0.min.js"></script>
<!--        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script>window.jQuery || document.write('<script src="src/js/vendor/jquery-3.3.1.min.js"><\/script>')</script>-->
        <!--<script src="<?= base_url('admin_assets/') ?>src/js/vendor/modernizr-2.8.3.min.js"></script>-->
        <!--<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>-->
        <script src="<?= base_url(); ?>assets/js/tableToExcel.js"></script>
        <!--<script src="http://maps.google.com/maps/api/js?key=AIzaSyCu0v1SDu6-BkV6RaDcXaSgRnTuMJlgQY&libraries=places&region=ind&language=en&sensor=false"></script>-->
         <link rel="stylesheet" href="<?php echo base_url();?>admin_assets/css/jquery.toast.css" />
        <style>
            .pagination .page-item .page-link {
                padding: 6px 11px !important;
                -webkit-border-radius: 0px !important;
                -moz-border-radius: 0px !important;
            }
            .pagination > li > a,
            .pagination > li > span {
                margin-left: 3px;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                color: #656d78;
                border-color: #E5E5E5;
            }
            .pagination > li > a:hover,
            .pagination > li > span:hover {
                color: #222;
            }
            .pagination > li.active > a,
            .pagination > li.active > a:hover {
                background: #33414e;
                color: #FFF;
                border-color: #33414e;
            }
            .pagination.pagination-sm {
                margin: 0px;
                width: auto;
            }
            .pagination.pagination-sm.push-down-20 {
                margin-bottom: 20px;
            }
            .pagination.pagination-sm.push-up-20 {
                margin-top: 20px;
            }
            .wrapper .page-wrap .app-sidebar .sidebar-content .nav-container .navigation-main .nav-item.has-sub .submenu-content .menu-item.active {
                color: #fff  !important;
                background: #abd362 !important;
            }.wrapper .page-wrap .app-sidebar.colored .sidebar-content .nav-container .navigation-main .nav-item.open::after, .wrapper .page-wrap .app-sidebar.colored .sidebar-content .nav-container .navigation-main .nav-item.active::after {
                background-color: #11c15b  !important;
            }
        </style>
    </head>

    <body>
        <?php $uri = $this->uri->segment(3); ?>
        <div class="wrapper">
            <header class="header-top" header-theme="green">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="top-menu d-flex align-items-center">
                            <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
                            <!--                            <div class="header-search">
                                                            <div class="input-group">
                                                                <span class="input-group-addon search-close"><i class="ik ik-x"></i></span>
                                                                <input type="text" class="form-control">
                                                                <span class="input-group-addon search-btn"><i class="ik ik-search"></i></span>
                                                            </div>
                                                        </div>-->
                            <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
                        </div>
                        <div class="top-menu d-flex align-items-center">
                            <!--                            <div class="dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" id="notiDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-bell"></i><span class="badge bg-danger">3</span></a>
                                                            <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notiDropdown">
                                                                <h4 class="header">Notifications</h4>
                                                                <div class="notifications-wrap">
                                                                    <a href="#" class="media">
                                                                        <span class="d-flex">
                                                                            <i class="ik ik-check"></i>
                                                                        </span>
                                                                        <span class="media-body">
                                                                            <span class="heading-font-family media-heading">Invitation accepted</span>
                                                                            <span class="media-content">Your have been Invited ...</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="#" class="media">
                                                                        <span class="d-flex">
                                                                            <img src="img/users/1.jpg" class="rounded-circle" alt="">
                                                                        </span>
                                                                        <span class="media-body">
                                                                            <span class="heading-font-family media-heading">Steve Smith</span>
                                                                            <span class="media-content">I slowly updated projects</span>
                                                                        </span>
                                                                    </a>
                                                                    <a href="#" class="media">
                                                                        <span class="d-flex">
                                                                            <i class="ik ik-calendar"></i>
                                                                        </span>
                                                                        <span class="media-body">
                                                                            <span class="heading-font-family media-heading">To Do</span>
                                                                            <span class="media-content">Meeting with Nathan on Friday 8 AM ...</span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                                <div class="footer"><a href="javascript:void(0);">See all activity</a></div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="nav-link ml-10 right-sidebar-toggle"><i class="ik ik-message-square"></i><span class="badge bg-success">3</span></button>
                                                        <div class="dropdown">
                                                            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-plus"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right menu-grid" aria-labelledby="menuDropdown">
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Dashboard"><i class="ik ik-bar-chart-2"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Message"><i class="ik ik-mail"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Accounts"><i class="ik ik-users"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Sales"><i class="ik ik-shopping-cart"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Purchase"><i class="ik ik-briefcase"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Pages"><i class="ik ik-clipboard"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Chats"><i class="ik ik-message-square"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Contacts"><i class="ik ik-map-pin"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Blocks"><i class="ik ik-inbox"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Events"><i class="ik ik-calendar"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Notifications"><i class="ik ik-bell"></i></a>
                                                                <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="More"><i class="ik ik-more-horizontal"></i></a>
                                                            </div>
                                                        </div>-->
                            <!--<button type="button" class="nav-link ml-10" id="apps_modal_btn" data-toggle="modal" data-target="#appsModal"><i class="ik ik-grid"></i></button>-->
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img class="avatar" src="<?= base_url() ?>assets/images/logo.png" alt=""></a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
<!--                                      <li><a href="<?= base_url(); ?>admin/register/ChangePassword"><span class="fa fa-key"></span> Change Password</a></li>
          <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>-->
                                    <a class="dropdown-item" href="<?= base_url('admin/register/ChangePassword') ?>"><i class="fa fa-lock dropdown-icon"></i> Change Password</a>
                                    <a class="dropdown-item" href="<?= base_url() ?>admin/home/is_logged_out" class="mb-control"  data-box="#mb-signout"><i class="ik ik-power dropdown-icon"></i> Logout</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </header>
            <?php $this->load->view('admin/includes/leftside_menu'); ?>