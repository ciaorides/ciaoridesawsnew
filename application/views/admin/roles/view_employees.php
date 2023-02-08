<style type="text/css">
  .f-right{float: right;}
</style>
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="<?=base_url();?>admin/home">Home</a></li>
  <li><a href="<?=base_url();?>admin/roles/employees">Employees</a></li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
  <div class="row">
    <div class="col-md-12">
      <?php 
      $attributes = array('class' => 'form-horizontal', 'id' => 'myform');
      echo form_open_multipart('admin/uploads/update_news', $attributes); 
      ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Employee View</h2>            
          </div>
          <div class="panel-body">
           <div class="input_fields_wrap gray points-cont">

             <div class="form-group row">
                  <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Image :</span></label> 
                      <div class="col-sm-4">
                                 <?php if($record['image'] != ''){?>
                  <img src="<?=base_url().$record['image'];?>" height="150" width="150"  class="thumbnail"/>
                <?php }else{?>
                  <img src="<?=base_url('storage/no_image.jpg');?>" height="50" width="50" class="thumbnail"/>
                <?php }?>
                      </div>
              </div>
                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Roles:</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['roles'];?>
                                </div>
                              </div>

                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Employee ID :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['employee_id'];?>
                                </div>
                              </div>


                              <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Employee Name :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['employee_name'];?>
                                </div>
                              </div>


                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Employee Email :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['employee_email'];?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Employee Mobile :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['employee_mobile'];?>
                                </div>
                            </div>

                             <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Address :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['emp_address'];?>
                                </div>
                             </div>

                             <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Pincode :</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['pincode'];?>
                                </div>
                             </div>
   
            </div>
        </div>
     

            <!--<a href="javascript:void(0)" class="add_field_button"><span class="" style="float: right; margin-right: 200px; color: green"><i class="fa fa-plus"></i></span></a>-->
           

          

          </div>
          <div class="panel-footer">

            <a href="<?=base_url();?>admin/roles/employees" class="btn btn-primary pull-right" style="margin-left:10px;">Back</a>  
            
           
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-validate-additional-methods.js"></script>
