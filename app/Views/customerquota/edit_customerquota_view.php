<?php
$selected_grade   = old('GRADE', $customerquota['GRADE']);
$customer_type    = old('CUSTOMER_TYPE', $customerquota['CUSTOMER_TYPE']);
$quota_percentage = old('QUOTA_PERCENTAGE', $customerquota['QUOTA_PERCENTAGE']);
$total_quota_percentage = old('TOTAL_QUOTA_PERCENTAGE', $customerquota['TOTAL_QUOTA_PERCENTAGE']);
$total_quota_percentage = $total_quota_percentage - $quota_percentage;
$max_quota_percentage = 100 - $total_quota_percentage;
$pp_id            = old('PP_ID', $customerquota['PP_ID']);
// $grade            = $grade;
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('CustomerQuota/updateData/' . $pp_id) ?>" style="width:100%">
		<input type="hidden" name="pp_id" value="<?php echo $pp_id; ?>">

		<div class="col-sm-3" style="float:left;margin-top:20px"></div>

		<div class="col-sm-6" style="float:left;margin-top:20px">
			<div class="ibox float-e-margins">
				<div style="background-color:#efd6bb; color:#000" class="ibox-title">
					<h5><?php echo $title; ?></h5>
				</div>

				<div class="ibox-content">
					<div class="form-horizontal">

						<div class="form-group">
							<div class="row">

								<div class="col-sm-6 col-xs-12">
									<label>Grade</label>
									<input type="text" class="form-control" name="grade" id="grade" value="<?php echo $selected_grade; ?>" readonly required>
									<!-- <select class="form-control" name="grade" id="grade" readonly required>
										<option value="">-- Select Grade --</option>
										<?php foreach ($grade as $row): ?>
											<option value="<?= esc($row['GRADE']) ?>"
												<?= isset($selected_grade) && $selected_grade == $row['GRADE'] ? 'selected' : '' ?>>
												<?= esc($row['GRADE']) ?>
											</option>
										<?php endforeach; ?>
									</select> -->
								</div>

								<div class="col-sm-6 col-xs-12">
									<label>Customer Type</label>
									<input type="text" class="form-control" name="customer_type" id="customer_type" value="<?php echo $customer_type; ?>" readonly required>

									<!-- <select class="form-control" name="customer_type" id="customer_type" required>
										<option value="">-- Select --</option>
										<option value="KC1" <?= ($customer_type == 'KC1') ? 'selected' : '' ?>>KC1</option>
										<option value="KC2" <?= ($customer_type == 'KC2') ? 'selected' : '' ?>>KC2</option>
										 <option value="KC3" <?= ($customer_type == 'KC3') ? 'selected' : '' ?>>KC3</option>
										<option value="KC4" <?= ($customer_type == 'KC4') ? 'selected' : '' ?>>KC4</option> 
									</select> -->
								</div>

							</div>
						</div>

						<div class="form-group">
							<div class="row">

								<div class="col-sm-6 col-xs-12">
									<label>Alloted Quota Percentage</label>
									<input type="number" class="form-control" readonly name="alloted_quota_percentage" id="alloted_quota_percentage" 
									value="<?php echo $total_quota_percentage; ?>">
								</div>

								<div class="col-sm-6 col-xs-12">
									<label>Quota Percentage</label>
									<input type="number" class="form-control" name="quota_percentage" id="quota_percentage" max = "<?php echo $max_quota_percentage; ?>"
										value="<?php echo $quota_percentage; ?>" required>
								</div>

							</div>
						</div>

						<div class="hr-line-dashed"></div>

						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 col-xs-12">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>CustomerQuota">Back</a>
									<button class="btn btn-success btn-sm" type="submit">Update</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$('#grade').change(function() {
		let grade = $(this).val();

		$.ajax({
			url: "<?= base_url('material/getAllotmentQuota') ?>",
			type: "POST",
			data: {
				grade: grade
			},
			dataType: "json",
			success: function(response) {

				let quota = 0;

				if (response.length > 0 && response[0].QUOTA_PERCENTAGE !== undefined) {
					quota = parseFloat(response[0].QUOTA_PERCENTAGE);
				}

				// ✅ set value correctly
				$('#alloted_quota_percentage').val(quota);

				$('#quota_percentage').val(100 - quota);
				$('#quota_percentage').attr('max', 100 - quota);

				let select = $('#customer_type');

				let selectedType = response[0].CUSTOMER_TYPE;

				// Reset
				select.find('option').prop('disabled', false);

				if (selectedType === 'KC1') {
					select.find('option[value="KC1"]').prop('disabled', true);
					select.val('KC2');
				}

				if (selectedType === 'KC2') {
					select.find('option[value="KC2"]').prop('disabled', true);
					select.val('KC1');
				}


			}
		});
	});
</script>