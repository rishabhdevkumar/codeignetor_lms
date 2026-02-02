<?php
$machine_code       = old('MR_MACHINE_CODE', $machine['MACHINE_TPM_ID']);
$sap_plant          = old('SAP_PLANT', $machine['SAP_PLANT']);
$type               = old('YPE', $machine['TYPE']);
$description        = old('DESCRIPTION', $machine['DESCRIPTION']);
$pincode            = old('PIN_CODE', $machine['PIN_CODE']);
$vendor_code        = old('SAP_VENDOR_CODE', $machine['SAP_VENDOR_CODE']);
$capacity_per_day   = old('CAPACITY_PER_DAY_MT', $machine['CAPACITY_PER_DAY_MT']);
$finish_loss        = old('FINISH_LOSS_PERCENT', $machine['FINISH_LOSS_PERCENT']);
$grade_change_time  = old('GRADE_CHANGE_TIME_MIN', $machine['GRADE_CHANGE_TIME_MIN']);
$gsm_change_time    = old('GSM_CHANGE_TIME_MIN', $machine['GSM_CHANGE_TIME_MIN']);
$machine_id         = old('PP_ID', $machine['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" style="width:100%">
		<input type="hidden" name="machine_id" value="<?php echo $machine_id; ?>">
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>
		<div class="col-sm-6" style="float:left;margin-top:20px">

			<div class="ibox float-e-margins">
				<div style="background-color:#efd6bb; color:#000" class="ibox-title"">
					<h5><?php echo $title; ?></h5>
				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Machine Code</label>
											<input type="text" class="form-control" name="machine_code" id="machine_code"
											 maxlength="20" value="<?php echo $machine_code; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Type</label>
											<select class="form-control" name="type" id="type" readonly>
												<option>Select</option>
												<option <?php if($type=="OWN") echo "selected";?> value="OWN">OWN</option>
												<option <?php if($type=="TPM") echo "selected";?> value="TPM">TPM</option>
											</select>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>SAP Plant</label>
											<input type="text" class="form-control" name="sap_plant" id="sap_plant" maxlength="5" 
											readonly value="<?php echo $sap_plant; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Vendor Code</label>
											<input type="text" class="form-control" name="vendor_code" id="vendor_code"
											 readonly value="<?php echo $vendor_code; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>PinCode</label>
											<input type="text" class="form-control" name="pincode" id="pincode" 
											readonly value="<?php echo $pincode; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Capacity Per Day (MTS)</label>
											<input type="number" class="form-control" name="capacity_per_day" 
											id="capacity_per_day" readonly value="<?php echo $capacity_per_day; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Grade Change Time (Min)</label>
											<input type="number" class="form-control" name="grade_change_time" 
											id="grade_change_time" readonly value="<?php echo $grade_change_time; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>GSM Change Time (Min)</label>
											<input type="number" class="form-control" name="gsm_change_time" 
											id="gsm_change_time" readonly value="<?php echo $gsm_change_time; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Description</label>
											<textarea class="form-control" Placeholder="Enter Description" name="description" 
											id="description" readonly style="resize:none;"><?php echo $description; ?></textarea>
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<a class="btn btn-dark btn-sm" href="<?php echo base_url() ?>Machine">Back</a>
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