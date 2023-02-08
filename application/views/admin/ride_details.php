<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-user bg-green"></i>
                        <div class="d-inline">
                            <h5>Ride Details </h5>
                            <span>View Ride Details  </span>
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
                                <a href="<?= base_url('admin/rides') ?>">Ride Details</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">Ride Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col-md-12 new-form">

                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                    echo form_open_multipart('admin/register/update_services', $attributes);
                    ?>
                    <div class="card card-default">

                        <?php if ($this->session->flashdata('success') != "") : ?>
                            <div class="alert alert-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-6 " style="border-right:1px dashed #ddd;">
                                    <div class="grey" style="background: #fff; padding:10px 30px; border-radius:20px;border-bottom:2px solid #eee;min-height: 400px;">

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Trip ID: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['trip_id']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">User Name: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['first_name']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">VehicleNumber: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['vehicle_number']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">From lat: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['from_lat']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">From lng: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['from_lng']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">From Address: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['from_address']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">To Lat: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['to_lat']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">To Lng: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['to_lng']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">To Address: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['to_address']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Mode: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['mode']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Vehicle Type: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['vehicle_type']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Gender: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['gender']; ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="grey" style="background: #fff; padding:10px 30px; border-radius:20px;border-bottom:2px solid #eee;min-height: 400px;" >
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Seats Available: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['seats_available']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Seats: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['seats']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Note: </label>
                                            <div class="col-md-9 col-xs-12 control-label">
                                                <?= $row['note']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Trip Distance: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['trip_distance']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">AmountPerHead:</label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['amount_per_head']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">RideBookingTime: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?= $row['created_on']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">RideStartTime: </label>
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
                                        <h4 style="
                                            text-align: center;
                                            margin-top: 27px;
                                            ">Fare Details</h4>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-3 col-xs-12 control-label">Total amount: </label>
                                            <div class="col-md-5 col-xs-12 control-label">
                                                <?php if (isset($row['amount'])) {
                                                    echo $row['amount'];
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
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
                <!-- <div id="map_canvas" style="height: 350px;width: 100%;margin: 0.6em;"></div> -->
            </div>
        </div>
    </div>
</div>
