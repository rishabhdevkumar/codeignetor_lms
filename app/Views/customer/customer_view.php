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
				<a href="<?php echo base_url(); ?>Customer/add" class="btn btn-info btn-sm">Add Customer</a>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
						<thead class="text-center">
							<tr>
								<th style="background-color:#efd6bb; color:#000">Sl.No</th>
								<th style="background-color:#efd6bb; color:#000">Customer Code</th>
								<th style="background-color:#efd6bb; color:#000">Customer Name</th>
								<th style="background-color:#efd6bb; color:#000">Customer Type</th>
								<th style="background-color:#efd6bb; color:#000">Partner Code</th>
								<th style="background-color:#efd6bb; color:#000">State</th>
								<th style="background-color:#efd6bb; color:#000">PIN Code</th>
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
										<td><?php echo $customer[$k]["CUSTOMER_CODE"]; ?></td>
										<td><?php echo $customer[$k]["cust_name"]; ?></td>
										<td><?php echo $customer[$k]["CUSTOMER_TYPE"]; ?></td>
										<td><?php echo $customer[$k]["parent_cust_no"]; ?></td>
										<td><?php echo $customer[$k]["STATE"]; ?></td>
										<td><?php echo $customer[$k]["PIN_CODE"]; ?></td>
										<td><a href="<?php echo base_url(); ?>Customer/view/<?php echo $customer[$k]["PP_ID"]; ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-eye"></i></a></td>
										<td><a href="<?php echo base_url(); ?>Customer/edit/<?php echo $customer[$k]["PP_ID"]; ?>" class="btn btn-outline-warning btn-sm"><i class="fa fa-pencil"></i></a></td>
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