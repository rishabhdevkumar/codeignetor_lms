<?php
$machine_tpm_id      = old('MACHINE_TPM_ID', $machine['MACHINE_TPM_ID']);
$sap_notification_no = old('SAP_NOTIFICATION_NO', $machine['SAP_NOTIFICATION_NO']);
$type               = old('TYPE', $machine['TYPE']);
$from_date = old('FROM_DATE', isset($machine['FROM_DATE']) ? date('Y-m-d\TH:i', strtotime($machine['FROM_DATE'])) : '');
$to_date   = old('TO_DATE', isset($machine['TO_DATE']) ? date('Y-m-d\TH:i', strtotime($machine['TO_DATE'])) : '');
$machine_id         = old('PP_ID', $machine['PP_ID']);

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
    <form method="post" action="<?php echo base_url('MachineAvailability/updateData/' . $machine_id); ?>"
        style="width:100%">
        <input type="hidden" name="machineavailability_id" value="<?php echo $machine_id; ?>">
        <div class="col-sm-3" style="float:left;margin-top:20px"></div>
        <div class="col-sm-6" style="float:left;margin-top:20px">
            <div class="ibox float-e-margins">
                <div style="background-color:#efd6bb; color:#000" class="ibox-title">
                    <h5>Edit Machine Availability</h5>
                </div>

                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 col-xs-12">
                                            <label>SAP Notification No</label>
                                            <input type="text" name="sap_notification_no" readonly class="form-control"
                                                value="<?php echo $sap_notification_no; ?>">
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label>Machine Code</label>
                                            <input type="text" name="machine_tpm_id" class="form-control"
                                                value="<?php echo $machine_tpm_id; ?>">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 col-xs-12">
                                            <label>Type</label>
                                            <input type="text" name="type" class="form-control"
                                                value="<?php echo $type; ?>">
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label>From Date</label>
                                            <input type="datetime-local" name="from_date" class="form-control"
                                                value="<?php echo $from_date; ?>">
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <label>To Date</label>
                                            <input type="datetime-local" name="to_date" class="form-control"
                                                value="<?php echo $to_date; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="<?= base_url('MachineAvailability'); ?>"
                                        class="btn btn-dark btn-sm">Back</a>
                                    <button class="btn btn-info btn-sm" type="submit">Update</button>
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