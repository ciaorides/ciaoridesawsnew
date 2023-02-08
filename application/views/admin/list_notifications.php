<!-- START BREADCRUMB -->

<ul class="breadcrumb">

  <li><a href="#">Home</a></li>

  <li class="active">Notifications</li>

</ul>

<!-- END BREADCRUMB -->

<!-- END PAGE TITLE -->



<!-- PAGE CONTENT WRAPPER -->

<div class="page-content-wrap">



  <div class="row">

    <div class="col-md-12">



      <!-- START DATATABLE EXPORT -->

      <div class="panel panel-default">

        <div class="panel-heading">

        <div class="col-md-6 col-xs-6">

          <h2><span class="fa fa-list"></span> Notifications</h2>

        </div>

        <!-- <div class="col-md-6 col-xs-6">

          <h3 class="panel-title" style="float: right;"><a href="<?=base_url();?>admin/register/add_discount_links" class="btn btn-success">Add</a></h3>

        </div> -->

        </div>

        <div class="panel-body">

          <div class="table-responsive">

          <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?=base_url();?>assets/img/demo_wait.gif' width="64" height="64" /></div>

            <input type="hidden" id="atpagination" value="">

            <input type="hidden" id="paginationlength" value="">

            <table id="notifications" class="table datatable table-striped">



            <!-------- Search Form ---------->

            <form id="fees_form" name="search_fees" method="post" class="pull-right">              

              <div class="col-md-2 col-md-offset-3" style="padding:0">

                  <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">

                      <option value="1">Title</option>

                     <option value="2">Description</option>

                  </select>

                  <i class="fa fa-angle-down arrow-icon" id="arrow-icon"></i>

              </div>

              <div class="col-md-3">              

                  <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">

              </div>

              <div class="col-md-2">

                  <select name="search_at_1" id="search_at_1" class="input-sm form-control custom-input">

                      <option value="">Contains</option>

                      <option value="after">Starts with</option>

                      <option value="before">Ends with</option>

                  </select>

                  <i class="fa fa-angle-down arrow-icon" id="arrow-icon"></i>

              </div>

              <div class="col-md-2">

              <button type="button" id="search_venue" class="btn btn-info margin_search" style=""><i class="fa fa-search icon-style"></i></button>

              <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/register/notifications'); ?>"><li class="fa fa-minus icon-style"></li></a>

              </div>

            </form> 

            <!-------- /Search Form ---------->



            </table>                                            

          </div>

        </div>

      </div>

      <!-- END DATATABLE EXPORT -->      

    </div>

  </div>

</div>         

<!-- END PAGE CONTENT WRAPPER -->

<script src="<?php echo base_url();?>assets/datatables/jquery.dataTables.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/datatables/dataTables_custom.js" type="text/javascript"></script>

<!--Load JQuery-->

<script src="<?php echo base_url();?>assets/datatables/jquery.dataTables.min.js"></script>

<script src="<?php echo base_url();?>assets/datatables/dataTables.bootstrap.min.js"></script>

