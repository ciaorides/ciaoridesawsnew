<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Vehicle Models</a></li>
  <li class="active">Add Vehicle Models</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
      echo form_open_multipart('admin/register/update_vehicle_models', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Add Vehicle Models</h2>            
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
                <select class="form-control" name="vehicle_type" id="vehicle_type" required="" onchange="get_vehicle_brands(this.value)">
                  <option value="">Select Vehicle Type</option>
                  <option value="bike">Bike</option> 
                  <option value="car">Car</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-xs-12 control-label">Vehicle Brands</label>
              <div class="col-md-6 col-xs-12">
                <select class="form-control" name="make_id" id="make_id">
                  <option value="">Select Vehicle Brand</option>
                   <?php
                  if(!empty($categories))
                  {
                    foreach($categories as $category)
                    {
                      ?>
                      <option value="<?=$category->id;?>"><?=$category->title;?></option>
                      <?php
                    }
                  }
                  ?> 
                </select>
              </div>
            </div>
            
              <div class="form-group">
                <label for="title" class="col-md-3 col-xs-12 control-label">Enter Vehicle Model</label>
                <div class="col-md-6 col-xs-12">                
                  <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                  <input type="text" class="form-control" name="title" id="title" />
                </div>
              </div>
             
              
            <a href="javascript:void(0)" class="add_field_button"><span class="" style="float: right; margin-right: 200px; color: green"></span></a>


          </div>
          <div class="panel-footer">
            <a href="<?=base_url();?>admin/register/vehicle_models" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    
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
  $(document).ready(function() { //alert();
      $("#validation").validate({
          rules: {
            // simple rule, converted to {required:true}            
            category_id: {
                required : true,
            },
            category1_id: {
                required : true,
            },
            gender: {
                required : true,
            },
            "title[]": {
                required : true,
            }
          },
          submitHandler: function(form) {
              $("#btnSubmit").prop('disabled', true);
              form.submit();
          }
        });
  });

  $(document).ready(function() {
      var max_fields      = 10; //maximum input boxes allowed

      var wrapper         = $(".input_fields_wrap"); //Fields wrapper
      var add_button      = $(".add_field_button"); //Add button ID
      
      var html = $(".input_fields_wrap").html();//alert(html);
      var x = 1; //initlal text box count
      $(add_button).click(function(e){ //on add input button click
          e.preventDefault();
          if(x < max_fields){ //max input box allowed
              x++; //text box increment
              $(wrapper).append('<div class="form-group"><label for="title" class="col-md-3 col-xs-12 control-label">Enter Sub Category</label><div class="col-md-6 col-xs-12"><span class="input-group-addon"><i class="fa fa-pencil"></i></span><input type="text" class="form-control" name="title[]" id="title" /></div><a href="javascript:void(0)" class="remove_field"><span class="" style=" color: red"><i class="fa fa-minus"></i></span></a></div>'); //add input box
          }
      });      
      $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
          e.preventDefault(); $(this).parent('div').remove(); x--;
      });
  });

  function get_vehicle_brands(vehicle_type)
  {
    //alert(vehicle_type);exit;
    $.ajax({
      type: 'post',
      url: '<?=base_url();?>admin/register/get_vehicle_brands',
      data: {vehicle_type: vehicle_type},
      beforeSend: function(xhr){
        xhr.overrideMimeType("text/plain; charset=x-user-defined");
        $("#wait").css("display", "block");
      },
      success: function(data){ //alert(data);
        $("#make_id").html(data);
       $("#wait").css("display", "none");
      }
    });
  }
</script>
