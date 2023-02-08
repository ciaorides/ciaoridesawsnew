
<aside class="right-sidebar">
    <div class="sidebar-chat" data-plugin="chat-sidebar">
        <div class="sidebar-chat-info">
            <h6>Chat List</h6>
            <form class="mr-t-10">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search for friends ...">
                    <i class="ik ik-search"></i>
                </div>
            </form>
        </div>
        <div class="chat-list">
            <div class="list-group row">
                <!--                <a href="javascript:void(0)" class="list-group-item" data-chat-user="Gene Newman">
                                    <figure class="user--online">
                                        <img src="img/users/1.jpg" class="rounded-circle" alt="">
                                    </figure><span><span class="name">Gene Newman</span>  <span class="username">@gene_newman</span> </span>
                                </a>-->

            </div>
        </div>
    </div>
</aside>

<div class="chat-panel" hidden>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <a href="javascript:void(0);"><i class="ik ik-message-square text-success"></i></a>
            <span class="user-name">John Doe</span>
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="card-body">
            <div class="widget-chat-activity flex-1">
                <div class="messages">
                    <!--                    <div class="message media">
                                            <figure class="user--online">
                                                <a href="#">
                                                    <img src="img/users/1.jpg" class="rounded-circle" alt="">
                                                </a>
                                            </figure>
                                            <div class="message-body media-body">
                                                <p>Cheeseburgers make your knees weak.</p>
                                            </div>
                                        </div>
                                        <div class="message media reply">
                                            <figure class="user--offline">
                                                <a href="#">
                                                    <img src="img/users/5.jpg" class="rounded-circle" alt="">
                                                </a>
                                            </figure>
                                            <div class="message-body media-body">
                                                <p>Cheeseburgers will never let you down.</p>
                                                <p>They'll also never run around or desert you.</p>
                                            </div>
                                        </div>-->

                </div>
            </div>
        </div>
        <form action="javascript:void(0)" class="card-footer" method="post">
            <div class="d-flex justify-content-end">
                <textarea class="border-0 flex-1" rows="1" placeholder="Type your message here"></textarea>
                <button class="btn btn-icon" type="submit"><i class="ik ik-arrow-right text-success"></i></button>
            </div>
        </form>
    </div>
</div>

<footer class="footer">
    <div class="w-100 clearfix">
        <span class="text-center text-sm-left d-md-inline-block"></span>
        <span class="float-none float-sm-right mt-1 mt-sm-0 text-center">Copyright © <?= date('Y') ?> CIAO Rides  All Rights Reserved.

    </div>
</footer>

</div>
</div>




<div class="modal fade apps-modal" id="appsModal" tabindex="-1" role="dialog" aria-labelledby="appsModalLabel" aria-hidden="true" data-backdrop="false">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ik ik-x-circle"></i></button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="quick-search">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 ml-auto mr-auto">
                            <div class="input-wrap">
                                <input type="text" id="quick-search" class="form-control" placeholder="Search..." />
                                <i class="ik ik-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="container">
                    <div class="apps-wrap">
                        <div class="app-item">
                            <a href="#"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                        </div>
                        <div class="app-item dropdown">
                            <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-command"></i><span>Ui</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-mail"></i><span>Message</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-users"></i><span>Accounts</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-shopping-cart"></i><span>Sales</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-briefcase"></i><span>Purchase</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-server"></i><span>Menus</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-clipboard"></i><span>Pages</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-message-square"></i><span>Chats</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-map-pin"></i><span>Contacts</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-box"></i><span>Blocks</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-calendar"></i><span>Events</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-bell"></i><span>Notifications</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-pie-chart"></i><span>Reports</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-layers"></i><span>Tasks</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-edit"></i><span>Blogs</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-settings"></i><span>Settings</span></a>
                        </div>
                        <div class="app-item">
                            <a href="#"><i class="ik ik-more-horizontal"></i><span>More</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<audio id="audio-alert" src="<?= base_url(); ?>assets/audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="<?= base_url(); ?>assets/audio/fail.mp3" preload="auto"></audio>


<script src="<?= base_url('admin_assets/plugins/') ?>popper.js/dist/umd/popper.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>screenfull/dist/screenfull.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>jvectormap/tests/assets/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>moment/moment.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>d3/dist/d3.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>c3/c3.min.js"></script>
<script src="<?= base_url('admin_assets/plugins/') ?>chartist/dist/chartist.min.js"></script>
<script src="<?= base_url('admin_assets/') ?>js/tables.js"></script>
<script src="<?= base_url('admin_assets/') ?>js/widgets.js"></script>
<script src="<?= base_url('admin_assets/') ?>js/charts.js"></script>
<script src="<?= base_url('admin_assets/') ?>dist/js/theme.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/api/fnPagingInfo.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>admin_assets/js/jquery.toast.js"></script>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
                function () {
                    (b[l].q = b[l].q || []).push(arguments)
                });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = 'https://www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-XXXXX-X', 'auto');
    ga('send', 'pageview');
</script>

<?php 

$msg='';

$icon='';

$icon='';

if($this->session->flashdata('success')!='')

{

$msg=$this->session->flashdata('success');

$heading='Success';

$icon='success';

}

else if($this->session->flashdata('error')!=''){

$msg=$this->session->flashdata('error');

$heading='Error';

$icon='error';

}

else if(isset($error) && $error!=''){

$msg=$error;

$heading='Error';

$icon='error';

}

else if(isset($success) && $success!=''){

$msg=$success;

$icon='success';

$heading='Success';

}

?>

<script type="text/javascript">

jQuery(function($) {
    
<?php if($msg!=''){?>

    $.toast({

    heading: '<?php echo $heading;?>',

    text: '<?php echo $msg;?>',

    showHideTransition: 'fade',

    position: 'top-center',

    icon: '<?php echo $icon;?>'

    });

<?php } ?>



});

</script>
</body>
</html>
