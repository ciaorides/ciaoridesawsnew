<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-user bg-green"></i>
                        <div class="d-inline">
                            <h5>Update Vehicle Models </h5>
                            <span>Edit Vehicle Models </span>
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
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('admin/vehicle_models') ?>">Vehicle Models</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">Edit Vehicle Models</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                    echo form_open_multipart('admin/register/update_vehicle_models', $attributes);
                    ?>
                    <div class="card card-default">

                        <?php if ($this->session->flashdata('success') != "") : ?>
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-block">

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Vehicle Types</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="vehicle_type" id="vehicle_type" required="" onchange="get_vehicle_brands(this.value)">
                                        <option value="">Select Vehicle Type</option>
                                        <option value="car" <?php echo ($row->vehicle_type == "car") ? "selected" : ""; ?>>Car</option>
                                        <option value="bike" <?php echo ($row->vehicle_type == "bike") ? "selected" : ""; ?>>Bike</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Vehicle Brands</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="make_id" id="make_id">
                                        <option value="">Select Vehicle Brand</option>
                                        <?php
                                        if (!empty($categories)) {
                                            foreach ($categories as $category) {
                                                ?>
                                                <option value="<?= $category->id; ?>" <?php echo ($category->id == $row->make_id) ? "selected" : ""; ?>><?= $category->title; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Enter Title</label>
                                <div class="col-md-6 col-xs-12">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" class="form-control" name="title" id="title" value="<?= $row->title; ?>" />
                                </div>
                            </div>


                        </div>

                        <input type="hidden" name="sub_category_id" value="<?= $row->id; ?>">

                        <div class="card-footer">
                            <!-- <button class="btn btn-default">Clear Form</button> -->
                            <a href="<?= base_url(); ?>admin/register/vehicle_models" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-validate-additional-methods.js"></script>
<script>
                                        $(document).ready(function () { //alert();
                                            $("#validation").validate({
                                                rules: {
                                                    // simple rule, converted to {required:true}
                                                    category_id: {
                                                        required: true,
                                                    },
                                                    category1_id: {
                                                        required: true,
                                                    },
                                                    title: {
                                                        required: true,
                                                    },
                                                    gender: {
                                                        required: true,
                                                    }
                                                },
                                                submitHandler: function (form) {
                                                    $("#btnSubmit").prop('disabled', true);
                                                    form.submit();
                                                }
                                            });

                                        });

                                        function get_vehicle_brands(vehicle_type)
                                        {
                                            //alert(vehicle_type);exit;
                                            $.ajax({
                                                type: 'post',
                                                url: '<?= base_url(); ?>admin/register/get_vehicle_brands',
                                                data: {vehicle_type: vehicle_type},
                                                beforeSend: function (xhr) {
                                                    xhr.overrideMimeType("text/plain; charset=x-user-defined");
                                                    $("#wait").css("display", "block");
                                                },
                                                success: function (data) { //alert(data);
                                                    $("#make_id").html(data);
                                                    $("#wait").css("display", "none");
                                                }
                                            });
                                        }
</script>
