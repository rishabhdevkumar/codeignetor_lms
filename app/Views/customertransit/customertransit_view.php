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
				<a href="<?php echo base_url(); ?>CustomerTransit/add" class="btn-success btn-outline-warning text-dark btn-sm">
					<i class="fa fa-plus p-1"></i>Add New</a>
			</div>

		 <div class="card-body">
				<div class="table-responsive">
					<table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
						<thead class="text-center">
							<tr>
								<th style="background-color:#efd6bb; color:#000">Sl.No</th>
								<th style="background-color:#efd6bb; color:#000">From Country</th>
								<th style="background-color:#efd6bb; color:#000">From Pincode</th>
								<th style="background-color:#efd6bb; color:#000">To Country</th>
								<th style="background-color:#efd6bb; color:#000">To Pincode</th>
								<th style="background-color:#efd6bb; color:#000">Distance</th>
								<th style="background-color:#efd6bb; color:#000">Time</th>
								<th style="background-color:#efd6bb; color:#000">View</th>
								<th style="background-color:#efd6bb; color:#000">Edit</th>
							</tr>
						</thead>
						<tbody id="tbody">

							<?php
							$ctr = 1;
							if ($customer != false) {
								foreach ($customer as $k => $v) {
							?>
									<tr class="gradeX">
										<td><?php echo $ctr; ?></td>
										<td><?php echo $customer[$k]['FROM_COUNTRY_NAME'] ?></td>
										<td><?php echo $customer[$k]["FROM_PINCODE"]; ?></td>
										<td><?php echo isset($customer[$k]["TO_COUNTRY_NAME"]) ? $customer[$k]["TO_COUNTRY_NAME"] : '-'; ?></td>
										<td><?php echo $customer[$k]["TO_PINCODE"]; ?></td>
										<td><?php echo $customer[$k]["DISTANCE"]; ?></td>
										<td><?php echo $customer[$k]["TRANSIT_TIME"]; ?></td>
										<td><a href="<?php echo base_url(); ?>CustomerTransit/view/<?php echo $customer[$k]["PP_ID"]; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a></td>
										<td><a href="<?php echo base_url(); ?>CustomerTransit/edit/<?php echo $customer[$k]["PP_ID"]; ?>" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a></td>
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
					title: 'Customer',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'csv',
					title: 'Customer',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'excel',
					title: 'Customer',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'pdf',
					title: 'Customer',
					exportOptions: {
						columns: [0, 1, 2, 3, 4]
					}
				},
				{
					extend: 'print',
					title: 'Customer',
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