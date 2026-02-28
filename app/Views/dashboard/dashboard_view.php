<?php

$pivot = [];
$machines = [];

foreach ($gradePlanning as $row) {

	$machine = $row['MACHINE_TPM_ID'];
	$grade   = $row['GRADE'];

	$machines[$machine] = $machine; // collect unique machines

	$pivot[$grade][$machine] = [
		'plan'      => $row['totalScheduled'] / 1000,
		'utilized'  => $row['totalUtilized'] / 1000,
		'balance'   => $row['totalBalance'] / 1000
	];
}
ksort($machines);
ksort($pivot);
?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
	body {
		background-color: #f3f3f3;
		font-family: "72", "Segoe UI", Arial, sans-serif;
	}

	h3 {
		font-weight: 600;
		color: #32363a;
	}

	table.dataTable thead th {
		background: linear-gradient(#e9edf2, #dde3e9);
		font-size: 12px;
		font-weight: 600;
		text-transform: uppercase;
		position: sticky;
		top: 0;
		z-index: 2;
	}

	th {
		background-color: #FCE7C2 !important;
	}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row border-bottom white-bg dashboard-header">
	<div class="col-sm-12 text-center">
		<h2>Welcome To Planner Portal</h2>
	</div>
</div>

<div class="container-fluid mt-4">

	<div class="row">

		<div class="col-lg-3 col-md-3 mb-3">
			<div class="sap-card" style="height: 300px;">
				<h5>Tommorrow's Planning</h5>
				<canvas id="plannerChart"></canvas>
			</div>
		</div>

		<div class="col-lg-5 col-md-6 mb-3">
			<div class="sap-card" style="height: 300px;">
				<h5>Machine Planning</h5>
				<?php
				$labels = [];
				$machinescheduled = [];
				$machineutilized = [];
				$machinebalance = [];

				foreach ($machinePlanning as $row1) {
					$labels[]    = $row1['MACHINE_TPM_ID'];
					$machinescheduled[] = (float)$row1['Scheduled'] / 1000;
					$machineutilized[]  = (float)$row1['Utilized'] / 1000;
					$machinebalance[]   = (float)$row1['Balance'] / 1000;
				}
				?>
				<canvas id="machineStackedChart"></canvas>
			</div>
		</div>


		<div class="col-lg-4 col-md-6 mb-3">
			<div class="sap-card p-3" style="height: 300px;">
				<h5>Downtime Scheduled</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-hover text-center">
						<thead style="position: sticky; top: 0; z-index: 1;">
							<tr>
								<th>Machine</th>
								<th>From Date</th>
								<th>To Date</th>
							</tr>
						</thead>

						<tbody>
							<?php if (!empty($downtime) && is_array($downtime)): ?>
								<?php foreach ($downtime as $row): ?>
									<tr>
										<td><?= esc($row['MACHINE_TPM_ID']) ?></td>
										<td><?= esc($row['FROM_DATE']) ?></td>
										<td><?= esc($row['TO_DATE']) ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="18" class="text-center">No Data Found</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>


	<div class="row">

		<div class="col-lg-6 col-md-6 mb-3">
			<div class="sap-card p-3" style="height: 600px;">
				<h5>Not-Allotted Indents</h5>
				<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
					<table class="table table-bordered table-hover text-center">
						<thead style="position: sticky; top: 0; z-index: 1;">
							<tr>
								<th>Indent No.</th>
								<th>Customer</th>
								<th>Quantity</th>
								<th>Status</th>
							</tr>
						</thead>

						<tbody>
							<?php if (!empty($indentallotment) && is_array($indentallotment)): ?>
								<?php foreach ($indentallotment as $row): ?>
									<tr>
										<td><?= esc($row['INDENT_NO']) ?></td>
										<td><?= esc($row['cust_name']) ?></td>
										<td><?= esc($row['QUANTITY']) ?></td>
										<td class="text-danger">
											<?= esc($row['REMARKS']) ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="18" class="text-center">No Data Found</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>


		<div class="col-lg-6 col-md-6 mb-3">
			<div class="sap-card p-3" style="height: 600px;">
				<h5>Grade Planning (MT)</h5>
				<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
					<table class="table table-bordered table-hover text-center">
						<thead style="position: sticky; top: 0; z-index: 1;">
							<tr>
								<th rowspan="2">Grade</th>

								<?php foreach ($machines as $machine): ?>
									<th colspan="3"><?= $machine ?></th>
								<?php endforeach; ?>

								<th rowspan="2">Total Plan</th>
								<th rowspan="2">Total Utilized</th>
								<th rowspan="2">Total Balance</th>
							</tr>

							<tr>
								<?php foreach ($machines as $machine): ?>
									<th>Plan</th>
									<th>Utilized</th>
									<th>Balance</th>
								<?php endforeach; ?>
							</tr>
						</thead>

						<tbody>
							<?php
							$grandPlan = 0;
							$grandUtilized = 0;
							$grandBalance = 0;
							?>

							<?php foreach ($pivot as $grade => $machineData): ?>

								<?php
								$rowPlan = 0;
								$rowUtilized = 0;
								$rowBalance = 0;
								?>

								<tr>
									<td><strong><?= $grade ?></strong></td>

									<?php foreach ($machines as $machine): ?>

										<?php
										$plan = $machineData[$machine]['plan'] ?? 0;
										$utilized = $machineData[$machine]['utilized'] ?? 0;
										$balance = $machineData[$machine]['balance'] ?? 0;

										$rowPlan += $plan;
										$rowUtilized += $utilized;
										$rowBalance += $balance;
										?>

										<td><?= $plan ?></td>
										<td><?= $utilized ?></td>
										<td><?= $balance ?></td>

									<?php endforeach; ?>

									<td class="fw-bold"><?= $rowPlan ?></td>
									<td class="fw-bold"><?= $rowUtilized ?></td>
									<td class="fw-bold"><?= $rowBalance ?></td>
								</tr>

								<?php
								$grandPlan += $rowPlan;
								$grandUtilized += $rowUtilized;
								$grandBalance += $rowBalance;
								?>

							<?php endforeach; ?>
						</tbody>

						<tfoot class="table-secondary">
							<tr>
								<th>Grand Total</th>

								<?php foreach ($machines as $machine): ?>

									<?php
									$machinePlan = 0;
									$machineUtil = 0;
									$machineBal  = 0;

									foreach ($pivot as $grade => $data) {
										$machinePlan += $data[$machine]['plan'] ?? 0;
										$machineUtil += $data[$machine]['utilized'] ?? 0;
										$machineBal  += $data[$machine]['balance'] ?? 0;
									}
									?>

									<th><?= $machinePlan ?></th>
									<th><?= $machineUtil ?></th>
									<th><?= $machineBal ?></th>

								<?php endforeach; ?>

								<th><?= $grandPlan ?></th>
								<th><?= $grandUtilized ?></th>
								<th><?= $grandBalance ?></th>
							</tr>
						</tfoot>

					</table>
				</div>
			</div>
		</div>

	</div>

</div>

<script>
	const ctx = document.getElementById('plannerChart');

	new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: [
				'Scheduled',
				'Utilized',
				'Balance'
			],
			datasets: [{
				label: 'Planner Overview',
				data: [
					<?= $totalScheduled ?>,
					<?= $totalUtilized ?>,
					<?= $totalScheduled - $totalUtilized ?>
				],
				backgroundColor: [
					'#007bff', // Scheduled - Blue
					'#28a745', // Utilized - Green
					'#dc3545' // Balance - Red
				],
				borderWidth: 3
			}]
		},
		options: {
			responsive: true,
			cutout: '50%', // Controls doughnut thickness
			plugins: {
				legend: {
					position: 'bottom'
				},
				tooltip: {
					callbacks: {
						label: function(context) {
							let value = context.raw;
							return context.label + ': ' + value.toLocaleString() + ' MT';
						}
					}
				}
			}
		}
	});

	const ctx1 = document.getElementById('machineStackedChart').getContext('2d');

	new Chart(ctx1, {
		type: 'bar',
		data: {
			labels: <?= json_encode($labels) ?>,
			datasets: [{
					label: 'Plan Qty MT',
					data: <?= json_encode($machinescheduled) ?>,
					backgroundColor: '#c00000' // red
				},
				{
					label: 'Utilized MT',
					data: <?= json_encode($machineutilized) ?>,
					backgroundColor: '#70ad47' // green
				},
				{
					label: 'Balance MT',
					data: <?= json_encode($machinebalance) ?>,
					backgroundColor: '#ffd966' // yellow
				}
			]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					// position: 'right'
				},
				tooltip: {
					callbacks: {
						label: function(context) {
							return context.dataset.label + ': ' +
								context.raw.toLocaleString() + ' MT';
						}
					}
				}
			},
			scales: {
				x: {
					stacked: true
				},
				y: {
					stacked: true,
					beginAtZero: true,
					ticks: {
						callback: function(value) {
							return value.toLocaleString();
						}
					}
				}
			}
		}
	});
</script>