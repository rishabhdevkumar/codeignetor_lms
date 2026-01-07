<?php
$customer_code       = old('CUSTOMER_CODE', $customer['CUSTOMER_CODE']);
$customer_type        = old('CUSTOMER_TYPE', $customer['CUSTOMER_TYPE']);
$country             = old('COUNTRY', $customer['COUNTRY']);
$state               = old('STATE', $customer['STATE']);
$pincode             = old('PIN_CODE', $customer['PIN_CODE']);
$customer_id         = old('PP_ID', $customer['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" style="width:100%">
		<input type="hidden" name="machine_id" value="<?php echo $customer_id; ?>">
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

										<div class="col-sm-6 col-xs-12">
											<label>Customer Code</label>
											<input type="text" class="form-control" name="customer_code" id="customer_code" maxlength="20" value="<?php echo $customer_code; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Type</label>
											<select class="form-control" name="customer_type" id="customer_type" readonly>
												<option>Select</option>
												<option <?php if($customer_type=="KC1") echo "selected";?> value="KC1">KC1</option>
												<option <?php if($customer_type=="KC2") echo "selected";?> value="KC2">KC2</option>
												<option <?php if($customer_type=="KC3") echo "selected";?> value="KC3">KC3</option>
												<option <?php if($customer_type=="KC4") echo "selected";?> value="KC4">KC4</option>
											</select>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>Country</label>
											<input type="text" class="form-control" name="country" id="country" maxlength="20" value="<?php echo $country; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>State</label>
											<input type="text" class="form-control" name="state" id="state" value="<?php echo $state; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>PinCode</label>
											<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $pincode; ?>" readonly>
											<div class="error"></div>
										</div>
									</div>
								</div>


								<br><br>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<a class="btn btn-primary" href="<?php echo base_url() ?>Machine">Back</a>
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