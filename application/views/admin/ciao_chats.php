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
            <div class="col-md-12 col-lg-12">
                <div class="col-lg-3 col-md-12"></div>
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Chat</h3>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                    <li><i class="ik ik-minus minimize-card"></i></li>
                                    <!--<li><i class="ik ik-x close-card"></i></li>-->
                                </ul>
                            </div>
                        </div>
                        <div class="card-body chat-box scrollable msg_container_base" style="height:400px;">
                            <ul class="chat-list " id="chat_log">
                                <li></li>
                            </ul>
                        </div>
                        <div class="card-footer chat-footer">
                            <form action="" method="post" id="myForm">
                                <div class="input-wrap">
                                    <input type="text" name="message" autocomplete="off" id="message" placeholder="Type and enter" class="form-control">
                                </div>

                                <button type="submit" id="submit" class="btn btn-icon btn-success" style="margin-top: 10px"><i class="fa fa-paper-plane" ></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .msg_container_base{font-size: 14px;}
    .help-text{ color: #fff;margin-left: 7x;
                font-size: 10px;}
    .help-text2{ color: #afafaf;margin-left: 7x;
                 font-size: 10px;}
    .card .chat-box .chat-list .chat-item {
        list-style: none;
        margin-top: 5px !important;
    }
</style>
<script>
    var userid_ = "<?= $user_id ?>";
    function get_data() {
        $.ajax({
            type: "POST",
            dataType: 'html',
            method: 'POST',
            data: {'user_id': userid_},
            url: "<?= base_url('admin/home/get_chat_list_api') ?>",
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                //alert(data.length);
                var selOpts = "";
                var isScean = '';
                for (i = 0; i < data.length; i++)
                {
                    var id = data[i]['id'];
                    var message = data[i]['message'];
                    var created_at = data[i]['created_at'];
                    var utype = data[i]['type'];
                    var is_scean = data[i]['is_scean'];
                    if (is_scean === 'yes') {
                        isScean = '<i class="ik ik-check" aria-hidden="true" style="color:blue;"></i><i class="ik ik-check" aria-hidden="true" style="position: absolute;right: 30px;color:blue;"></i>';
                    } else {
                        isScean = '';
                    }
                    if (utype === 'admin') {
                        selOpts += '<li class="odd chat-item"> <div class="chat-content"><div class="box bg-success">' + message + ' ' + isScean + ' <sub class="help-text"><br>' + created_at + '</sub></div> </div></li>';
                    } else {
                        selOpts += '<li class="chat-item"> <div class="chat-content"><div class="box bg-light-inverse">' + message + ' <sub class="help-text2">' + created_at + '</sub></div> </div></li>';
                    }

                }
                $('#chat_log').html(selOpts);
                $(".msg_container_base").stop().animate({scrollTop: $(".msg_container_base")[0].scrollHeight}, 1000);
            },
            error: function (se) {
                console.log(se);
            }
        });
    }
    $(document).ready(function () {
        get_data();
    });
    $('.msg_container_base').stop().animate({
        scrollTop: $('.msg_container_base')[0].scrollHeight
    }, 4000);
    $("#submit").click(function (e) {
        var data = $("#message").val();
        //console.log(data);
//        alert("Test");

        clearInput();
        if (data !== '') {
            get_data();
            postajax(userid_, data, 'admin');
        }

        $('#chat_log').append('<li class="odd chat-item"><div class="chat-content"><div class="box bg-light-inverse">' + data + '</div> <br></div><div class="chat-time">now</div></li>');


    });

    function clearInput() {
        $("#myForm :input").each(function () {
            $(this).val(''); //hide form values
        });
    }
    $("#myForm").submit(function () {
        return false; //to prevent redirection to save.php
    });
    function postajax(userid, message, type) {
        $.ajax({
            type: "POST",
            dataType: 'html',
            method: 'POST',
            data: {'user_id': userid_, 'message': message, 'type': 'admin'},
            url: "<?= base_url('admin/home/post_chat_list_api') ?>",
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                //alert(data.length);<i class="ik ik-check" aria-hidden="true"></i><i class="ik ik-check" aria-hidden="true"></i>
                var selOpts = "";
                var isScean = '';
                for (i = 0; i < data.length; i++)
                {
                    var id = data[i]['id'];
                    var message = data[i]['message'];
                    var created_at = data[i]['created_at'];
                    var utype = data[i]['type'];
                    var is_scean = data[i]['is_scean'];
                    if (is_scean === 'yes') {
                        isScean = '<i class="ik ik-check" aria-hidden="true"></i><i class="ik ik-check" aria-hidden="true" style="position: absolute;right: 30px;"></i>';
                    } else {
                        isScean = '';
                    }
                    if (utype == 'admin') {
                        selOpts += '<li class="odd chat-item"> <div class="chat-content"><div class="box bg-success">' + message + ' ' + isScean + ' <sub class="help-text"><br>' + created_at + '</sub></div> </div></li>';
                    } else {
                        selOpts += '<li class="chat-item"> <div class="chat-content"><div class="box bg-light-inverse">' + message + ' <sub class="help-text2">' + created_at + '</sub></div> </div></li>';
                    }

                }
                $('#chat_log').html(selOpts);
                $(".msg_container_base").stop().animate({scrollTop: $(".msg_container_base")[0].scrollHeight}, 1000);
            },
            error: function (se) {
                console.log(se);
            }
        });
    }
    window.setInterval(function () {
        /// call your function here
        get_data()
    }, 10000);
</script>