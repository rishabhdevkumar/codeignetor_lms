  <head>
  	<style>
  		.prepone {
  			background-color: #d4edda !important;
  			color: #155724;
  			font-weight: 600;
  		}

  		.postpone {
  			background-color: #f8d7da !important;
  			color: #721c24;
  			font-weight: 600;
  		}
  	</style>
  </head>
  <div class="card mt-4">
  	<div class="row">
  		<div class="col-12">

  			<div class="card-header d-flex justify-content-between align-items-center" style="background-color:#FFE0B5;">
  				<h5>Allotment Details</h5>
  			</div>

  			<div class="card-body">
  				<div class="table-responsive">
  					<table id="tbl" class="table table-bordered table-hover dataTables-example">
  						<thead class="text-center">
  							<tr>
  								<th style="background-color:#efd6bb; color:#000">Indent No</th>
  								<th style="background-color:#efd6bb; color:#000">Line Item</th>
  								<th style="background-color:#efd6bb; color:#000">Cal ID</th>
  								<th style="background-color:#efd6bb; color:#000">Version</th>
  								<th style="background-color:#efd6bb; color:#000">Finish Material</th>
  								<th style="background-color:#efd6bb; color:#000">MR Material</th>
  								<th style="background-color:#efd6bb; color:#000">Quantity</th>
  								<th style="background-color:#efd6bb; color:#000">From Date</th>
  								<th style="background-color:#efd6bb; color:#000">To Date</th>
  								<th style="background-color:#efd6bb; color:#000">Finishing Date</th>
  								<th style="background-color:#efd6bb; color:#000">Old Finishing Date</th>
  								<th style="background-color:#efd6bb; color:#000">Delivery Date</th>
  								<th style="background-color:#efd6bb; color:#000">Customer Type</th>
  								<th style="background-color:#efd6bb; color:#000">Sale Order</th>
  								<th style="background-color:#efd6bb; color:#000">SAP Remarks</th>
  								<th style="background-color:#efd6bb; color:#000">Indent Remarks</th>
  							</tr>
  						</thead>
  						<tbody id="tbody">
  							<?php
								$ctr = 1;
								if ($indentallotment != false) {
									foreach ($indentallotment as $k => $v) {

										$finishingDate = $indentallotment[$k]['FINISHING_DATE'];
										$oldfinishingDate = $indentallotment[$k]['OLD_FINISHING_DATE'] ?? null;

										// Check if OLD_FROM_DATE is initial
										$isOldInitial = empty($oldfinishingDate)
											|| $oldfinishingDate == '0000-00-00 00:00:00';

										$class = '';

										if (!$isOldInitial) {

											$newTime = strtotime($finishingDate);
											$oldTime = strtotime($oldfinishingDate);

											if ($newTime < $oldTime) {
												$class = 'prepone';
											} elseif ($newTime > $oldTime) {
												$class = 'postpone';
											}
										}
								?>
  									<tr class="gradeX">
  										<td><?php echo $indentallotment[$k]["INDENT_NO"]; ?></td>
  										<td><?php echo $indentallotment[$k]["INDENT_LINE_ITEM"]; ?></td>
  										<td><?php echo $indentallotment[$k]["PLANNING_CAL_ID"]; ?></td>
  										<td><?php echo $indentallotment[$k]["VERSION"]; ?></td>
  										<td><?php echo $indentallotment[$k]["FINISH_MATERIAL_CODE"]; ?></td>
  										<td><?php echo $indentallotment[$k]["MR_MATERIAL_CODE"]; ?></td>
  										<td><?php echo $indentallotment[$k]["QUANTITY"]; ?></td>
  										<td><?php echo $indentallotment[$k]["FROM_DATE"]; ?></td>
  										<td><?php echo $indentallotment[$k]["TO_DATE"]; ?></td>
  										<td class="<?= $class ?>"><?php echo $indentallotment[$k]["FINISHING_DATE"]; ?></td>
  										<td><?= $isOldInitial ? '-' : $oldfinishingDate ?></td>
										<td><?php echo $indentallotment[$k]["DOOR_STEP_DEL_DATE"]; ?></td>
  										<td><?php echo $indentallotment[$k]["CUSTOMER_TYPE"]; ?></td>
  										<td><?php echo $indentallotment[$k]["SAP_ORDER_NO"]; ?></td>
  										<td><?php echo $indentallotment[$k]["SAP_REMARKS"]; ?></td>
  										<td><?php echo $indentallotment[$k]["REMARKS"]; ?></td>
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
  					title: 'Material',
  					exportOptions: {
  						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
  					}
  				},
  				{
  					extend: 'csv',
  					title: 'Material',
  					exportOptions: {
  						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
  					}
  				},
  				{
  					extend: 'excel',
  					title: 'Material',
  					exportOptions: {
  						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
  					}
  				},
  				{
  					extend: 'pdf',
  					title: 'Material',
  					exportOptions: {
  						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
  					}
  				},
  				{
  					extend: 'print',
  					title: 'Material',
  					exportOptions: {
  						columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
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
  </script>