<!--<div class="main-content">
    <div class="container-fluid">
        <div class="row clearfix">-->
<?php if ($this->session->flashdata('success') != "") : ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
<!-- PAGE CONTENT WRAPPER -->
<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-inr bg-blue"></i>
                        <div class="d-inline">
                            <h5>Amount Calculations  </h5>
                            <span>Total Amount calculations </span>
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

                            <li class="breadcrumb-item active" aria-current="page">Amount Calculations </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Amount Calculations</h3>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                            <input type="hidden" id="atpagination" value="">
                            <input type="hidden" id="paginationlength" value="">
                            <table id="users" class="table datatable table-striped">

                                <!-------- Search Form ---------->
                                <!-- <form id="fees_form" name="search_fees" method="post" class="pull-right">
                                  <div class="col-md-3 col-md-offset-3" style="padding:0">
                                      <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
                                  </div>
                                  <div class="col-md-2">
                                      <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">
                                          <option value="1">Title</option>
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
                                  <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/register/amount_calculations'); ?>"><li class="fa fa-minus icon-style"></li></a>
                                  </div>
                                </form> -->
                                <!--<button type="button" id="download_user" data-id="exceldata" class="btn btn-info margin_search download" onclick="tableToExcel('users', 'W3C Example Table')"><i class="fa fa-download"></i></button>-->
                                <input type="hidden" name="search_text_1" id="search_text_1">
                                <input type="hidden" name="search_on_1" id="search_on_1">
                                <input type="hidden" name="search_at_1" id="search_at_1">
                                <!-- <button type="button" id="download_user" data-id="exceldata" class="btn btn-info download" onclick="excel()"><i class="fa fa-download"></i></button><br> -->
                                <!-------- /Search Form ---------->

                            </table>
                        </div>

                    </div>
                </div>

            </div>
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
                "url": "<?php echo base_url('admin/register/all_amount_calculations'); ?>",
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
                {"title": "Actions", "name": "action", "orderable": false, "deafultContent": "", "data": "id", "width": "0%", "class": "td_action"},
                //{ "title": "Per Km", "name":"per_km","orderable": false, "data":"per_km", "width":"0%" },
                {"title": "Travel Type", "name": "travel_type", "orderable": false, "data": "travel_type", "width": "0%"},
                // { "title": "1 to 25", "name":"1_to_25","orderable": false, "data":"1_to_25", "width":"0%" },
                // { "title": "26 to 50", "name":"26_to_50","orderable": false, "data":"26_to_50", "width":"0%" },
                // { "title": "51 to 100", "name":"51_to_100","orderable": false, "data":"51_to_100", "width":"0%" },
                // { "title": "101 to 300", "name":"101_to_300","orderable": false, "data":"101_to_300", "width":"0%" },
                // { "title": "301 to 500", "name":"301_to_500","orderable": false, "data":"301_to_500", "width":"0%" },
                // { "title": "Greater than 500", "name":"greater_than_500","orderable": false, "data":"greater_than_500", "width":"0%" },
                {"title": "Vehicle Type", "name": "vehicle_type", "orderable": false, "data": "vehicle_type", "width": "0%"},
                {"title": "Service tax", "name": "service_tax", "orderable": false, "data": "service_tax", "width": "0%"},
                {"title": "Created Date", "name": "created_on", "orderable": false, "data": "created_on", "width": "0%"},
                {"title": "Modified Date", "name": "modified_on", "orderable": false, "data": "modified_on", "width": "0%"}
            ],
            "fnCreatedRow": function (nRow, aData, iDataIndex)
            {

                var image = '<img src="' + url + aData['icon'] + '" height="50" width="50">';
                //$(nRow).find('td:eq(2)').html(image);

                var action = '<a class="btn btn-warning btn-condensed" title="edit" href="' + url + 'admin/register/edit_amount_calculations/' + aData['id'] + '"><i class="fa fa-edit"></i></a>';
                $(nRow).find('td:eq(1)').html(action);
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
        window.location.replace("<?php echo base_url('admin/register/amount_calculations_excel_export'); ?>?search_text_1=" + search_text_1 + "&&search_on_1=" + search_on_1 + "&&search_at_1=" + search_at_1 + "");

    }

    function getpagenumber()
    {
        return $("#atpagination").val() / $("#paginationlength").val();
    }
</script>