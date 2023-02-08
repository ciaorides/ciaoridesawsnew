<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">User Vehicles</li>
</ul>
<!-- END BREADCRUMB -->
<!-- END PAGE TITLE -->
<?php if ($this->session->flashdata('success') != "") : ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DATATABLE EXPORT -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-6 col-xs-6">
                        <h2><span class="fa fa-list"></span> User Vehicles</h2>
                    </div>

                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                        <input type="hidden" id="atpagination" value="">
                        <input type="hidden" id="paginationlength" value="">
                        <table id="users" class="table datatable table-striped">

                            <!-------- Search Form ---------->
                            <form id="fees_form" name="search_fees" method="post" class="pull-right">
                                <div class="col-md-3 col-md-offset-3" style="padding:0">
                                    <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
                                </div>
                                <div class="col-md-2">
                                    <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">
                                        <option value="1">USER NAME</option>
                                        <option value="2">COUNTRY</option>
                                        <option value="3">NUMBER PLATE</option>
                                        <option value="4">MOBILE</option>
                                    </select>
                                    <i class="fa fa-angle-down arrow-icon" id="arrow-icon"></i>
                                </div>
                                <div class="col-md-2">
                                    <select name="search_at_1" id="search_at_1" class="input-sm form-control custom-input">
                                        <option value="">Contains</option>
                                        <option value="after">Starts with</option>
                                        <option value="before">Ends with</option>
                                    </select>
                                    <i class="fa fa-angle-down arrow-icon" id="arrow-icon"></i>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="search_user" class="btn btn-info margin_search" style=""><i class="fa fa-search icon-style"></i></button>
                                    <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/register/user_vehicles'); ?>"><li class="fa fa-minus icon-style"></li></a>
                                    <!--<button type="button" id="download_user" data-id="exceldata" class="btn btn-info margin_search download" onclick="tableToExcel('users', 'W3C Example Table')"><i class="fa fa-download"></i></button>-->
                                    <button type="button" id="download_user" data-id="exceldata" class="btn btn-info download" onclick="excel()"><i class="fa fa-download"></i></button>
                                </div>
                            </form>
                            <!-------- /Search Form ---------->

                        </table>
                    </div>
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
                                        $(document).ready(function () {
                                            dtabel = $('#users').DataTable({
                                                "processing": true,
                                                "serverSide": true,
                                                "bStateSave": true,
                                                "language": {
                                                    "emptyTable": "No Records Found!",
                                                },
                                                dom: '<"html5buttons" B>lTgtp',
                                                buttons: [],
                                                "aLengthMenu": [10, 20, 50, 100],
                                                "destroy": true,
                                                "ajax": {
                                                    "url": "<?php echo base_url('admin/register/all_user_vehicles'); ?>",
                                                    "type": "POST",
                                                    beforeSend: function () {
                                                        $("#wait").css("display", "block");
                                                    },
                                                    "data": function (d) {
                                                        d.search_text_1 = search_text_1;
                                                        d.search_on_1 = search_on_1;
                                                        d.search_at_1 = search_at_1;
                                                    },
                                                    "dataSrc": function (jsondata) {
                                                        $("#wait").css("display", "none");
                                                        return jsondata['data'];
                                                    }
                                                },
                                                "columns": [
                                                    {"title": "S.No", "name": "sno", "orderable": false, "data": "sno", "width": "0%"},
                                                    {"title": "Actions", "name": "id", "orderable": false, "deafultContent": "", "data": "id", "width": "0%"},
                                                    {"title": "User Name", "name": "first_name", "orderable": false, "data": "first_name", "width": "0%"},
                                                    {"title": "  Mobile", "name": "mobile", "orderable": false, "data": "mobile",
                                                        "width": "0%"},
                                                    {"title": "number plate", "name": "number_plate", "orderable": false, "data": "number_plate", "width": "0%"},

                                                    {"title": " Vehicle Brand", "name": "make_id", "orderable": false, "data": "make_id", "width": "0%"},

                                                    {"title": " Vehicle Model", "name": "model_id", "orderable": false, "data": "model_id", "width": "0%"},
                                                    {"title": " Vehicle Type", "name": "vehicle_type", "orderable": false, "data": "vehicle_type", "width": "0%"},
                                                    {"title": "color", "name": "color", "orderable": false, "data": "color", "width": "0%"},
                                                    {"title": "reg.year of vehicle", "name": "year", "orderable": false, "data": "year", "width": "0%"},
                                                    {"title": " vehicle picture", "name": "vehicle_picture", "orderable": false, "data": "vehicle_picture", "width": "0%"},

                                                    {"title": "Created Date", "name": "created_on", "orderable": false, "data": "created_on", "width": "0%"},
                                                ],
                                                "fnCreatedRow": function (nRow, aData, iDataIndex)
                                                {
                                                    var action = '<a onclick="return confirm(' + "'Are you sure want to delete?'" + ');" class="btn btn-danger btn-condensed" title="delete" href="' + url + 'admin/register/delete_user_vehicles/' + aData['id'] + '"><i class="fa fa-trash"></i></a>';
                                                    $(nRow).find('td:eq(1)').html(action);
                                                    var image = '<img src="' + url + aData['vehicle_picture'] + '" height="50" width="50">';

                                                    $(nRow).find('td:eq(10)').html(image);

                                                    var u_name = '<a  style="color:green;font-weight:bold"   title="Click to view details" href="' + url + 'admin/register/user_details/' + aData['u_id'] + '" >' + aData['first_name'] + '</a>';
                                                    $(nRow).find('td:eq(2)').html(u_name);
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
                                            window.location.replace("<?php echo base_url('admin/register/user_vehicles_excel_export'); ?>?search_text_1=" + search_text_1 + "&&search_on_1=" + search_on_1 + "&&search_at_1=" + search_at_1 + "");

                                        }

                                        function getpagenumber()
                                        {
                                            return $("#atpagination").val() / $("#paginationlength").val();
                                        }
</script>

