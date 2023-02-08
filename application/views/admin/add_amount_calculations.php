
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Charges</a></li>
    <li class="active">Add Charges</li>
</ul>


<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <?php
            $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
            echo form_open_multipart('admin/register/update_amount_calculations', $attributes);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Add Charges</h2>
                </div>
                <?php if ($this->session->flashdata('success') != "") : ?>
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                <div class="panel-body">

                    <div class="form-group">
                        <label class="col-md-3 col-xs-12 control-label">Vehicle Type</label>
                        <div class="col-md-6 col-xs-12">
                            <select class="form-control" name="vehicle_type" id="vehicle_type">
                                <option value="">Select Vehicle Type</option>
                                <option value="car">Car</option>
                                <option value="bike">Bike</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-xs-12 control-label">Travel Type</label>
                        <div class="col-md-6 col-xs-12">
                            <select class="form-control" name="travel_type" id="travel_type" onchange="check_fields(this.value)">
                                <option value="">Select Travel Type</option>
                                <option value="city">City</option>
                                <option value="outstation">Outstation</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="0to1" class="col-md-3 col-xs-12 control-label">Enter Amount for 0 to 1 Km</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="0to1" id="0to1" />
                        </div>
                    </div>

                    <div class="form-group city">
                        <label for="2to10" class="col-md-3 col-xs-12 control-label">Enter amount for 2 to 10 Km</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="2to10" id="2to10" />
                        </div>
                    </div>

                    <div class="form-group city">
                        <label for="11to30" class="col-md-3 col-xs-12 control-label">Enter amount for 11 to 30 Km</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="11to30" id="11to30" />
                        </div>
                    </div>

                    <div class="form-group outstation">
                        <label for="2to25" class="col-md-3 col-xs-12 control-label">Enter amount for 2 to 25 Km</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="2to25" id="2to25" />
                        </div>
                    </div>

                    <div class="form-group outstation">
                        <label for=">25" class="col-md-3 col-xs-12 control-label">Enter amount for 25 KM or more</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name=">25" id=">25" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="payment_gateway_commision" class="col-md-3 col-xs-12 control-label">Enter Payment gateway commision</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="payment_gateway_commision" id="payment_gateway_commision" />
                        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                    <div class="form-group">
                        <label for="ciao_commission" class="col-md-3 col-xs-12 control-label">Enter CIAO commission</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="ciao_commission" id="ciao_commission" />
                        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                    <div class="form-group">
                        <label for="service_tax" class="col-md-3 col-xs-12 control-label">Enter Service tax</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="service_tax" id="service_tax" />
                        </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule before time</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="schedule_before_time" id="schedule_before_time" placeholder="Ex: 60" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mins
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule before percentage</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="schedule_before_percentage" id="schedule_before_percentage" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule less than time</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="schedule_lessthan_time" id="schedule_lessthan_time" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mins
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Schedule less than percentage</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="schedule_lessthan_percentage" id="schedule_lessthan_percentage" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Instant after time</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="instant_after_time" id="instant_after_time" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mins
                    </div>

                    <div class="form-group">
                        <label for="greater_than_500" class="col-md-3 col-xs-12 control-label">Instant after percentage</label>
                        <div class="col-md-6 col-xs-12">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <input type="text" class="form-control" name="instant_after_percentage" id="instant_after_percentage" />
                        </div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%
                    </div>

                </div>
                <div class="panel-footer">
                    <a href="<?= base_url(); ?>admin/register/amount_calculations" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
                    <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
                </div>
            </div>
            </form>

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
                                            per_km: {
                                                required: true,
                                            },
                                            vehicle_type: {
                                                required: true,
                                            }
                                        },
                                        submitHandler: function (form) {
                                            $("#btnSubmit").prop('disabled', true);
                                            form.submit();
                                        }
                                    });
                                });

                                function check_fields(travel_type)
                                {
                                    //alert(travel_type);
                                    var vehicle_type = $("#vehicle_type option:selected").val();
                                    if (travel_type == "outstation")
                                    {
                                        $(".outstation").hide();
                                        $(".city").show();
                                    } else
                                    {
                                        $(".outstation").show();
                                        $(".city").hide();
                                    }
                                }
</script>
