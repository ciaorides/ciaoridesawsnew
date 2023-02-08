<!-- START BREADCRUMB -->

<div class="main-content">
        <div class="main-content-inner">
          <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
            <ul class="breadcrumb">
              <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo site_url();?>admin/dashboard">Home</a>
              </li>
              <li>                 
                <a href="<?php echo site_url();?>admin/roles/employees">Employees</a>
              </li>
              <li class="active"> Edit</li>
            </ul><!-- /.breadcrumb -->            
          </div>

          <div class="page-content">
            <div class="page-header-2">
              <h1 class="col-lg-4 col-md-3 col-sm-3 col-xs-12 pdg-top-10">
                <i class="menu-icon fa fa-list-ul blue"></i>Employees
                <span class="label label-purple arrowed">Edit<span>
              </h1>
              <div class="pull-right ">              
                 <input type="hidden" name="hiv" id="hiv" value="0" />
                  
                           </div>
            </div><!-- /.page-header -->
            
             <?php //echo $message; ?>

           <div class="container">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                    <form class="form-horizontal" role="form" name="myform" id="myform" method="post" action="<?php echo base_url()?>admin/roles/update_employees" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">

              


          <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Employee ID <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

                  

                  <input type="text" class="form-control" name="employeeID" id="title" required="" data-parsley-required-message="Please enter Employee ID" value="<?php echo $row->user_id?>" readonly/>

                </div>

              </div>

             

                        
                

                 

                  <div class="form-group">

              <label class="col-md-3 col-xs-6 control-label">Select Roles  <span style="color:red">*</span></label>

              <div  class="col-md-6 col-xs-6">

            <select class="form-control standardSelect"  multiple   name="role_ids[]" id="roles_ids" required="" data-parsley-required-message="Role is Required" >
              <?php foreach($roles as $val){
                $db_role_ids=explode(',',$row->role_ids);

                ?>
                  <option value="<?php echo $val['id']?>" 
                    <?php foreach($db_role_ids as $r_id){
                            if($r_id == $val['id']){?> selected <?php } }?> ><?php echo $val['rolename']?>
                              
                  </option>
               <?php }?> 

                </select>

              </div>

  </div>

              <div class="form-group">

              <label class="col-md-3 col-xs-6 control-label">Select Department  <span style="color:red">*</span></label>

              <div  class="col-md-6 col-xs-6">

          <input type="text" class="form-control" name="department" id="title" required="" data-parsley-required-message="Please enter department Name" value="<?php echo $row->department?>" />

              </div>

              </div>

        <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Enter Name <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

                

                  <input type="text" class="form-control" name="employee_name" id="title" required="" data-parsley-required-message="Please enter Name" value="<?php echo $row->user_name?>" />

                </div>

              </div>


        <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Enter Email <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

                

                  <input type="email" class="form-control" name="employee_email" id="title" required="" data-parsley-required-message="Please enter email" parsley-type="text" data-parsley-check_admin_email="" data-parsley-trigger="keyup" data-parsley-trigger="change" data-parsley-type-message="Please enter valid email id" data-parsley-required-message="Email ID must be filled" parsley-type="email" value="<?php echo $row->user_email?>"/>

                </div>

              </div>


        <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Enter Mobile <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

                 

                  <input type="text" class="form-control" name="employee_mobile" id="title" required="" data-parsley-required-message="Please enter Mobile" parsley-type="text" data-parsley-check_emp_mobile="" data-parsley-trigger="keyup" data-parsley-trigger="change" data-parsley-pattern="/^[0-9]+$/"   data-parsley-pattern-message="Enter valid Mobile no" data-parsley-minlength="10" data-parsley-minlength-message="Mobile number should not be less than 10 digits" data-parsley-maxlength="10" data-parsley-maxlength-message="Mobile number should not be greater than 10 digits" value="<?php echo $row->user_mobile?>"/>

                </div>

              </div>

        <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Enter Pincode <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

                 

                  <input type="text" class="form-control" name="pincode" id="title" required="" data-parsley-required-message="Please enter Pincode" value="<?php echo $row->pincode?>"/>

                </div>

              </div>

              <div class="form-group">

        <label for="title" class="col-md-3 col-xs-6 control-label">Enter Address <span style="color:red">*</span></label>

                <div class="col-md-6 col-xs-6">                

         <textarea type="text" class="form-control" name="emp_address" id="title" required="" data-parsley-required-message="Please enter address" ><?php echo $row->user_address?></textarea>

                </div>

              </div>

              

                  

              <div class="form-group">
              <label  class="col-md-3 col-xs-6 control-label">Address Proof <span style="color:red">*</span></label>
              <div class="col-md-6 col-xs-6">
                <div class="">
                 
                  <input type="file" class="fileinput btn-primary" name="address_proof" id="icon" />
                </div>
                <?php if($row->address_proof   != ''){?>
                  <img src="<?=base_url().$row->address_proof ;?>" height="150" width="150" />
                <?php }else{?>
                  <img src="<?=base_url('storage/no_image.jpg');?>" height="50" width="50" />
                <?php }?>
              </div>
            </div>

            <div class="form-group">
              <label  class="col-md-3 col-xs-6 control-label">Upload Image</label>
              <div class="col-md-6 col-xs-6">
                <div class="">
                 
                  <input type="file" class="fileinput btn-primary" name="image" id="icon" />
                </div>
                <?php if($row->image != ''){?>
                  <img src="<?=base_url().$row->image;?>" height="150" width="150" />
                <?php }else{?>
                  <img src="<?=base_url('storage/no_image.jpg');?>" height="50" width="50" />
                <?php }?>
              </div>
            </div>



 <input type="hidden" class="form-control" name="employee_id" value="<?php echo $row->id?>"/>

            <!--<a href="javascript:void(0)" class="add_field_button"><span class="" style="float: right; margin-right: 200px; color: green"><i class="fa fa-plus"></i></span></a>-->


          <div class="panel-footer">

            <a href="<?=base_url();?>admin/roles/employees" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    

            <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>

          </div>



          </div>


        </div>

      </form>



    </div>

  </div>

