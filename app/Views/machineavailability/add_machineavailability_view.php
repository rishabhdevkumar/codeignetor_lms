<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
    <form method="post" action="<?= base_url('MachineAvailability/insertData') ?>" style="width:100%">
        <div class="col-sm-3" style="float:left;margin-top:20px"></div>
        <div class="col-sm-6" style="float:left;margin-top:20px">
            <div class="ibox float-e-margins">
                <div style="background-color:#efd6bb; color:#000" class="ibox-title">
                    <h5>Add Machine Availability</h5>
                </div>

                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 col-xs-12">
                                            <label>SAP Notification No</label>
                                            <input type="text" name="sap_notification_no" class="form-control">
                                        </div>
                                        
                                        <div class="col-sm-6 col-xs-12">
                                            <label>Machine Code</label>
                                            <input type="text" name="machine_tpm_id" class="form-control" required>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 col-xs-12">
                                            <label>Type</label>
                                            <input type="text" name="type" class="form-control">
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label>From Date</label>
                                            <input type="datetime-local" name="from_date" class="form-control">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-sm-6 col-xs-12">
                                            <label>To Date</label>
                                            <input type="datetime-local" name="to_date" class="form-control">
                                        </div>

                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="<?= base_url('MachineAvailability'); ?>"
                                        class="btn btn-dark btn-sm">Back</a>
                                    <button class="btn btn-success btn-sm" type="submit">Save</button>
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