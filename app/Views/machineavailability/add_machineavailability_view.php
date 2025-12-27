<div class="row">
    <form method="post" action="<?= base_url('MachineAvailability/insertData') ?>">
        <div class="col-4">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Add Machine Availability</h5>
                </div>

                <div class="ibox-content">

                    <div class="form-group">
                        <label>Machine Code</label>
                        <input type="text" name="machine_tpm_id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>SAP Notification No</label>
                        <input type="text" name="sap_notification_no" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <input type="text" name="type" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control">
                    </div>

                    <div class="text-center">
                        <a href="<?= base_url('MachineAvailability'); ?>" class="btn btn-outline-dark btn-sm">Back</a>
                        <button class="btn btn-outline-success btn-sm" type="submit">Save</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
