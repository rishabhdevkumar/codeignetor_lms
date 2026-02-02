<div class="card mt-4">
<div class="row">
    <div class="col-12">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
                <h5><?php echo $title; ?></h5>
                <a href="<?php echo base_url(); ?>MachineAvailability/add" class="btn-success btn-outline-warning text-dark btn-sm">Add New</a>
            </div>

                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead class="text-center">
                            <tr>
                                <th style="background-color:#efd6bb; color:#000">Sl.No</th>
                                <th style="background-color:#efd6bb; color:#000">Machine Code</th>
                                <th style="background-color:#efd6bb; color:#000">Notification No</th>
                                <th style="background-color:#efd6bb; color:#000">Type</th>
                                <th style="background-color:#efd6bb; color:#000">From Date</th>
                                <th style="background-color:#efd6bb; color:#000">To Date</th>
                                <th style="background-color:#efd6bb; color:#000">View</th>
                                <th style="background-color:#efd6bb; color:#000">Edit</th>
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

<script>
	$(document).ready(function() {

		$('.dataTables-example').
		DataTable({
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [{
					extend: 'copy',
					title: 'Machine',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'csv',
					title: 'Machine',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'excel',
					title: 'Machine',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'pdf',
					title: 'Machine',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'print',
					title: 'Machine',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					},

					customize: function(win) {
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');

						$(win.document.body).find('table')
							.addClass('compact')
							.css('font-size', 'inherit');

					}
				}
			]

		});



	});

	function del(id) {
		var x = confirm("Do you want to delete this record ???");
		if (x == true) {
			return true;
		} else {
			return false;
		}
	}
</script>
