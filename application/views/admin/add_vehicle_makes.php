<!-- START BREADCRUMB -->
<!--
<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Vehicle Brands</a></li>
  <li class="active">Add Vehicle Brands</li>
</ul>
-->
<!-- END BREADCRUMB -->

<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-users bg-green"></i>
                        <div class="d-inline">
                            <h5>Add Vehicle Brands  </h5>
                            <span> Vehicle Brands </span>
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
                                <a href="<?= base_url('admin/vehicle_makes') ?>"> Vehicles </a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">  Vehicle Brands </li>
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
      echo form_open_multipart('admin/vehicle_management/update_vehicle_makes', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Add Vehicle Brands</h2>            
          </div>
          <?php if($this->session->flashdata('success') != "") : ?>                
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <?=$this->session->flashdata('success');?>
            </div>
          <?php endif; ?>
          <div class="panel-body">

            <div class="form-group">
              <label class="col-md-3 col-xs-12 control-label">Vehicle Types</label>
              <div class="col-md-6 col-xs-12">
                <select class="form-control" name="vehicle_type" id="vehicle_type" required="">
                  <option value="">Select Vehicle Type</option>
                  <option value="bike">Bike</option> 
                  <option value="car">Car</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="title" class="col-md-3 col-xs-12 control-label">Enter Title</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="title" id="title" />
              </div>
            </div>

          </div>
          <div class="panel-footer">
            <a href="<?=base_url();?>admin/vehicle_management/vehicle_makes" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    
            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
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
            title: {
                required : true,
            },
           
          },
          submitHandler: function(form) {
              $("#btnSubmit").prop('disabled', true);
              form.submit();
          }
        });
  });
 
</script>
