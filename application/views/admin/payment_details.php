<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Paymet Details</a></li>
  <li class="active">Payment Details</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-6 new-form">
      <?php 
      $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
      echo form_open_multipart('admin/register/update_services', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Payment Details</h2>
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
              <label for="title" class="col-md-3 col-xs-12 control-label">User Mobile: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['mobile'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">User email: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['email_id'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Gender: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['gender'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Address: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['address1'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">payment status: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['payment_status'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">User payment mode: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['payment_mode'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Amount paid: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['amount'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">From address: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['from_address'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">To address: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['to_address'];?>              
              </div>
            </div>
            
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Raider Name: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['rname'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Raider Payment Mode: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['rpayment_mode'];?>              
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Payment Date: </label>
              <div class="col-md-6 col-xs-12 margintop7">  
              <?=$row['ocreated_on'];?>              
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
