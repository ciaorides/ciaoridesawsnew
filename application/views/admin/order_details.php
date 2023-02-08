<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-car bg-green"></i>
                        <div class="d-inline">
                            <h5>Booking Details  </h5>
                            <span> Booking Details </span>
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

                            <li class="breadcrumb-item active" aria-current="page"> Booking Details </li>
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
        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            <div class="row">
                <div class="col">
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
                    echo form_open_multipart('admin/register/update_services', $attributes);
                    ?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-default">
                                    <!--                                    <div class="card-heading">
                                                                            <h2 class="panel-title">Booking Details</h2>
                                                                        </div>-->
                                    <?php if ($this->session->flashdata('success') != "") : ?>
                                        <div class="alert alert-success" role="alert">
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <?= $this->session->flashdata('success'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-block ">

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Booking Id: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['booking_id']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Trip Details: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <a href="<?= base_url('admin/register/ride_details/' . $row['ride_id']) ?>" target="_blank" class="text-success"><b> View </b></a>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">User Name: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <a href="<?= base_url('admin/register/user_details/' . $row['user_id']) ?>" target="_blank" class="text-success"><b>  <?= $row['first_name']; ?> <?= $row['last_name']; ?></b></a>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Rider Name: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <a href="<?= base_url('admin/register/user_details/' . $row['rider_id']) ?>" target="_blank" class="text-success"><b>  <?= $row['ufirst_name']; ?> <?= $row['ulast_name']; ?></b></a>


                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">From lat: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['from_lat']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">From lng: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['from_lng']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">From Address: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['from_address']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">To Lat: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['to_lat']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">To Lng: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['to_lng']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">To Address: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['to_address']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Mode: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['mode']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Vehicle Type: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['vehicle_type']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Seats: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['seats_required']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Ride Type: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ride_type']; ?>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- <div class="panel-footer">
                                      <a href="<?= base_url(); ?>admin/register/venues" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
                                      <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card card-default">
                                    <!--                                    <div class="card-heading">
                                                                            <h2 class="card-title">Booking Details</h2>
                                                                        </div>-->
                                    <div class="card-block">

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Ride Time: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ride_time']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Trip Distance: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['trip_distance']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Amount: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['amount']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Base Fare: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['base_fare']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Tax: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['tax']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Payment Gateway Commision: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['payment_gateway_commision']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">CIAO Commission: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ciao_commission']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Total Amount: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['total_amount']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Rider Amount: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['rider_amount']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Rider: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ufirst_name']; ?>
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row ">
                                          <label for="title" class="col-md-6 col-xs-12 control-label">Ride Id: </label>
                                          <div class="col-md-6 col-xs-12 margintop7">
                                        <?= $row['ride_id']; ?>
                                          </div>
                                        </div> -->

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Accepted Date: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['accepted_date']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Ride start time: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ride_start_time']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Ride end time: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['ride_end_time']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Status: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['status']; ?>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Payment Status: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['payment_status']; ?>
                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <label for="title" class="col-md-6 col-xs-12 control-label">Cancellation Charges: </label>
                                            <div class="col-md-6 col-xs-12 margintop7">
                                                <?= $row['cancellation_charges']; ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .margintop7
    {
        margin-top: 7px;
    }
</style>