<script>

    var dtabel;

    var search_text_1;

    var search_on_1;

    var search_at_1;

    var ispage;

    var url = '<?php echo base_url();?>';

    $(document).ready(function () {

        dtabel = $('#notifications').DataTable({

            "processing": true,

            "serverSide": true,

            "bStateSave": true,

            "language": {

            "emptyTable": "No Records Found!",

        },

        dom: '<"html5buttons" B>lTgtp',

        buttons: [],

        "aLengthMenu": [10, 20, 50, 100],

        "destroy": true,

        "ajax": {

            "url": "<?php echo base_url('admin/register/all_notifications'); ?>",

            "type":"POST",

            beforeSend: function() {

              $("#wait").css("display", "block");

            },

            "data":function (d){

                d.search_text_1 = search_text_1;

                d.search_on_1 = search_on_1;

                d.search_at_1 = search_at_1;

            },

            "dataSrc": function ( jsondata ) {

                $("#wait").css("display", "none");

                return jsondata['data'];

            }

        },

        "columns": [

            { "title": "S.No", "name":"sno", "orderable": false, "data":"sno", "width":"0%" },

         

           

            { "title": "Title", "name":"title","orderable": false, "data":"title", "width":"0%" },

            { "title": "Description", "name":"description","orderable": false, "data":"description", "width":"0%" },

            

            { "title": "Created Date", "name":"created_on","orderable": false, "data":"created_on", "width":"0%" },

        ],

        "fnCreatedRow": function(nRow, aData, iDataIndex) 

        {

          var status ='<a href="'+url+'admin/register/delete_venue/'+aData['id']+'" class="btn btn-danger btn-condensed" title="Delete Venue"><i class="fa fa-trash"></i></a>';

          //$(nRow).find('td:eq(1)').html(status);



         //  if(aData['status'] == "Active")

         //  {

         //    var status ='<a title="Click to Inactive" href="'+url+'admin/register/change_venue_status/'+aData['id']+'/Inactive" class="btn btn-success btn-condensed">Active</a>';

         //  }

         //  else

         //  {

         //    var status ='<a title="Click to Active" href="'+url+'admin/register/change_venue_status/'+aData['id']+'/Active" class="btn btn-danger btn-condensed">In Active</a>';

         //  }

         // $(nRow).find('td:eq(2)').html(status);



        



        var action ='<a class="btn btn-warning btn-condensed" title="edit" href="'+url+'admin/register/edit_categories/'+aData['id']+'"><i class="fa fa-edit"></i></a> <a onclick="return confirm('+"'Are you sure want to delete?'"+');" class="btn btn-danger btn-condensed" title="delete" href="'+url+'admin/register/delete_categories/'+aData['id']+'"><i class="fa fa-trash"></i></a>';

          //$(nRow).find('td:eq(1)').html(action);

        },

        "fnDrawCallback": function( oSettings ) {            

            var info = this.fnPagingInfo().iPage;

            $("#atpagination").val(info+1);

            $("td:empty").html("&nbsp;");

        },

    });

    $("#search_venue").click(function(){

        if($("#search_text_1").val()!=""){

            $("#search_text_1").css('background', '#ffffff');

            setallvalues();

            dtabel.draw();

        }else{

         $("#search_text_1").css('background', '#ffb3b3');

         $("#search_text_1").focus();

                     return false;

        }

    });

});



function setallvalues(){

    search_text_1 = $("#search_text_1").val();

    search_on_1 = $("#search_on_1").val();

    search_at_1 = $("#search_at_1").val();

    var table = $('#notifications').DataTable();

    var info = table.page.info();

    $("#atpagination").val((info.page+1));

    if(search_text_1!=""){

        $("#searchreset").show();            

    }

    searchAstr = '';

}



function getpagenumber()

{

    return $("#atpagination").val() / $("#paginationlength").val();

}



$("#search_on_1").change(function() {

    var val = $(this).val();

    if(val == 5)

    {

      $(".search").hide();

      $("#search_text_1").datepicker();

    }

    else if(val == 6)

    {

      $(".search").hide();

      $("#search_text_1").datepicker();

    }

    else

    {     

      $(".search").show();  

      $("#search_text_1").datepicker('remove');     

    }

});

</script>



<!-- START BREADCRUMB -->

<ul class="breadcrumb">

  <li><a href="#">Home</a></li>

  <li><a href="#">Notifications</a></li>

  <li class="active">Add Notifications</li>

</ul>

<!-- END BREADCRUMB -->



<!-- PAGE CONTENT WRAPPER -->

<div class="page-content-wrap">

  <div class="row">

    <div class="col-md-12">

      <?php 

      $attributes = array('class' => 'form-horizontal', 'id' => 'validation');

      echo form_open_multipart('admin/register/update_notifications', $attributes); 

      ?>

        <div class="panel panel-default">

          <div class="panel-heading">

            <h2 class="panel-title">Add Notifications</h2>            

          </div>

          <?php if($this->session->flashdata('success') != "") : ?>                

            <div class="alert alert-success" role="alert">

              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

              <?=$this->session->flashdata('success');?>

            </div>

          <?php endif; ?>

          <div class="panel-body">



            <div class="form-group">

              <label for="title" class="col-md-3 col-xs-12 control-label">Enter Title</label>

              <div class="col-md-6 col-xs-12">                

                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>

                <input type="text" class="form-control" name="title" id="title" />

              </div>

            </div>

            <div class="form-group">

              <label for="title" class="col-md-3 col-xs-12 control-label">Enter Description</label>

              <div class="col-md-6 col-xs-12">                

                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>

                <input type="text" class="form-control" name="description" id="description" required="" />

              </div>

            </div>



          </div>

          <div class="panel-footer">

            <a href="<?=base_url();?>admin/register/notifications" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>    

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

