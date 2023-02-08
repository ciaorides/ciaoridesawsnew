<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-car bg-green"></i>
                        <div class="d-inline">
                            <h5>OnGoing  Ride Details  </h5>
                            <span> OnGoing  Ride Details </span>
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

                            <li class="breadcrumb-item active" aria-current="page"> OnGoing Ride Details </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($this->session->flashdata('success') != "") : ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12  new-form">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                    echo form_open_multipart('admin/register/update_services', $attributes);
                    ?>
                    <div class="card card-default">
                        <!--                        <div class="card-heading">
                                                    <h2 class="card-title">Shceduled Ride Details</h2>
                                                </div>-->
                        <?php if ($this->session->flashdata('success') != "") : ?>
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-block">

                            <div class="form-group row  ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">User Name: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['first_name']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">VehicleNumber: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['vehicle_number']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">From lat: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['from_lat']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">From lng: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['from_lng']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">From Address: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['from_address']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">To Lat: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['to_lat']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">To Lng: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['to_lng']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">To Address: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['to_address']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Mode: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['mode']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Vehicle Type: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['vehicle_type']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Gender: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['gender']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Seats Available: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['seats_available']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Seats: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['seats']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Note: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['note']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Trip Distance: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['trip_distance']; ?>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Amount Per Head: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['amount_per_head']; ?>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Ride Time: </label>
                                <div class="col-md-6 col-xs-12 margintop7">
                                    <?= $row['ride_time']; ?>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Ride Booking Time: </label>
                                <div class="col-md-5 col-xs-12 control-label">
                                    <?= $row['created_on']; ?>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Ride Start Time: </label>
                                <div class="col-md-5 col-xs-12 control-label">
                                    <?= $row['ride_time']; ?>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="title" class="col-md-3 col-xs-12 control-label">Ride End Time: </label>
                                <div class="col-md-5 col-xs-12 control-label">
                                    <?= $row['ride_end_time']; ?>
                                </div>
                            </div>


                        </div>
                        <!-- <div class="panel-footer">
                          <a href="<?= base_url(); ?>admin/register/venues" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
                          <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
                        </div> -->
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
