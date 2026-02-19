<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title . " | " . $settings["COMPANY_NAME"]; ?></title>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/landing_page.css" rel="stylesheet">
    <script>
        var baseurl = '<?php echo base_url(); ?>';
    </script>
</head>

<body style="background-color: #FFF0D1">
    <div class="container">
        <div class="page">
            <center><img class="img-responsive" src="<?php echo base_url(); ?>assets/img/logoHeader.png" /></center>
            <div class="row mt-5">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <!-- <center><img src="<?php echo base_url() ?>uploads/logo-white.png" class="img-responsive rounded" style=" margin-bottom: 10px !important;"></center> -->

                    <h3>Pakka Limited</h3>
                    </p>
                    <p>
                        <center><?php echo $title; ?></center>
                    </p>
                    <form class="m-t" role="form" action="<?= base_url('Auth/Attemptlogin') ?>" autocomplete="off" method="post" id="frm">
                       <?= csrf_field() ?>
                        <div class="form-group">
                            <input type="text" name="user_name" id="user_name" class="form-control <?php if (isset($validation)) $validation->getError('user_name'); ?>" placeholder="User Name" value="<?php echo set_value('user_name'); ?>" autocomplete="off">
                             <?php if (isset($validation)) : ?>
                                    <div class="error">
                                        <?= $validation->getError('user_name'); ?>
                                    </div>
                                <?php endif; ?>
                        </div>


                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control <?php if (isset($validation)) $validation->getError('password'); ?>" placeholder="Password" value="<?php echo set_value('password'); ?>" autocomplete="off">
                            
                                <?php if (isset($validation)) : ?>
                                    <div class="error">
                                        <?= $validation->getError('password'); ?>
                                    </div>
                                <?php endif; ?>
                            
                        </div>


                        <div id="status">
                           <?= session()->getFlashdata('message'); ?>
                        </div>
                        <button type="submit" style="background-color:#A3243C;" class="btn block full-width m-b">Login</button>
                    </form>
                    <!--<p class="m-t"> <center><small>&copy; 2020-2021</small> </center></p>-->
                </div>
                <div class="col-sm-4"></div>
            </div>

            
        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#frm input').on('keyup', function() {
                if ($(this).val().length > 0) {
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).parent().find(".error").remove();
                    $(this).addClass("is-valid");
                }
            });

            $('#frm select').on('change', function() {
                if ($(this).val() != '') {
                    $(this).parent().removeClass('has-error').addClass('has-success');
                    $(this).parent().find(".error").remove();
                    $(this).addClass("is-valid");
                }
            });
        });
    </script>
</body>

</html>