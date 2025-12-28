<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?php echo $title; ?></h5>
            </div>

            <div class="ibox-content">

                <div class="row">
                    <div class="col-sm-3">
                        <a href="<?php echo base_url(); ?>MachineAvailability/add" class="btn btn-outline-dark btn-sm">Add</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Machine Code</th>
                                <th>Notification No</th>
                                <th>Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>View</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                if ($machineavailability != false) {
                                foreach ($machineavailability as $M => $v) { 
                            ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $machineavailability[$M]['MACHINE_TPM_ID']; ?></td>
                                        <td><?php echo $machineavailability[$M]['SAP_NOTIFICATION_NO']; ?></td>
                                        <td><?php echo $machineavailability[$M]['TYPE']; ?></td>
                                        <td><?php echo $machineavailability[$M]['FROM_DATE']; ?></td>
                                        <td><?php echo $machineavailability[$M]['TO_DATE']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('MachineAvailability/view/' . $machineavailability[$M]['PP_ID']); ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('MachineAvailability/edit/' . $machineavailability[$M]['PP_ID']); ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                            $i++;
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
