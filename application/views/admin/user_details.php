<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-user bg-blue"></i>
                        <div class="d-inline">
                            <h5>User Details  </h5>
                            <span> Users Details</span>
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

                            <li class="breadcrumb-item active" aria-current="page">  Users Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if ($this->session->flashdata('success') != "") : ?>
                <div class="alert alert-success col-md-12 text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="row">

                        <div class="col-md-12 form-group">
                            <div class="col-xl-12 col-md-12 ">
                                <?php
//
                                $percentage = 0;
                                if ($row['aadhar_card_verified'] == 'yes') {
                                    $percentage += 40;
                                }
                                if ($row['address_verified'] == 'yes') {
                                    $percentage += 20;
                                }
                                if ($row['mobile_verified'] == 'yes') {
                                    $percentage += 10;
                                }
                                if ($row['photo_verified'] == 'yes') {
                                    $percentage += 10;
                                }

                                if ($row['email_id_verified'] == 'yes') {
                                    $percentage += 10;
                                }

                                if ($row['office_email_id_verified'] == 'yes') {
                                    $percentage += 10;
                                }
                                ?>
                                <br>
                                <h6>Profile Completion</h6>
                                <h5 class=" fw-700"><?= $percentage ?>%<span class="text-green ml-10"></span></h5>
                                <div class="progress" style="height:4px">
                                    <div class="progress-bar bg-green" style="width:<?= $percentage ?>%"></div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header"><h3>Basic Information</h3></div>
                                <div class="card-body">

                                    <table class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <td> User ID </td>
                                            <th> <?= $row['userid'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Profile Created  </td>
                                            <th> <?= $row['created_on'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> First Name </td>
                                            <th> <?= $row['first_name'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Last Name </td>
                                            <th> <?= $row['last_name'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Registered   Location  </td>
                                            <th > <?php
                                                if (!empty($row['lattitude']) && !empty($row['longitude'])) {
                                                    $loc = GetCityName($row['lattitude'], $row['longitude']);
                                                    echo $loc;
                                                } else {
                                                    echo '--';
                                                }
                                                ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Mobile </td>
                                            <th> <?= $row['mobile'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Alternate Number </td>
                                            <th> <?= $row['alternate_number'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Email Id</td>
                                            <th> <?= $row['email_id'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Date of Birth </td>
                                            <th> <?= $row['dob'] ?>  (YYYY-MM-DD)</th>
                                        </tr>
                                        <tr>
                                            <td>Age </td>
                                            <th>   <?= (!empty($row['dob'])) ? get_age($row['dob']) : '' ?> Years</th>
                                        </tr>
                                        <tr>
                                            <td> Gender </td>
                                            <th> <?= $row['gender'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Address 1</td>
                                            <th> <?= $row['address1'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Address 2</td>
                                            <th> <?= $row['address2'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Country  </td>
                                            <th> <?= $row['countryname'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> State </td>
                                            <th> <?= $row['states_name'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> City </td>
                                            <th> <?= $row['cityname'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Postcode  </td>
                                            <th> <?= $row['postcode'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Bio  </td>
                                            <th> <?= $row['bio'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Payment Mode  </td>
                                            <th> <?= $row['payment_mode'] ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Facebook  </td>
                                            <th> <?= anchor('https://www.facebook.com/' . $row['facebook'], 'https://www.facebook.com/' . $row['facebook']) ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Instagrm  </td>
                                            <th> <?= anchor('https://www.instagram.com/' . $row['instagram'], 'https://www.instagram.com/' . $row['instagram']) ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Linkedin  </td>
                                            <th> <?= anchor('https://www.linkedin.com/' . $row['linkedin'], 'https://www.linkedin.com/' . $row['linkedin']) ?> </th>
                                        </tr>
                                        <tr>
                                            <td> Offie Email ID  </td>
                                            <th> <?= $row['office_email_id']; ?> </th>
                                        </tr>

                                        <tr>
                                            <td> Profile Pic  </td>
                                            <th>
                                                <?php if (!empty($row['profile_pic'])) { ?>
                                                    <button type="button"  data-toggle="modal" data-target="#exampleModalCenter">  <img src="<?= base_url() . $row['profile_pic']; ?>" height="200"  <?php if ($row['profile_pic'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                    </button> <?php } ?>

                                                <div class="modal fade bd-example-modal-lg " id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img style="height:520px;widht:640px" src="<?= base_url() . $row['profile_pic']; ?>"   <?php if ($row['profile_pic'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card" style="min-height: 484px;">
                                <div class="card-header"><h3>User  Documents</h3></div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover table-striped">
                                        <tr>
                                            <td> Driver License </td>
                                            <th>
                                                <button type="button"  data-toggle="modal" data-target="#driver_license_front">
                                                    <img src="<?= base_url() . $row['driver_license_front']; ?>" height="80" width="80" <?php if ($row['driver_license_front'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                </button>
                                                <button type="button"  data-toggle="modal" data-target="#driver_license_back">
                                                    <img src="<?= base_url() . $row['driver_license_back']; ?>" height="80" width="80" <?php if ($row['driver_license_back'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                </button>
                                                <div class="modal fade " id="driver_license_front" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['driver_license_front']; ?>"  />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade " id="driver_license_back" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['driver_license_back']; ?>"   />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr>
                                            <td> Driver License ID </td>
                                            <th>
                                                <?= $row['driver_license_id']; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Pan Card </td>
                                            <th>
                                                <button type="button"  data-toggle="modal" data-target="#pan_card_front">
                                                    <img src="<?= base_url() . $row['pan_card_front']; ?>" height="80" width="80" <?php if ($row['pan_card_front'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                </button>
                                                <button type="button"  data-toggle="modal" data-target="#pan_card_back">
                                                    <img src="<?= base_url() . $row['pan_card_back']; ?>" height="80" width="80" <?php if ($row['pan_card_back'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                </button>
                                                <div class="modal fade " id="pan_card_front" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['pan_card_front']; ?>"  />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade " id="pan_card_back" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['pan_card_back']; ?>"   />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Pan Id </td>
                                            <th> <?= $row['pan_card_id']; ?> </th>
                                        </tr>
                                        <tr>
                                            <td>Aadhar Card</td>
                                            <th>
                                                <button type="button"  data-toggle="modal" data-target="#aadhar_card_front">
                                                    <img src="<?= base_url() . $row['aadhar_card_front']; ?>" height="80" width="80" <?php if ($row['aadhar_card_front'] != '') { ?> class="imgSmall" <?php } ?>/>
                                                </button>
                                                <button type="button"  data-toggle="modal" data-target="#aadhar_card_back">
                                                    <img src="<?= base_url() . $row['aadhar_card_back']; ?>" height="80" width="80" <?php if ($row['aadhar_card_back'] != '') { ?> class="imgSmall" <?php } ?>/>

                                                </button>
                                                <div class="modal fade " id="aadhar_card_front" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['aadhar_card_front']; ?>"  />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal fade " id="aadhar_card_back" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body" >
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                                <div class="row">
                                                                    <center>
                                                                        <img src="<?= base_url() . $row['aadhar_card_back']; ?>"   />
                                                                    </center>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Aadhar Card ID: </td>
                                            <th>   <?= $row['aadhar_card_id']; ?> </th>
                                        </tr>

                                    </table>


                                    <!--Verification-->
                                    <div class="card-header"><h3>Document Verification</h3></div>
                                    <br>
                                    Govt ID (40%), Address (20%), Mobile (10%), Photo (10%), personal Mail ID (10%) and Official Mail ID(10%) <br>  <br>
                                    <form method="post">
                                        <table class="table  table-hover table-striped table-bordered">
                                            <tr>
                                                <td> Mobile Verified </td>
                                                <th>
                                                    <select name="mobile_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['mobile_verified']) && $row['mobile_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['mobile_verified']) && $row['mobile_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>

                                                </th>
                                            </tr>
                                            <tr>
                                                <td> Email Verified </td>
                                                <th>

                                                    <select name="email_id_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['email_id_verified']) && $row['email_id_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['email_id_verified']) && $row['email_id_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td> Office email id  Verified </td>
                                                <th>
                                                    <select name="office_email_id_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['office_email_id_verified']) && $row['office_email_id_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['office_email_id_verified']) && $row['office_email_id_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>

                                            <tr>
                                                <td> Driver license Verified </td>
                                                <th>
                                                    <select name="driver_license_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['driver_license_verified']) && $row['driver_license_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['driver_license_verified']) && $row['driver_license_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>

                                            <tr>
                                                <td> Pan card Verified </td>
                                                <th>
                                                    <select name="pan_card_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['pan_card_verified']) && $row['pan_card_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['pan_card_verified']) && $row['pan_card_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td> Photo Verified </td>
                                                <th>
                                                    <select name="photo_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['photo_verified']) && $row['photo_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['photo_verified']) && $row['photo_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td> Address Verified </td>
                                                <th>
                                                    <select name="address_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['address_verified']) && $row['address_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['address_verified']) && $row['address_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td> Aadhar card Verified </td>
                                                <th>

                                                    <select name="aadhar_card_verified" class="form-control">
                                                        <option value="yes" <?= (isset($row['aadhar_card_verified']) && $row['aadhar_card_verified'] == 'yes') ? 'selected' : '' ?>>Yes</option>
                                                        <option value="no"  <?= (isset($row['aadhar_card_verified']) && $row['aadhar_card_verified'] == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td  class="text-right">
                                                    <input type="submit" name="update" value="Update" class="btn btn-block btn-success"/>
                                                    </th>
                                            </tr>

                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($user_vehicles)) { ?>
                            <hr>
                            <?php
                            $i = 1;
                            foreach ($user_vehicles as $item) {
                                ?>
                                <div class="col-md-6" style="padding:5px 35px;">
                                    <div class="card-header"><h3>Vehicle No : <?= $i ?></h3></div>
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>Brand</th>
                                            <td><?= $item->v_make ?></td>
                                        </tr>
                                        <tr>
                                            <th>Model</th>
                                            <td><?= $item->v_model ?></td>
                                        </tr>
                                        <tr>
                                            <th>Number Plate</th>
                                            <td><?= $item->number_plate ?></td>
                                        </tr>
                                        <tr>
                                            <th>REG Year</th>
                                            <td><?= $item->year ?></td>
                                        </tr>
                                        <tr>
                                            <th>Color</th>
                                            <td><?= $item->color ?></td>
                                        </tr>
                                        <tr>
    <?php 
    $vehicle_id=$item->id; $user_id=$item->user_id;
 $query="select * from vehicle_images where user_id='$user_id' and vehicle_id='$vehicle_id' ";
 $vehicle_images=$this->db->query($query)->result();
    ?>
                                            <th>Image</th>
                                            <td>
                                                <?php 
                                            if(!empty($vehicle_images)){
                                                foreach($vehicle_images as $img){ ?>
                                                <img src="<?= base_url($img->vehicle_image) ?>" height="50" width="50">


                                                
                                                <?php }
                                            }else{?>

                                    <img src="<?= base_url($item->vehicle_picture) ?>" height="50" width="50">
                                           <?php  }?>

                                            </td>

                                        </tr>

                                    </table>
                                </div>

                                <?php
                                $i++;
                            }
                        }
                        ?>


                    </div>

                    <!--                    <div class="card-header">
                                            <h3>Users Details</h3>
                                        </div>-->


                </div>
                <!-- <div class="panel-footer">
                  <a href="<?= base_url(); ?>admin/register/venues" class="btn btn-primary pull-right" style="margin-left:10px;">Cancel</a>
                  <button type="submit" class="btn btn-primary pull-right" id="btnSubmit">Submit</button>
                </div> -->
            </div>
            </form>
        </div>
    </div>

</div>

<style>
    .table td, .table th {
        padding: 0.45rem !important;

    }
    #overlay{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: #000;
        opacity: 0.7;
        filter: alpha(opacity = 70) !important;
        display: none;
        z-index: 100;
    }

    #overlayContent{
        position: fixed;
        width: 100%;
        top: 100px;
        text-align: center;
        display: none;
        overflow: hidden;
        z-index: 100;
    }
    #imgBig, .imgSmall{
        cursor: pointer;
    }

</style>
<style type="text/css">
    .margintop7
    {
        margin-top: 7px;
    }
</style>
<script>
    $(document).ready(function () {

//apply the 'elevateZoom' plugin to the image
        /*$("#mainimage").elevateZoom({
         zoomType: "lens",
         lensShape: "round",
         lensSize: 300
         });*/
//alert('Hai');
//on page load hide the container which plugin is applied
        /*$('#image2-container').hide();

         $("#image-wrapper").click(function () {
         // hide matched element if shown, shows if element is hidden
         $('#image1-container, #image2-container').toggle();
         });*/
        $(".imgSmall").click(function () {
            $("#imgBig").attr("src", $(this).attr('src'));
            $("#overlay").show();
            $("#overlayContent").show();
        });

        $("#imgBig").click(function () {
            $("#imgBig").attr("src", "");
            $("#overlay").hide();
            $("#overlayContent").hide();
        });

    });

</script>
