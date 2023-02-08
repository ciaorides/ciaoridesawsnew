<!-- START BREADCRUMB -->

<!-- END BREADCRUMB -->
<!-- END PAGE TITLE -->
<!-- PAGE CONTENT WRAPPER -->
<div class="main-content">
<div class="container-fluid">
      
<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-user bg-green"></i>
                        <div class="d-inline">
                            <h5>Role Management</h5>
                            <span>Roles List</span>
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
                                <a href="<?php echo site_url();?>admin/dashboard">Roles Management</a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page"> Roles List </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
      
      

<!--
 <div class="page-content">
                 <div class="page-header-1">
 
              <?php if( in_array('a',$roleResponsible['roles'])){?>
                <div class="pull-right ">   
                  <a href="<?php echo base_url();?>admin/roles/add_roles" class="btn btn-success btn-sm" type="button"><i class="fa fa-plus fa-lg"></i> Add </a>                          
                  <input type="hidden" name="hiv" id="hiv" value="0" />
               </div>
              <?php }?>
            </div>
            </div>
-->
      
      
      
      <!-- /.page-header -->


            
  <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-6 col-xs-6">
                            <h3> Roles List </h3>
                        </div>

                        <div class="col-md-6 col-xs-6">
                             <?php if( in_array('a',$roleResponsible['roles'])){?>
                            <h3 class="panel-title" style="float: right;">
                                
                                <a href="<?php echo base_url();?>admin/roles/add_roles" class="btn btn-success btn-sm" type="button"><i class="fa fa-plus fa-lg"></i> Add </a>
                            
                            </h3>
                            
                               <?php }?>
                            
                        </div>
                    </div>
                    <div class="card-block">
            
            
          <div class="table-responsive">
          <div id="wait" style="display:none;width:69px;height:89px;position:absolute;top:50%;left:50%;padding:2px; z-index:99999999"><img src='<?=base_url();?>assets/img/demo_wait.gif' width="64" height="64" /></div>
            <input type="hidden" id="atpagination" value="">
            <input type="hidden" id="paginationlength" value="">
            <table id="users" class="table datatable table-striped">

            <!-------- Search Form ---------->
            <form id="fees_form" name="search_fees" method="post" class="pull-right">
                
                <div class="row">
              <div class="col-md-3 col-md-offset-3" style="padding:0">
                  <input type="text" name="search_text_1" id="search_text_1" placeholder="Type keyword to search" class="input-sm form-control custom-input" style="margin-left:5px;">
              </div>
              <div class="col-md-2">
                  <select name="search_on_1" id="search_on_1" class="form-control input-sm custom-input">
                      
                      <option value="1">Role Name</option>

                  </select>
                  
              </div>
              <div class="col-md-2">
                  <select name="search_at_1" id="search_at_1" class="input-sm form-control custom-input">
                      <option value="">Contains</option>
                      <option value="after">Starts with</option>
                      <option value="before">Ends with</option>
                  </select>
                  
              </div>
              <div class="col-md-2">
              <button type="button" id="search_user" class="btn btn-info margin_search" style=""><i class="fa fa-search icon-style"></i></button>
              <a class="btn btn-danger" style="display:none;" id="searchreset" href="<?php echo base_url('admin/roles'); ?>"><li class="fa fa-minus icon-style"></li></a>
              </div>
                    
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
</div> 
<script>
    var dtabel;
    var search_text_1;
    var search_on_1;
    var search_at_1;
    var ispage;
    var url = '<?php echo base_url();?>';
    
    $(document).ready(function () {
        dtabel = $('#users').DataTable({
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
            "url": "<?php echo base_url('admin/roles/all_roles'); ?>",
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
            { "title": "Role Name", "name":"title","orderable": false, "data":"rolename", "width":"0%" },
           
           // { "title": "Employees", "name":"created_on","orderable": false, "data":"id", "width":"0%" },
            { "title": "Created Date", "name":"created_on","orderable": false, "data":"created_on", "width":"0%" },
           // { "title": "Modified Date", "name":"modified_on","orderable": false, "data":"modified_on", "width":"0%" },
          <?php if( in_array('s',$roleResponsible['roles'])){?>
            { "title": "Status", "name":"action", "orderable": false, "deafultContent":"", "data": "id", "width":"0%", "class":"td_action"},
          <?php }?>

          <?php if( in_array('e',$roleResponsible['roles'])){?>  
            {"title": "Actions", "name":"action", "orderable": false, "deafultContent":"", "data": "id", "width":"0%", "class":"td_action"}, 
          <?php }?> 
                   
            
        ],
        "fnCreatedRow": function(nRow, aData, iDataIndex) 
        {           

          var image = '<img src="'+url+aData['image_path']+'" height="100" width="150">';
          //$(nRow).find('td:eq(2)').html(image);

          var view ='<a  title="view" href="'+url+'admin/roles/view_role_employees/'+aData['id']+'" class="btn btn-primary btn-condensed">Employees</a>';
         // $(nRow).find('td:eq(2)').html(view);

          if(aData['status'] == "1")
          {
            var action = '<a title="Click to Inactive" href="'+url+'admin/roles/change_role_status/'+aData['id']+'/0" class="btn btn-success btn-condensed">Active</a>';
          }
          else
          {
            var action = '<a title="Click to Active" href="'+url+'admin/roles/change_role_status/'+aData['id']+'/1" class="btn btn-danger btn-condensed">Inactive</a>';
          }
          <?php if( in_array('s',$roleResponsible['roles'])){?>
          $(nRow).find('td:eq(3)').html(action);
        <?php }?>

        
          var action ='<a class="btn btn-warning btn-condensed" title="edit" href="'+url+'admin/roles/edit_roles/'+aData['id']+'"><i class="fa fa-edit"></i></a>';
         
         
         <?php if( (in_array('s',$roleResponsible['roles'])) && (in_array('e',$roleResponsible['roles'])) ){?>
        
          $(nRow).find('td:eq(4)').html(action);
        
        <?php }else if((!in_array('s',$roleResponsible['roles'])) && (in_array('e',$roleResponsible['roles'])) ){?>
           $(nRow).find('td:eq(3)').html(action);

        <?php }?>



        },
        "fnDrawCallback": function( oSettings ) {            
            var info = this.fnPagingInfo().iPage;
            $("#atpagination").val(info+1);
            $("td:empty").html("&nbsp;");
        },
    });
    $("#search_user").click(function(){
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
    var table = $('#users').DataTable();
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
</script>

