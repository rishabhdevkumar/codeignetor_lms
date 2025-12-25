<?php
$material_code = old('MR_MATERIAL_CODE', $material['MR_MATERIAL_CODE']);
$sap_plant      = old('SAP_PLANT', $material['SAP_PLANT']);
$grade          = old('GRADE', $material['GRADE']);
$description    = old('DESCRIPTION', $material['DESCRIPTION']);
$gsm            = old('GSM', $material['GSM']);
$delivery_plant = old('DELIVERY_PLANT_YN', $material['DELIVERY_PLANT_YN']);
$machine_output = old('MACHINE_OUTPUT_KG_HR', $material['MACHINE_OUTPUT_KG_HR']);
$material_id    = old('PP_ID', $material['PP_ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('/MRMaterial/updateData/'.$material_id) ?>" style="width:100%">
		<input type="hidden" name="material_id" value="<?php echo $material_id; ?>">
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
										<div class="col-sm-12 col-xs-12">
											<label>Material Code</label>
											<input type="text" id="material_code" name="material_code" class="form-control" value="<?php echo $material_code; ?>" readonly>
										</div>
									</div>
								</div>
								
								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>SAP Plant</label>
											<input type="text" class="form-control" name="sap_plant" id="sap_plant" maxlength="5" autocomplete="off" value="<?php echo $sap_plant; ?>">
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Grade</label>
											<input type="text" class="form-control" name="grade" id="grade" maxlength="5" autocomplete="off" value="<?php echo $grade; ?>">
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>GSM</label>
											<input type="text" class="form-control" name="gsm" id="gsm" maxlength="5" autocomplete="off" value="<?php echo $gsm; ?>">
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Description</label>
											<textarea class="form-control" Placeholder="Enter Description" name="description" id="description"><?php echo $description; ?></textarea>
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Delivery Plant</label>
											<select class="form-control" name="delivery_plant" id="delivery_plant" >
												<option></option>
												<option <?php if($delivery_plant=="Y") echo "selected";?> value="Y">YES</option>
												<option <?php if($delivery_plant=="N") echo "selected";?> value="N">NO</option>
											</select>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Machine Output (KG/hr)</label>
											<input type="number" class="form-control" name="machine_output" id="machine_output" value="<?php echo $machine_output; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<br><br>
								
								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<button class="btn btn-info" type="submit">Update</button>
											<a class="btn btn-primary" href="<?php echo base_url() ?>MRMaterial">Back</a>
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
		if (charCode > 31 && (charCode < 45 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function add(x) {
		$("#tbody").append($("#tbody tr:first-child").clone());
		$("#tbody tr:last-child").find("input").val("");
		$("#tbody tr:last-child").find("select").val("");
	}

	function del(x) {
		var si = 0;
		$('#tbody tr').each(function() {
			si++;
		});
		if (si > 1)
			$(x).closest("tr").remove();
	}

	function clsAlphaNoOnly(e) // Accept only alpha numerics, no special characters 
	{
		var regex = new RegExp("^[a-zA-Z0-9 ]+$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (regex.test(str)) {
			return true;
		}

		e.preventDefault();
		return false;
	}



	
</script>