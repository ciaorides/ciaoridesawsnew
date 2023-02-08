


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
                	<div class="panel-heading">
             <ul class="nav nav-tabs mb-3" >
             	<?php /*foreach($languages as $lang){?>
             <li <?php if($uri == $lang['id']){?> class="active" <?php }?> >
             	<a href="<?php echo base_url().$url.$lang['id']?>" ><?php echo $lang['language']?>  </a>
             </li>
             
             <?php }*/ ?>
             
             <li  class="nav-item"  >
             	<a class="nav-link <?php if($type == 'car'){ ?> active <?php } ?>" href="<?php echo base_url().'admin/city_management/rides/car'?>" > <b>Car</b>  </a>
             	</li>
             	<li  class="nav-item"  >
             	<a class="nav-link <?php if($type == 'bike'){ ?> active <?php } ?>" href="<?php echo base_url().'admin/city_management/rides/bike'?>" ><b>Bike </b> </a>
             	</li>
             	<li  class="nav-item"  >
             	<a class="nav-link <?php if($type == 'auto'){ ?> active <?php } ?>" href="<?php echo base_url().'admin/city_management/rides/auto'?>" ><b>Auto </b> </a>
             </li>
                     
            </ul>
       </div>

                    <div class="page-header-title">
                        <i class="fa  fa-cab bg-blue"></i>
                        <div class="d-inline">
                            <h5><?php echo $named_type;?> Rides  </h5>
                            <span>All <?php echo $named_type;?> Rides </span>
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

                            <li class="breadcrumb-item active" aria-current="page"><?php echo $named_type;?> Rides </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3><?php echo $named_type;?> Rides List</h3>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive ">
                            <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                            <input type="hidden" id="atpagination" value="">
                            <input type="hidden" id="paginationlength" value="">
                            <table id="users" class="table datatable table-striped table-bordered">

                                <!-------- Search Form ---------->
                                <form id="fees_form" name="search_fees" method="post" class="pull-right">
                                    <div class="container-fluid row" style="padding:0">
                                        <div class="col-md-3 col-md-offset-3" style="padding:0">
                                            <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">
                                                <option value="1">USER NAME</option>
                                                <option value="2">VEHICLE REG. NUMBER</option>
                                                <option value="3">TRIP ID</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="search_at_1" id="search_at_1" class="input-sm form-control custom-input">
                                                <option value="">Contains</option>
                                                <option value="after">Starts with</option>
                                                <option value="before">Ends with</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="search_user" class="btn btn-info margin_search" style=""><i class="fa fa-search icon-style"></i></button>
                                            <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/city_management/rides').'/'.$type; ?>"><li class="fa fa-minus icon-style"></li></a>
                                            <!--<button type="button" id="download_user" data-id="exceldata" class="btn btn-info margin_search download" onclick="tableToExcel('users', 'W3C Example Table')"><i class="fa fa-download"></i></button>-->
                                            <button type="button" id="download_user" data-id="exceldata" class="btn btn-info download" onclick="excel()"><i class="fa fa-download"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-------- /Search Form ---------->

                            </table>
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
                        <h2><span class="fa fa-list"></span> Rides</h2>
                    </div>

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
<script>
                                                var dtabel;
                                                var search_text_1;
                                                var search_on_1;
                                                var search_at_1;
                                                var ispage;
                                                var url = '<?php echo base_url(); ?>';
                                                //var uri_seg='<?php echo $this->uri->segment(4); ?>';
                                                var uri_seg='<?php echo $type;?>';
                                                $(document).ready(function () {
                                                    dtabel = $('#users').DataTable({
                                                        "processing": true,
                                                        "serverSide": true,
                                                        "bStateSave": true,
                                                        "language": {
                                                            "emptyTable": "No Records Found!",
                                                        },
                                                        dom: '<"html5buttons" B>lTgtp',
                                                        buttons: [

                                                        ],
                                                        "aLengthMenu": [10, 20, 50, 100],
                                                        "destroy": true,
                                                        "ajax": {
                                                            "url": "<?php echo base_url('admin/city_management/all_rides'); ?>",
                                                            "type": "POST",
                                                            beforeSend: function () {
                                                                $("#wait").css("display", "block");
                                                            },
                                                            "data": function (d) {
                                                                d.search_text_1 = search_text_1;
                                                                d.search_on_1 = search_on_1;
                                                                d.search_at_1 = search_at_1;
                                                                d.uri_seg=uri_seg;
                                                            },
                                                            "dataSrc": function (jsondata) {
                                                                $("#wait").css("display", "none");
                                                                return jsondata['data'];
                                                            }
                                                        },
                                                        "columns": [
                                                            {"title": "S.No", "name": "sno", "orderable": false, "data": "sno", "width": "0%"},
                                                            {"title": "Actions", "name": "action", "orderable": false, "deafultContent": "", "data": "id", "width": "0%", "class": "td_actionn"},

                                                            {"title": "View Details", "name": "id", "orderable": false, "deafultContent": "", "data": "id", "width": "0%"},
                                                            {"title": "Trip Id", "name": "trip_id", "orderable": false, "deafultContent": "", "data": "trip_id", "width": "0%"},
                                                            {"title": "user name", "name": "first_name", "orderable": false, "data": "first_name", "width": "0%"},
                                                            {"title": "vehicle Reg. Number", "name": " vehicle Reg. Number", "orderable": false, "data": "vehicle_number", "width": "0%"},
                                                            {"title": "vehicle type", "name": "vehicle_type", "orderable": false, "data": "vehicle_type", "width": "0%"},
                                                            {"title": "status", "name": "status", "orderable": false, "data": "status", "width": "0%"},
                                                            {"title": "Ride mode", "name": "Ride mode", "orderable": false, "data": "mode", "width": "0%"},
                                                            {"title": "trip distance", "name": "trip_distance", "orderable": false, "data": "trip_distance", "width": "0%"},
                                                            {"title": "amount per head", "name": "amount_per_head", "orderable": false, "data": "amount_per_head", "width": "0%"},
                                                            {"title": "Created Date", "name": "created_on", "orderable": false, "data": "created_on", "width": "0%"},
                                                            {"title": "Modified Date", "name": "modified_on", "orderable": false, "data": "modified_on", "width": "0%"}
                                                        ],
                                                        "fnCreatedRow": function (nRow, aData, iDataIndex)
                                                        {
                                                            var action = '<a onclick="return confirm(' + "'Are you sure want to delete?'" + ');" class="btn btn-danger btn-condensed" title="delete" href="' + url + 'admin/register/delete_rides/' + aData['id'] + '"><i class="fa fa-trash"></i></a>';
                                                            $(nRow).find('td:eq(1)').html(action);
                                                            var status = '<a class="btn btn-warning btn-condensed" title="" href="' + url + 'admin/register/ride_details/' + aData['id'] + '" class="btn btn-success btn-condensed">View</a>';
                                                            $(nRow).find('td:eq(2)').html(status);



                                                            var u_name = '<a  style="color:green;font-weight:bold" title="Click to view details" href="' + url + 'admin/register/user_details/' + aData['user_id'] + '" >' + aData['first_name'] + '</a>';
                                                            $(nRow).find('td:eq(4)').html(u_name);


                                                        },
                                                        "fnDrawCallback": function (oSettings) {
                                                            var info = this.fnPagingInfo().iPage;
                                                            $("#atpagination").val(info + 1);
                                                            $("td:empty").html("&nbsp;");
                                                        },
                                                    });
                                                    $("#search_user").click(function () {
                                                        if ($("#search_text_1").val() != "") {
                                                            $("#search_text_1").css('background', '#ffffff');
                                                            setallvalues();
                                                            dtabel.draw();
                                                        } else {
                                                            $("#search_text_1").css('background', '#ffb3b3');
                                                            $("#search_text_1").focus();
                                                            return false;
                                                        }
                                                    });
                                                });

                                                function setallvalues() {
                                                    search_text_1 = $("#search_text_1").val();
                                                    search_on_1 = $("#search_on_1").val();
                                                    search_at_1 = $("#search_at_1").val();
                                                    var table = $('#users').DataTable();
                                                    var info = table.page.info();
                                                    $("#atpagination").val((info.page + 1));
                                                    if (search_text_1 != "") {
                                                        $("#searchreset").show();
                                                    }
                                                    searchAstr = '';
                                                }

                                                function excel() {
                                                    var search_text_1 = $('#search_text_1').val();
                                                    var search_on_1 = $('#search_on_1').val();
                                                    var search_at_1 = $('#search_at_1').val();
                                                    window.location.replace("<?php echo base_url('admin/register/rides_excel_export'); ?>?search_text_1=" + search_text_1 + "&&search_on_1=" + search_on_1 + "&&search_at_1=" + search_at_1 + "");

                                                }

                                                function getpagenumber()
                                                {
                                                    return $("#atpagination").val() / $("#paginationlength").val();
                                                }
</script>

