<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Bookings cancelled by rider</a></li>
  <li class="active">Bookings cancelled by rider</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
      echo form_open_multipart('admin/register/update_services', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Bookings cancelled by rider</h2>
          </div>
          <?php if($this->session->flashdata('success') != "") : ?>                
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <?=$this->session->flashdata('success');?>
            </div>
          <?php endif; ?>
          <div class="panel-body">  

            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Booking Id: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['booking_id'];?>              
              </div>
            </div>
           <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">User Name: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['first_name'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Vehicle Id: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['vehicle_id'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">From lat: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['from_lat'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">From lng: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['from_lng'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">From Address: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['from_address'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">To Lat: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['to_lat'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">To Lng: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['to_lng'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">To Address: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['to_address'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Mode: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['mode'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Vehicle Type: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['vehicle_type'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Seats Required: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['seats_required'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Ride Type: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['ride_type'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Ride Time: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['ride_time'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Trip Distance: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['trip_distance'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Amount: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['amount'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Rider: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['ufirst_name'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Ride Id: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['ride_id'];?>              
              </div>
            </div>
            
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Accepted Date: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['accepted_date'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Status: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['status'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Payment Status: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['payment_status'];?>              
              </div>
            </div>
          </div>
          <!-- <div class="panel-footer">
            <a href="<?=base_url();?>admin/register/venues" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    
            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
          </div> -->
        </div>
      </form>

    </div>
  </div>
</div>
<style type="text/css">
  .margintop7
  {
    margin-top: 7px;
  }
</style>
