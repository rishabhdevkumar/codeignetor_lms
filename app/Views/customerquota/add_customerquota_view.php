<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<?php if (isset($error)) : ?>
		<div class="alert alert-danger">
			<?= $error ?>
		</div>
	<?php endif; ?>

	<form id="frm" autocomplete="off" method="post" action="<?= base_url('CustomerQuota/insertData') ?>" style="width:100%">
		
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>

		<div class="col-sm-8" style="float:left;margin-top:20px">
			<div>
				<div class="ibox-title">
					<h5><?php echo $title; ?></h5>
				</div>

				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>Grade</label>
											<input type="text" class="form-control" name="grade" id="grade">
										</div>

										<div class="col-sm-4 col-xs-12">
    										<label>Customer Type</label>
    										<select class="form-control" name="customer_type" id="customer_type">
        										<option value="">-- Select Customer Type --</option>
        										<option value="KC1">KC1</option>
        										<option value="KC2">KC2</option>
        										<option value="KC3">KC3</option>
        										<option value="KC4">KC4</option>
    										</select>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>Quota Percentage</label>
											<input type="number" class="form-control" name="quota_percentage" id="quota_percentage">
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<a class="btn btn-outline-secondary btn-sm" href="<?php echo base_url(); ?>CustomerQuota">Back</a>
											<button class="btn btn-outline-info btn-sm" type="submit">Add</button>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>
