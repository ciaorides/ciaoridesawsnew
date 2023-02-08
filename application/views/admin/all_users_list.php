<style>
    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
    }

    .pagination a.active {
        background-color: dodgerblue;
        color: white;
    }

    .pagination a:hover:not(.active) {background-color: #ddd;}
</style>

<?php if ($this->session->flashdata('success') != "") : ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-users bg-green"></i>
                        <div class="d-inline">
                            <h5>Users  </h5>
                            <span>All Users </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0)"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('admin') ?>">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page"> All Users </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!--                    <div class="card-header">
                                            <h3>Users List</h3>
                                        </div>-->
                    <div class="card-block">
                        <div class="table-responsive">
                            <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                            <input type="hidden" id="atpagination" value="">
                            <input type="hidden" id="paginationlength" value="">
                            <table id="users" class="table datatable table-striped table-bordered">
                                <!-------- Search Form ---------->
                                <form id="" name="search_fees" method="post" class="pull-right">
                                    <div class="container-fluid row">
                                        <div class="col-md-3 col-md-offset-3" style="padding:0">
                                            <input type="text" name="search_string" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
                                        </div>

                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-info margin_search" style=""><i class="fa fa-search icon-style"></i></button>
                                            <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/register/otherusers'); ?>"><li class="fa fa-minus icon-style"></li></a>
                                            <!--<button type="button" id="download_user" data-id="exceldata" class="btn btn-info margin_search download" onclick="tableToExcel('users', 'W3C Example Table')"><i class="fa fa-download"></i></button>-->
                                            <button type="button" id="download_user" data-id="exceldata" class="btn btn-info download" onclick="excel()"><i class="fa fa-download"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-------- /Search Form ---------->

                            </table>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Mobile</th>
                                            <th>Email Id</th>
                                            <th>Created Date</th>
                                            <th>Modified Date</th>
                                            <th>Status</th>
                                            <th>Profile %</th>
                                            <th>Admin Chat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $no = 1;
                                        foreach ($photo as $row) {
                                            if ($no == 1) {
//                                                echo '<pre>';
//                                                print_r($row);
//                                                echo '</pre>';
                                            }
                                            ?>

                                            <tr>
                                                <td> <?php echo $no; ?> </td>
                                                <td> <?php echo ucwords(strtolower($row->first_name)); ?> </td>
                                                <td> <?php echo ucwords(strtolower($row->last_name)); ?> </td>
                                                <td> <?php echo $row->mobile; ?> </td>
                                                <td> <?php echo $row->email_id; ?> </td>
                                                <td> <?php echo $row->created_on; ?> </td>
                                                <td> <?php echo $row->modified_on; ?> </td>
                                                <!--<td> <?php //if($row->status == 1){echo "active";} else {echo "inactive";}                                                                              ?> </td>-->
                                                <td>
                                                    <?php if ($row->status == 1) { ?>

                                                        <a href="<?php echo base_url() ?>admin/register/change_user_status/<?php echo $row->id; ?>/0"><button class="btn btn-success">Active</button></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url() ?>admin/register/change_user_status/<?php echo $row->id; ?>/1"><button class="btn btn-danger">Inactive</button></a>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
//                                Govt ID (40%), Address (20%), Mobile (10%), Photo (10%), personal Mail ID (10%) and Official Mail ID(10%)
                                                    $percentage = 0;
                                                    if ($row->aadhar_card_verified == 'yes') {
                                                        $percentage += 40;
                                                    }
                                                    if ($row->address_verified == 'yes') {
                                                        $percentage += 20;
                                                    }
                                                    if ($row->mobile_verified == 'yes') {
                                                        $percentage += 10;
                                                    }
                                                    if ($row->photo_verified == 'yes') {
                                                        $percentage += 10;
                                                    }

                                                    if ($row->email_id_verified == 'yes') {
                                                        $percentage += 10;
                                                    }

                                                    if ($row->office_email_id_verified == 'yes') {
                                                        $percentage += 10;
                                                    }
                                                    ?>
                                                    <br>
                                                    <p class=" fw-700"><?= $percentage ?>%<span class="text-green ml-10"></span></p>
                                                    <div class="progress" style="height:4px">
                                                        <div class="progress-bar bg-green" style="width:<?= $percentage ?>%"></div>
                                                    </div>

                                                </td>
                                                <td>
                                                    <?php
                                                    $n_c = $this->db->where(['user_id' => $row->id, 'type' => 'user', 'is_scean' => 'no'])->get('co_chat')->num_rows();
                                                    if ($n_c > 0) {
                                                        ?>
                                                        <i class="fa fa-bell text-danger"></i><sup style="color: #fff;background: green;padding: 1px 4px;border-radius: 23px;margin-left: -3px;font-size: 9px"><?= $n_c ?></sup>

                                                    <?php } else { ?>
                                                        <i class="fa fa-bell-o text-danger"></i>
                                                    <?php } ?>
                                                    <a  href="<?= base_url('admin/home/ciao_view/' . $row->id) ?>"  class="btn btn-outline-primary"><i class="fa fa-comment"></i>&nbsp;Chat </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>admin/register/user_details/<?php echo $row->id; ?>" class="btn btn-outline-success"> View </a> &nbsp; &nbsp;
                                                    <a  href="<?= base_url('register/delete_otherusers/' . $row->id) ?>"  onclick="return confirm('Are you sure?')"  class="btn btn-outline-danger">Delete</a> </td>

                                                </td>
                                            </tr>

                                            <?php
                                            $no++;
                                        }
                                        ?>

                                    </tbody>
                                </table>

                                <div class="col-md-12 pagination text-right">
                                    <nav aria-label="Page navigation">
                                        <?= $pagination ?>
                                    </nav>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DATATABLE EXPORT -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-6 col-xs-6">
                        <h2><span class="fa fa-list"></span> Users</h2>
                    </div>
                    <!-- <div class="col-md-6 col-xs-6">
                      <h3 class="panel-title" style="float: right;"><a href="<?= base_url(); ?>admin/register/add_otherusers" class="btn btn-success">Add</a></h3>
                    </div> -->
                </div>
                <div class="panel-body">

                </div>
            </div>
            <!-- END DATATABLE EXPORT -->
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script src="<?php echo base_url(); ?>assets/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/datatables/dataTables_custom.js" type="text/javascript"></script>
<!--Load JQuery-->
<script src="<?php echo base_url(); ?>assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatables/dataTables.bootstrap.min.js"></script>


