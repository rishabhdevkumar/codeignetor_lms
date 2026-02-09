<?php
$machine_tpm_id      = old('MACHINE_TPM_ID', $machine['MACHINE_TPM_ID']);
$sap_notification_no = old('SAP_NOTIFICATION_NO', $machine['SAP_NOTIFICATION_NO']);
$type                = old('TYPE', $machine['TYPE']);
$from_date           = old('FROM_DATE', $machine['FROM_DATE']);
$to_date             = old('TO_DATE', $machine['TO_DATE']);
$machine_id          = old('PP_ID', $machine['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
    <form id="frm" autocomplete="off" method="POST" style="width:100%">
        <input type="hidden" name="machineavailability_id" value="<?php echo $machine_id; ?>">
        <div class="col-sm-3" style="float:left;margin-top:20px"></div>
        <div class="col-sm-6" style="float:left;margin-top:20px">

            <div class="ibox float-e-margins">
                <div style="background-color:#efd6bb; color:#000" class="ibox-title">
                    <h5><?php echo $title; ?><small> </small></h5>
                </div>
                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>Machine TPM ID</label>
                                            <input type="text" class="form-control" name="machine_tpm_id" value="<?php echo $machine_tpm_id; ?>" readonly>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label>SAP Notification No</label>
                                            <input type="text" class="form-control" name="sap_notification_no" value="<?php echo $sap_notification_no; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>Type</label>
                                            <input type="text" class="form-control" name="type" value="<?php echo $type; ?>" readonly>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label>From Date</label>
                                            <input type="date" class="form-control" name="from_date" value="<?php echo date('Y-m-d', strtotime($from_date)); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>To Date</label>
                                            <input type="date" class="form-control" name="to_date" value="<?php echo date('Y-m-d', strtotime($to_date)); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <a class="btn btn-dark btn-sm" href="<?php echo base_url() ?>MachineAvailability">Back</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
