<?php
$grade            = old('GRADE', $customerquota['GRADE']);
$customer_type    = old('CUSTOMER_TYPE', $customerquota['CUSTOMER_TYPE']);
$quota_percentage = old('QUOTA_PERCENTAGE', $customerquota['QUOTA_PERCENTAGE']);
$pp_id            = old('PP_ID', $customerquota['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('CustomerQuota/updateData/'.$pp_id) ?>" style="width:100%">
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
									<input type="text" class="form-control" name="grade" id="grade"
										value="<?php echo $grade; ?>" required>
								</div>

								<div class="col-sm-6 col-xs-12">
									<label>Customer Type</label>
									<select class="form-control" name="customer_type" id="customer_type" required>
										<option value="">-- Select --</option>
										<option value="KC1" <?= ($customer_type == 'KC1') ? 'selected' : '' ?>>KC1</option>
										<option value="KC2" <?= ($customer_type == 'KC2') ? 'selected' : '' ?>>KC2</option>
										<option value="KC3" <?= ($customer_type == 'KC3') ? 'selected' : '' ?>>KC3</option>
										<option value="KC4" <?= ($customer_type == 'KC4') ? 'selected' : '' ?>>KC4</option>
									</select>
								</div>

							</div>
						</div>

						<div class="form-group">
							<div class="row">

								<div class="col-sm-6 col-xs-12">
									<label>Quota Percentage</label>
									<input type="number" class="form-control" name="quota_percentage" id="quota_percentage"
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
