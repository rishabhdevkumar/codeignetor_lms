
    </div>  <!-- wrapper end -->                          
    
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.1.1.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script> 
    <script src="<?php echo base_url(); ?>assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/chosen/chosen.jquery.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>  -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/inspinia.js"></script> -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/pace/pace.min.js"></script>  -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/tabs.js"></script>   -->
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/toastr/toastr.min.js"></script> -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/datatables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/select2/select2.full.min.js"></script> -->
    <script src="<?php echo base_url();?>assets/js/daterangepicker.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/js/plugins/iCheck/icheck.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script> -->
    
        <script>
            // $(document).ready(function () {
            //     $('.i-checks').iCheck({
            //         checkboxClass: 'icheckbox_square-green',
            //         radioClass: 'iradio_square-green',
            //     });
            // });
        </script>
    <script>
    $(document).ready(function()
    {
        var today = new Date();

         $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
//          startDate: '-0m'
        });
        
        //  $(".select2").select2({
        //         placeholder: "Select",
        //         allowClear: true
        //     }); 
            
        $('#frm input').on('keyup', function () 
        { 
            if($(this).val().length>0)
            {
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).parent().find(".error").remove();
                $(this).addClass("is-valid");
            }
        });
        
        $('#frm select').on('change', function () 
        { 
            if($(this).val()!='')
            {
                $(this).parent().removeClass('has-error').addClass('has-success');
                $(this).parent().find(".error").remove();
                $(this).addClass("is-valid");
            }
        }); 
    });
    
  

    </script>
</body>
</html>
