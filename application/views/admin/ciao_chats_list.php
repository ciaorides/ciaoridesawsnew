<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="fa  fa-comment-alt bg-green"></i>
                        <div class="d-inline">
                            <h5>CIAO Chat  </h5>
                            <span>List CIAO Chat   </span>
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

                            <li class="breadcrumb-item active" aria-current="page">  CIAO Chat  Queries </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <?php if ($this->session->flashdata('success') != "") : ?>
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-block">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>  <th>User ID</th>
                                            <th>User Name</th>
                                            <th>Mobile</th>
                                            <th>Message</th>
                                            <th>Date Time</th>
                                            <th>Go To Chat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($chat_list) {
                                            $i = 1;
                                            foreach ($chat_list as $al) {
                                                $userd = $this->db->where('id', $al->user_id)->get('users')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= $userd->userid ?>
                                                    </td>
                                                    <td><b ><a style="color:green" target="_blank" href="<?= base_url('admin/register/user_details/') ?><?= $al->user_id ?>" ><?php echo $userd->first_name . '' . $userd->last_name; ?></a>   </b></td>
                                                    <td><?= $userd->mobile ?></td>
                                                    <td><?= $al->message ?></td>
                                                    <td><?= $al->created_at ?></td>
                                                    <td><a  href="<?= base_url('admin/home/ciao_view/' . $al->user_id) ?>"  class="btn btn-outline-primary"><i class="fa fa-comment"></i>&nbsp;Chat </a> </td>
                                                </tr>

                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>


                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
