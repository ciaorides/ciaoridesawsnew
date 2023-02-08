<style type="text/css">
  .f-right{float: right;}
</style>
<!-- START BREADCRUMB -->
<ul class="breadcrumb">
  <li><a href="<?=base_url();?>admin/home">Home</a></li>
  <li><a href="<?=base_url();?>admin/roles/roles">Role Employees</a></li>
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
            <h2 class="panel-title">Role Employees</h2>            
          </div>
          <div class="panel-body">
           <div class="input_fields_wrap gray points-cont">

             
                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Role:</span></label> 
                                <div class="col-sm-4">
                                  <?php echo $record['rolename'];?>
                                </div>
                              </div>
              <?php if(!empty($employees)){
                foreach($employees as $key=>$emp){
                ?>
                            <div class="form-group row">
                                <label for="lastname" class="col-sm-3 col-form-label"><span class="f-right">Employee <?php echo $key+1 ?> :</span></label> 
                                <div class="col-sm-4">
                                 <?php echo $emp['employee_name'];?>( <?php echo $emp['employee_id'];?> )
                                </div>
                              </div>


                              
              <?php }
              }?>

                           


                            

                            
   
            </div>
        </div>
     

            <!--<a href="javascript:void(0)" class="add_field_button"><span class="" style="float: right; margin-right: 200px; color: green"><i class="fa fa-plus"></i></span></a>-->
           

          

          </div>
          <div class="panel-footer">

            <a href="<?=base_url();?>admin/roles/roles" class="btn btn-primary pull-right" style="margin-left:10px;">Back</a>  
            
           
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-validate-additional-methods.js"></script>
