<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-user bg-green"></i>
                        <div class="d-inline">
                            <h5>Update </h5>
                            <span>Update User Details</span>
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
                            <li class="breadcrumb-item active" aria-current="page">Update User Details </li>
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
                    echo form_open_multipart('admin/register/update_verification', $attributes);
                    ?>
                    <div class="card">
                        <!--                        <div class="card-header">
                                                    <h2 class="card-title">Update User Details</h3>
                                                </div>-->
                        <?php if ($this->session->flashdata('success') != "") : ?>
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-block">

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Mobile Verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="mobile_verified" id="mobile_verified">
                                        <option value="<?= $row->mobile_verified; ?>"><?= $row->mobile_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Email id verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="email_id_verified" id="email_id_verified">
                                        <option value="<?= $row->email_id_verified; ?>"><?= $row->email_id_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Office email id verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="office_email_id_verified" id="office_email_id_verified">
                                        <option value="<?= $row->office_email_id_verified; ?>"><?= $row->office_email_id_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Driver license verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="driver_license_verified" id="driver_license_verified">
                                        <option value="<?= $row->driver_license_verified; ?>"><?= $row->driver_license_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>
                            <!--<div class="form-group">-->
                            <!--  <label class="col-md-3 col-xs-12 control-label">Government id verified:</label>-->
                            <!--  <div class="col-md-6 col-xs-12">-->
                            <!--    <select class="form-control" name="government_id_verified" id="government_id_verified">-->
                            <!--      <option value="<?= $row->government_id_verified; ?>"><?= $row->government_id_verified; ?></option>-->
                            <!--      <option value="yes">yes</option>-->
                            <!--       <option value="no">no</option>-->

                            <!--    </select>-->
                            <!--  </div>-->
                            <!--</div>  -->
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Photo verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="photo_verified" id="photo_verified">
                                        <option value="<?= $row->photo_verified; ?>"><?= $row->photo_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Address verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="address_verified" id="address_verified">
                                        <option value="<?= $row->address_verified; ?>"><?= $row->address_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>






                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Pan card verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="pan_card_verified" id="pan_card_verified">
                                        <option value="<?= $row->pan_card_verified; ?>"><?= $row->pan_card_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Aadhar card verified:</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="aadhar_card_verified" id="aadhar_card_verified">
                                        <option value="<?= $row->aadhar_card_verified; ?>"><?= $row->aadhar_card_verified; ?></option>
                                        <option value="yes">yes</option>
                                        <option value="no">no</option>

                                    </select>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="status_id" value="<?= $id; ?>">


                        <div class="card-footer">
                            <!-- <button class="btn btn-default">Clear Form</button> -->
                            <a href="<?= base_url(); ?>admin/register/otherusers" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
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
                brand_id: {
                    required: true,
                },
                title: {
                    required: true,
                }
            },
            submitHandler: function (form) {
                $("#btnSubmit").prop('disabled', true);
                form.submit();
            }
        });

    });
</script>
