<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-inr bg-blue"></i>
                        <div class="d-inline">
                            <h5>Amount Calculations  </h5>
                            <span>Edit  Amount Calculations</span>
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

                            <li class="breadcrumb-item active" aria-current="page"> Amount Calculations </li>
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
                    echo form_open_multipart('admin/register/update_amount_calculations', $attributes);
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
                                <label class="col-md-3 col-xs-12 control-label">Vehicle Type</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="vehicle_type" id="vehicle_type" disabled="">
                                        <option value="">Select Vehicle Type</option>
                                        <option value="car" <?php echo ($row['vehicle_type'] == "car") ? "selected" : ""; ?>>Car</option>
                                        <option value="bike" <?php echo ($row['vehicle_type'] == "bike") ? "selected" : ""; ?>>Bike</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-md-3 col-xs-12 control-label">Travel Type</label>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control" name="travel_type" id="travel_type" disabled="">
                                        <option value="">Select Travel Type</option>
                                        <option value="city" <?php echo ($row['travel_type'] == "city") ? "selected" : ""; ?>>City</option>
                                        <option value="outstation" <?php echo ($row['travel_type'] == "outstation") ? "selected" : ""; ?>>Outstation</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row  city">
                                <label for="base_fare" class="col-md-3 col-xs-12 control-label">Enter Base Fare</label>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" class="form-control" name="base_fare" id="base_fare" value="<?= $row['base_fare']; ?>" />
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="0to1" class="col-md-3 col-xs-12 control-label">Enter Amount for 0 to 1 Km</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="0to1" id="0to1" value="<?= $row['0to1']; ?>" />
                                </div>
                            </div>

                            <?php
                            if ($row['travel_type'] == "city") {
                                ?>
                                <div class="form-group row  city">
                                    <label for="2to10" class="col-md-3 col-xs-12 control-label">Enter amount for 2 to 10 Km</label>
                                    <div class="col-md-6 col-xs-12">

                                        <input type="text" class="form-control" name="2to10" id="2to10" value="<?= $row['2to10']; ?>" />
                                    </div>
                                </div>

                                <div class="form-group row  city">
                                    <label for="11to30" class="col-md-3 col-xs-12 control-label">Enter amount for 11 to 30 Km</label>
                                    <div class="col-md-6 col-xs-12">

                                        <input type="text" class="form-control" name="11to30" id="11to30" value="<?= $row['11to30']; ?>" />
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="form-group row  outstation">
                                    <label for="2to25" class="col-md-3 col-xs-12 control-label">Enter amount for 2 to 25 Km</label>
                                    <div class="col-md-6 col-xs-12">

                                        <input type="text" class="form-control" name="2to25" id="2to25" value="<?= $row['2to25']; ?>" />
                                    </div>
                                </div>

                                <div class="form-group row  outstation">
                                    <label for=">25" class="col-md-3 col-xs-12 control-label">Enter amount for 25 KM or more</label>
                                    <div class="col-md-6 col-xs-12">

                                        <input type="text" class="form-control" name=">25" id=">25" value="<?= $row['>25']; ?>" />
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Service Tax</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="service_tax" id="service_tax" value="<?= $row['service_tax']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                            <div class="form-group row ">
                                <label for="payment_gateway_commision" class="col-md-3 col-xs-12 control-label">Payment gateway commision</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="payment_gateway_commision" id="payment_gateway_commision" value="<?= $row['payment_gateway_commision']; ?>" />
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                            <div class="form-group row ">
                                <label for="ciao_commission" class="col-md-3 col-xs-12 control-label">Enter CIAO commission</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="ciao_commission" id="ciao_commission" value="<?= $row['ciao_commission']; ?>" />
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                            <hr>
                            <h3>Cancellation Charges Calculations</h3>
                            <hr>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule before time</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="schedule_before_time" id="schedule_before_time" value="<?= $row['schedule_before_time']; ?>" placeholder="Ex: 60" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mins
                            </div>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule before percentage</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="schedule_before_percentage" id="schedule_before_percentage" value="<?= $row['schedule_before_percentage']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule less than time</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="schedule_lessthan_time" id="schedule_lessthan_time" value="<?= $row['schedule_lessthan_time']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mins
                            </div>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule less than percentage</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="schedule_lessthan_percentage" id="schedule_lessthan_percentage" value="<?= $row['schedule_lessthan_percentage']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Instant after time</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="instant_after_time" id="instant_after_time" value="<?= $row['instant_after_time']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mins
                            </div>

                            <div class="form-group row ">
                                <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Instant after percentage</label>
                                <div class="col-md-6 col-xs-12">

                                    <input type="text" class="form-control" name="instant_after_percentage" id="instant_after_percentage" value="<?= $row['instant_after_percentage']; ?>" />
                                </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                            </div>

                        </div>
                        <input type="hidden" name="amount_calculations_id" value="<?= $row['id']; ?>">
                        <div class="card-footer">
                            <!-- <button class="btn btn-default">Clear Form</button> -->
                            <a href="<?= base_url(); ?>admin/register/amount_calculations" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
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
    $(document).ready(function() { //alert();
    $("#validation").validate({
    rules: {
    // simple rule, converted to {required:true}
    per_km: {
    required : true,
    },
            vehicle_type: {
            required : true,
            },
            1_to_25: {
            required : true,
            },
            26_to_50: {
            required : true,
            },
            51_to_100: {
            required : true,
            },
            101_to_300: {
            required : true,
            },
            301_to_500: {
            required : true,
            },
            greater_than_500: {
            required : true,
            }
    },
            submitHandler: function(form) {
            $("#btnSubmit").prop('disabled', true);
            form.submit();
            }
    });
    });
</script>
