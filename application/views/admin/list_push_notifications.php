

<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-message-circle bg-green"></i>
                        <div class="d-inline">
                            <h5>Push Notifications  </h5>
                            <span>Push Notifications </span>
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
                            <li class="breadcrumb-item active" aria-current="page"> Push Notifications </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('success') != "") : ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-12">
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                            echo form_open_multipart('admin/register/send_push_notification', $attributes);
                            ?>
                            <div class="form-group">

                                <label for="title" class="col-md-12 col-xs-12 control-label">Enter Title</label>

                                <div class="col-md-6 col-xs-12">

                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>

                                    <input type="text" class="form-control" name="title" id="title" />

                                </div>

                            </div>


                            <div class="form-group">

                                <label for="title" class="col-md-3 col-xs-12 control-label">Enter Description</label>

                                <div class="col-md-6 col-xs-12">
                                    <textarea class="form-control" name="description" id="description" required="" rows="5" /></textarea>
                                    <br>
                                    <button type="submit" class="btn btn-primary pull-right">Submit</button>

                                </div>

                            </div>
                            <div class="form-group">

                                <div class="col-md-12 col-xs-12 text-left">
                                    <!-- <button class="btn btn-default">Clear Form</button> -->
                                    <!-- <button class="add_field_button btn btn-primary">Add More Stops</button> -->
                                    <!--<button type="submit" class="btn btn-primary pull-right">Submit</button>-->

                                </div>

                            </div>

                            </form>

                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?= base_url(); ?>assets/img/demo_wait.gif' width="64" height="64" /></div>
                            <input type="hidden" id="atpagination" value="">
                            <input type="hidden" id="paginationlength" value="">
                            <table  class="table datatable table-striped table-bordered">
                                <tr>
                                    <th>S.no</th>
                                    <th> Title</th>
                                    <th> Description </th>
                                    <th> Date</th>
                                    <th> Action</th>
                                </tr>
                                <?php
                                $i = 1;
                                foreach ($items as $item) {
                                    ?>
                                    <tr>
                                        <td> <?= $i ?></td>
                                        <td> <?= $item['title'] ?></td>
                                        <td> <?= $item['description'] ?></td>
                                        <td> <?= $item['created_on'] ?></td>
                                        <td><a href="<?= base_url('admin/register/push_notifications_delete/' . $item['id']) ?>" class="btn btn-md btn-danger" onclick="return confirm('Are you sure?')" >Delete</a> </td>
                                    </tr>

                                    <?php
                                    $i++;
                                }
                                ?>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- END PAGE CONTENT WRAPPER -->
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-validate-additional-methods.js"></script>
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
                                                    "url": "<?php echo base_url('admin/register/all_push_notifications'); ?>",
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
                                                    {"title": "S.No", "name": "sno", "orderable": true, "data": "sno", "width": "0%"},
                                                    //{ "title": "Action", "name":"Status", "orderable": false, "deafultContent":"", "data": "Status", "width":"0%"},
                                                    //{ "title": "Name", "name":"Name","orderable": false, "data":"Name", "width":"0%" },
                                                    //{ "title": "Phone", "name":"Phone","orderable": false, "data":"Phone", "width":"0%" },
                                                    //{ "title": "EmailID", "name":"EmailID","orderable": false, "data":"EmailID", "width":"0%" },
                                                    //{ "title": "User ID", "name":"UserID","orderable": false, "data":"UserID", "width":"0%" },
                                                    {"title": "Title", "name": "title", "orderable": false, "data": "title", "width": "0%"},
                                                    {"title": "Description", "name": "description", "orderable": false, "data": "description", "width": "0%"},
                                                    {"title": "Sent date", "name": "Sent date", "orderable": false, "data": "created_on", "width": "0%"},
                                                    {"title": "Action", "name": "action", "orderable": false, "data": "action", "width": "0%"},
                                                            //{ "title": "Modified Date", "name":"ModifiedOn","orderable": false, "data":"ModifiedOn", "width":"0%" },
                                                ],
                                                "fnCreatedRow": function (nRow, aData, iDataIndex)
                                                {
                                                    var action = ' <a onclick="return confirm(' + "'Are you sure want to delete?'" + ');" class="btn btn-danger btn-condensed" title="delete" href="' + url + 'admin/register/delete_otherusers/' + aData['id'] + '"><i class="fa fa-trash"></i><a class="btn btn-warning btn-condensed" title="View" href="' + url + 'admin/register/user_details/' + aData['id'] + '" class="btn btn-success btn-condensed"><i class="fa fa-eye"></i></a></a>';
                                                    $(nRow).find('td:eq(1)').html(action);
                                                    // var Image = aData['Image'];
                                                    // var imgTag = '<img src="' +url+ Image + '"style="height:50px;width:50px;"/>';
                                                    // $(nRow).find('td:eq(5)').html(imgTag);
                                                    // if(aData['Status'] == "ACTIVE")
                                                    // {
                                                    //   var Status ='<a href="'+url+'admin/register/Userstatus/INACTIVE/'+aData['UserID']+'" class="btn btn-default">Active</button>';
                                                    // }
                                                    // else
                                                    // {
                                                    //   var Status ='<a href="'+url+'admin/register/Userstatus/ACTIVE/'+aData['UserID']+'" class="btn btn-default">In Active</button>';
                                                    // }
                                                    // $(nRow).find('td:eq(1)').html(Status);

                                                    //   var Restaurant ='<a href="'+url+'admin/register/RestaurantsList/'+aData['UserID']+'" class="btn btn-default">View</button>';
                                                    // $(nRow).find('td:eq(2)').html(Restaurant);
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
                                            window.location.replace("<?php echo base_url('admin/register/push_notifications_excel_export'); ?>?search_text_1=" + search_text_1 + "&&search_on_1=" + search_on_1 + "&&search_at_1=" + search_at_1 + "");

                                        }


                                        function getpagenumber()
                                        {
                                            return $("#atpagination").val() / $("#paginationlength").val();
                                        }
</script>
