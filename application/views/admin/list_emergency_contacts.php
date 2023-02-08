
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
                        <i class="fa  fa-inr bg-blue"></i>
                        <div class="d-inline">
                            <h5> Emergency Contacts  </h5>
                            <span>All Emergency Contacts List</span>
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

                            <li class="breadcrumb-item active" aria-current="page">Emergency Contacts  </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-block">
                        <div class="table-responsive">
                            <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                            <input type="hidden" id="atpagination" value="">
                            <input type="hidden" id="paginationlength" value="">
                            <table id="users" class="table datatable table-striped">

                                <!-------- Search Form ---------->
                                <form id="fees_form" name="search_fees" method="post" class="pull-right">
                                    <div class="container-fluid row" style="padding:0">
                                        <div class="col-md-3 col-md-offset-3" style="padding:0">
                                            <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">
                                                <option value="1">USER NAME</option>
                                                <option value="2">NAME</option>
                                                <option value="3">MOBILE</option>
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
                                            <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/register/emergency_contacts'); ?>"><li class="fa fa-minus icon-style"></li></a>
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
                                                            "url": "<?php echo base_url('admin/register/all_emergency_contacts'); ?>",
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
                                                            {"title": "Actions", "name": "action", "orderable": false, "deafultContent": "", "data": "id", "width": "0%", "class": "td_actionn"},
                                                            {"title": "User name", "name": "id", "orderable": false, "deafultContent": "", "data": "first_name", "width": "0%"},

                                                            /* { "title": "Mobile No", "name":"id", "orderable": false, "deafultContent":"", "data": "umobile", "width":"0%"},*/
                                                            {"title": "Contact Name", "name": "name", "orderable": false, "data": "name", "width": "0%"},
                                                            {"title": "Mobile No", "name": "Emergency mobile No", "orderable": false, "data": "mobile", "width": "0%"},
                                                            {"title": "Relation", "name": "relation", "orderable": false, "data": "relation", "width": "0%"},
                                                            {"title": "Created Date", "name": "created_on", "orderable": false, "data": "created_on",
                                                                "width": "0%"},
                                                        ],
                                                        "fnCreatedRow": function (nRow, aData, iDataIndex)
                                                        {
                                                            var action = '<a onclick="return confirm(' + "'Are you sure want to delete?'" + ');" class="btn btn-danger btn-condensed" title="delete" href="' + url + 'admin/register/delete_emergency_contacts/' + aData['id'] + '"><i class="fa fa-trash"></i></a>';
                                                            $(nRow).find('td:eq(1)').html(action);
                                                            var status = aData['first_name'] + ' ' + aData['last_name'];
                                                            $(nRow).find('td:eq(2)').html(status);

                                                            var u_name = '<a   style="color:green;font-weight:bold" title="Click to view details" href="' + url + 'admin/register/user_details/' + aData['u_id'] + '" >' + aData['first_name'] + ' ' + aData['last_name'] + '</a>';
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
                                                    window.location.replace("<?php echo base_url('admin/register/emergency_contacts_excel_export'); ?>?search_text_1=" + search_text_1 + "&&search_on_1=" + search_on_1 + "&&search_at_1=" + search_at_1 + "");

                                                }

                                                function getpagenumber()
                                                {
                                                    return $("#atpagination").val() / $("#paginationlength").val();
                                                }
</script>

