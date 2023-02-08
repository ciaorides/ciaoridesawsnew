<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-lock bg-green"></i>
                        <div class="d-inline">
                            <h5>Password  </h5>
                            <span>Change Password </span>
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

                            <li class="breadcrumb-item active" aria-current="page">Change Password </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                    echo form_open_multipart('admin/register/UpdatePassword', $attributes);
                    ?>
                    <div class="card card-default">

                        <?php if ($this->session->flashdata('success') != "") : ?>
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-block">

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Username</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="">
                                        <input type="text" class="form-control" name="username" id="username" value="<?= $row->username; ?>" readonly="readonly" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Enter Password</label>
                                <div class="col-md-6 col-xs-12">
                                    <div class="">
                                        <input type="password" class="form-control" name="password" id="password" required="" placeholder="" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <!-- <button class="btn btn-default">Clear Form</button> -->
                            <a href="<?= base_url(); ?>admin/home/index" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
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
                Password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                Password: {
                    required: "Please Enter Password"
                }
            }
        });
    });
</script>
