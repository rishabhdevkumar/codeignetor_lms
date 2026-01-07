<?php
$from_country             = old('FROM_COUNTRY', $customer['FROM_COUNTRY']);
$from_pincode             = old('FROM_PINCODE', $customer['FROM_PINCODE']);
$to_country             = old('TO_COUNTRY', $customer['TO_COUNTRY']);
$to_pincode             = old('TO_PINCODE', $customer['TO_PINCODE']);
$distance              = old('DISTANCE', $customer['DISTANCE']);
$transit_time            = old('TRANSIT_TIME', $customer['TRANSIT_TIME']);
$transit_id         = old('PP_ID', $customer['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" style="width:100%">
		<input type="hidden" name="transit_id" value="<?php echo $transit_id; ?>">
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>
		<div class="col-sm-6" style="float:left;margin-top:20px">

			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5><?php echo $title; ?><small> </small></h5>
				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>From Country</label>
											<input type="text" class="form-control" name="from_country" id="from_country" maxlength="20" value="<?php echo $from_country; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>From PinCode</label>
											<input type="text" class="form-control" name="from_pincode" id="from_pincode" value="<?php echo $from_pincode; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>To Country</label>
											<input type="text" class="form-control" name="to_country" id="to_country" maxlength="20" value="<?php echo $to_country; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>To PinCode</label>
											<input type="text" class="form-control" name="to_pincode" id="to_pincode" value="<?php echo $to_pincode; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>Distance</label>
											<input type="number" class="form-control" name="distance" id="distance" value="<?php echo $distance; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>Transit Time</label>
											<input type="number" class="form-control" name="transit_time" id="transit_time" value="<?php echo $transit_time; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>


								<br><br>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<a class="btn btn-primary" href="<?php echo base_url() ?>CustomerTransit">Back</a>
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
<script>
	$(document).ready(function() {

	});

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function add(x) {
		$("#tbody").append($(x).closest("tr").clone());
	}

	function del(x) {
		$(x).closest("tr").remove();
	}
</script>