<div class="card mt-4">
<div class="row">
	<div class="col-12">
		<?php if (session()->getFlashdata('error')): ?>
			<div class="alert alert-danger">
				<?= session()->getFlashdata('error'); ?>
			</div>
		<?php endif; ?>

		<?php if (session()->getFlashdata('success')): ?>
			<div class="alert alert-success">
				<?= session()->getFlashdata('success'); ?>
			</div>
		<?php endif; ?>
			<div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
				<h5><?php echo $title; ?> Details</h5>
				<a href="<?php echo base_url(); ?>Machine/add" class="btn-success btn-outline-warning text-dark btn-sm">Add Machine</a>
			</div>

			 <div class="card-body">
				<div class="table-responsive">
					<table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
						<thead class="text-center">
							<tr>
								<th style="background-color:#efd6bb; color:#000">Sl.No</th>
								<th style="background-color:#efd6bb; color:#000">Machine Code</th>
								<th style="background-color:#efd6bb; color:#000">Machine Description</th>
								<th style="background-color:#efd6bb; color:#000">Type</th>
								<th style="background-color:#efd6bb; color:#000">SAP Plant</th>
								<th style="background-color:#efd6bb; color:#000">Capacity Per Day</th>
								<th style="background-color:#efd6bb; color:#000">View</th>
								<th style="background-color:#efd6bb; color:#000">Edit</th>
							</tr>
						</thead>
						<tbody id="tbody">

							<?php
							$ctr = 1;
							if ($machine != false) {
								foreach ($machine as $k => $v) {
							?>
									<tr class="gradeX">
										<td><?php echo $ctr; ?></td>
										<td><?php echo $machine[$k]["MACHINE_TPM_ID"]; ?></td>
										<td><?php echo $machine[$k]["DESCRIPTION"]; ?></td>
										<td><?php echo $machine[$k]["TYPE"]; ?></td>
										<td><?php echo $machine[$k]["SAP_PLANT"]; ?></td>
										<td><?php echo $machine[$k]["CAPACITY_PER_DAY_MT"]; ?></td>
										<td><a href="<?php echo base_url(); ?>Machine/view/<?php echo $machine[$k]["PP_ID"]; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a></td>
										<td><a href="<?php echo base_url(); ?>Machine/edit/<?php echo $machine[$k]["PP_ID"]; ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a></td>
									</tr>
							<?php
									$ctr++;
								}
							}

							?>

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