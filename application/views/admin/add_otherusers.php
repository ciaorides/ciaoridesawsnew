<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">otherusers</a></li>
  <li class="active">Add otherusers</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $attributes = array('class' => 'form-horizontal', 'id' => 'validation');
      echo form_open_multipart('admin/register/update_otherusers', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Add otherusers</h2>            
          </div>
          <?php if($this->session->flashdata('success') != "") : ?>                
            <div class="alert alert-success" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <?=$this->session->flashdata('success');?>
            </div>
          <?php endif; ?>
          <div class="panel-body">

            <div class="form-group">
              <label for="first_name" class="col-md-3 col-xs-12 control-label">Enter  first_name</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="first_name" id="first_name" />
              </div>
            </div>
            <div class="form-group">
              <label for="last_name" class="col-md-3 col-xs-12 control-label">Enter     last_name</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="last_name" id="last_name" />
              </div>
            </div>

             <div class="form-group">
              <label for="mobile" class="col-md-3 col-xs-12 control-label">Enter   mobile</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="mobile" id="mobile" />
              </div>
            </div>
            <div class="form-group">
              <label for="alternate_number" class="col-md-3 col-xs-12 control-label">Enter   alternate_number</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="alternate_number" id="alternate_number" />
              </div>
            </div>


             <div class="form-group">
              <label for="email_id" class="col-md-3 col-xs-12 control-label">Enter  email_id</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="email_id" id="email_id" />
              </div>
            </div>
            <div class="form-group">
              <label for="dob" class="col-md-3 col-xs-12 control-label">Enter  dob</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="dob" id="dob" />
              </div>
            </div>
            
        
          
            <div class="form-group">
              <label class="col-md-3 col-xs-12 control-label">Gender</label>
              <div class="col-md-6 col-xs-12">
                <select class="form-control" name="gender" id="gender" required="">
                  <option value="">Select Gender</option>
                  <option value="men">men</option> 
                  <option value="women">women</option>
                  <option value="any">any</option>
                </select>
              </div>
            </div>




            <div class="form-group">
              <label for="profile_pic" class="col-md-3 col-xs-12 control-label">Enter   profile_pic</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="profile_pic" id="profile_pic" />
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="col-md-3 col-xs-12 control-label">Enter  password</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="password" id="password" />
              </div>
            </div>

             <div class="form-group">
              <label for="address1" class="col-md-3 col-xs-12 control-label">Enter   address1</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="address1" id="address1" />
              </div>
            </div>
            <div class="form-group">
              <label for="address2" class="col-md-3 col-xs-12 control-label">Enter      address2</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="address2" id="address2" />
              </div>
            </div>


             <div class="form-group">
              <label for="postcode" class="col-md-3 col-xs-12 control-label">Enter    postcode</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="postcode" id="postcode" />
              </div>
            </div>
            <div class="form-group">
              <label for="city_id" class="col-md-3 col-xs-12 control-label">Enter   city_id</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="city_id" id="city_id" />
              </div>
            </div> 




            <div class="form-group">
              <label for="country_id" class="col-md-3 col-xs-12 control-label">Enter    country_id</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="country_id" id="country_id" />
              </div>
            </div>
            <div class="form-group">
              <label for="bio" class="col-md-3 col-xs-12 control-label">Enter bio</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="bio" id="bio" />
              </div>
            </div>

             <div class="form-group">
              <label for="payment_mode" class="col-md-3 col-xs-12 control-label">Enter   payment_mode</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="payment_mode" id="payment_mode" />
              </div>
            </div>
            <div class="form-group">
              <label for="facebook" class="col-md-3 col-xs-12 control-label">Enter  facebook</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="facebook" id="facebook" />
              </div>
            </div>


             <div class="form-group">
              <label for="instagram" class="col-md-3 col-xs-12 control-label">Enter    instagram</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="instagram" id="instagram" />
              </div>
            </div>
            <div class="form-group">
              <label for="twitter" class="col-md-3 col-xs-12 control-label">Enter  twitter</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="twitter" id="twitter" />
              </div>
            </div>



            <div class="form-group">
              <label for="linkedin" class="col-md-3 col-xs-12 control-label">Enter    linkedin</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="linkedin" id="linkedin" />
              </div>
            </div>
            <div class="form-group">
              <label for="office_email_id" class="col-md-3 col-xs-12 control-label">Enter     office_email_id</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="office_email_id" id="office_email_id" />
              </div>
            </div>

             <div class="form-group">
              <label for="driver_license" class="col-md-3 col-xs-12 control-label">Enter   driver_license</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="driver_license" id="driver_license" />
              </div>
            </div>
            <div class="form-group">
              <label for="government_id" class="col-md-3 col-xs-12 control-label">Enter      government_id</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="government_id" id="government_id" />
              </div>
            </div>


             <div class="form-group">
              <label for="pan_card" class="col-md-3 col-xs-12 control-label">Enter    pan_card</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="pan_card" id="pan_card" />
              </div>
            </div>
            <div class="form-group">
              <label for="aadhar_card" class="col-md-3 col-xs-12 control-label">Enter  aadhar_card</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="aadhar_card" id="aadhar_card" />
              </div>
            </div>


           
             <div class="form-group">
              <label for="token" class="col-md-3 col-xs-12 control-label">Enter   token</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="token" id="token" />
              </div>
            </div>
            <div class="form-group">
              <label for="ios_token" class="col-md-3 col-xs-12 control-label">Enter   ios_token</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="ios_token" id="ios_token" />
              </div>
            </div>
                


             <div class="form-group">
              <label for="status" class="col-md-3 col-xs-12 control-label">Enter    status</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="status" id="status" />
              </div>
            </div>
            <div class="form-group">
              <label for="delete_status" class="col-md-3 col-xs-12 control-label">Enter  delete_status</label>
              <div class="col-md-6 col-xs-12">                
                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                <input type="text" class="form-control" name="delete_status" id="delete_status" />
              </div>
            </div>


            </div>
          <div class="panel-footer">
            <a href="<?=base_url();?>admin/register/otherusers" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    
            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
          </div>
        </div>
      </form> 
      <script>
  $(document).ready(function() { //alert();
      $("#validation").validate({
          rules: {
            // simple rule, converted to {required:true}            
                order_id: {
                required : true,
            },
                user_id: {
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
              $(wrapper).append('<div class="form-group"><label for="title" class="col-md-3 col-xs-12 control-label">Enter Event Type</label><div class="col-md-6 col-xs-12"><span class="input-group-addon"><i class="fa fa-pencil"></i></span><input type="text" class="form-control" name="title[]" id="title" /></div><a href="javascript:void(0)" class="remove_field"><span class="" style=" color: red"><i class="fa fa-minus"></i></span></a></div>'); //add input box
          }
      });      
      $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
          e.preventDefault(); $(this).parent('div').remove(); x--;
      });
  });   
</script>