</div>

<!-- END PAGE CONTENT WRAPPER -->
 </div>

</div>

<link rel="stylesheet" href="<?php echo base_url(); ?>admin_assets/css/chosen/chosen.min.css">
<script src="<?php echo base_url(); ?>admin_assets/js/chosen/chosen.jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".standardSelect").chosen({
            disable_search_threshold: 10,
            no_results_text: "Oops, nothing found!",
            width: "100%"
        });
    });

    </script>
<script>

  $(document).ready(function() { //alert();

      $("#validation").validate({

          rules: {

            // simple rule, converted to {required:true}            

            category_id: {

                required : true,

            },

            title: {

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

              $(wrapper).append('<div class="form-group"><label for="title" class="col-md-3 col-xs-12 control-label">Enter Building/Zone/Block</label><div class="col-md-6 col-xs-12"><span class="input-group-addon"><i class="fa fa-pencil"></i></span><input type="text" class="form-control" name="title[]" id="title" required="" data-parsley-required-message="Please enter Building/Zone/Block" parsley-type="text" data-parsley-check_building_name="" data-parsley-trigger="keyup"/></div><a href="javascript:void(0)" class="remove_field"><span class="" style=" color: red"><i class="fa fa-minus"></i></span></a></div>'); //add input box

          }

      });      

      $(wrapper).on("click",".remove_field", function(e){ //user click on remove text

          e.preventDefault(); $(this).parent('div').remove(); x--;

      });

  });





function getroles(center_id){
    //alert(exam_id);
     var state_id=$("#state_id").val();
     var organisation_id=$("#organisation_id").val();

    $.ajax({
      type: 'post',
      url: '<?=base_url();?>admin/common/getroles',
      data: {state_id: state_id,organisation_id: organisation_id,center_id: center_id},
      beforeSend: function(xhr){
        xhr.overrideMimeType("text/plain; charset=utf-8");
        //$("#wait").css("display", "block");
      },
      success: function(data){ //alert(data);
        $("#roles_ids").html(data);
        //$("#wait").css("display", "none");
        $("#roles_ids").trigger("chosen:updated");
      }
    });


    $.ajax({
      type: 'post',
      url: '<?=base_url();?>admin/common/getDepartments',
      data: {state_id: state_id,organisation_id: organisation_id,center_id: center_id},
      beforeSend: function(xhr){
        xhr.overrideMimeType("text/plain; charset=utf-8");
        //$("#wait").css("display", "block");
      },
      success: function(data){ //alert(data);
        $("#department_id").html(data);
        
      }
    });

  

 $.ajax({
            type: 'post',
            url: '<?=base_url();?>admin/common/getPaymentModes',
            data: {state_id: state_id,organisation_id:organisation_id,center_id:center_id},
            beforeSend: function(xhr){
              xhr.overrideMimeType("text/plain; charset=utf-8");
              $("#wait").css("display", "block");
            },
            success: function(data){ 
              //var json = $.parseJSON(data); 
              //alert("OTP Send Successfully");
              $("#payment_mode_id").html(data);
              $("#to_payment_mode_id").html(data);
              $("#to_payment_mode_id").trigger("chosen:updated");
            }
          });

  }







</script>

