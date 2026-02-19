<div class="card mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tbl" class="table table-striped table-bordered table-hover dataTables-example">
						<thead class="text-center">
							<tr>
								<th style="background-color:#efd6bb; color:#000">Sl.No</th>
								<th style="background-color:#efd6bb; color:#000">Indent No</th>
								<th style="background-color:#efd6bb; color:#000">Indent Line Item</th>
								<th style="background-color:#efd6bb; color:#000">Planning Cal ID</th>
								<th style="background-color:#efd6bb; color:#000">Version</th>
								<th style="background-color:#efd6bb; color:#000">Finish Material Code</th>
								<th style="background-color:#efd6bb; color:#000">MR Material Code</th>
								<th style="background-color:#efd6bb; color:#000">Quantity</th>
								<th style="background-color:#efd6bb; color:#000">From Date</th>
								<th style="background-color:#efd6bb; color:#000">To Date</th>
								<th style="background-color:#efd6bb; color:#000">Finishing Date</th>
								<th style="background-color:#efd6bb; color:#000">Door Step Del Date</th>
								<th style="background-color:#efd6bb; color:#000">Customer Type</th>
							</tr>
						</thead>
						<tbody id="tbody">
							<?php
							$ctr = 1;
							if ($indentallotment != false) {
								foreach ($indentallotment as $k => $v) {
							?>
									<tr class="gradeX">
										<td><?php echo $ctr; ?></td>
										<td><?php echo $indentallotment[$k]["INDENT_NO"]; ?></td>
										<td><?php echo $indentallotment[$k]["INDENT_LINE_ITEM"]; ?></td>
										<td><?php echo $indentallotment[$k]["PLANNING_CAL_ID"]; ?></td>
										<td><?php echo $indentallotment[$k]["VERSION"]; ?></td>
										<td><?php echo $indentallotment[$k]["FINISH_MATERIAL_CODE"]; ?></td>
										<td><?php echo $indentallotment[$k]["MR_MATERIAL_CODE"]; ?></td>
										<td><?php echo $indentallotment[$k]["QUANTITY"]; ?></td>
										<td><?php echo $indentallotment[$k]["FROM_DATE"]; ?></td>
										<td><?php echo $indentallotment[$k]["TO_DATE"]; ?></td>
										<td><?php echo $indentallotment[$k]["FINISHING_DATE"]; ?></td>
										<td><?php echo $indentallotment[$k]["DOOR_STEP_DEL_DATE"]; ?></td>
										<td><?php echo $indentallotment[$k]["CUSTOMER_TYPE"]; ?></td>
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