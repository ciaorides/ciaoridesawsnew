<style type="text/css">
    .col-md-12 {
        /* margin-left: -30px !important;*/
    }
    .login-container .login-box .login-body {border-radius: 5px }
</style>
<div class="login-container">
    <div class="login-box animated fadeInDown">
        <div class="login-logo" style=""><img src="<?= base_url('admin_assets/ciao_white.jpg') ?>" width="130px"></div>
        <div class="login-body">
            <div class="login-title">
                <?php
                if (!validation_errors()) {
                    ?>
                    <strong>Welcome</strong>, Please login
                    <?php
                } else {
                    ?>
                    <span><strong style="color: red">Error</strong>: <?= validation_errors(); ?></span>
                    <?php
                }
                ?>
            </div>

            <?php
            $attributes = array('class' => 'form-horizontal', 'id' => 'myForm');
            echo form_open_multipart('admin/login/checklogin', $attributes);
            ?>
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    $data = array(
                        'name' => 'username',
                        'id' => 'username',
                        'placeholder' => 'Username',
                        'class' => 'form-control',
                    );
                    echo form_input($data);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <?php
                    $data = array(
                        'name' => 'password',
                        'id' => 'password',
                        'placeholder' => 'Password',
                        'class' => 'form-control',
                    );
                    echo form_password($data);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <!-- <a href="#" class="btn btn-link btn-block">Forgot your password?</a> -->
                </div>
                <div class="col-md-6">
                    <?php
                    $data = array(
                        'type' => 'submit',
                        'name' => 'submit',
                        'value' => 'Log In',
                        'class' => 'btn btn-info btn-block',
                    );
                    echo form_submit($data);
                    ?>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="login-footer">
            <div class="text-center">
                <br>
                &copy; <?= date('Y') ?> CIAO Rides
            </div>
            <!-- <div class="pull-right">
                    <a href="#">About</a> |
                    <a href="#">Privacy</a> |
                    <a href="#">Contact Us</a>
            </div> -->
        </div>
    </div>
</div>

