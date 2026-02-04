<?php
$material_code      = old('FINISH_MATERIAL_CODE', $material['FINISH_MATERIAL_CODE']);
$sap_plant          = old('SAP_PLANT', $material['SAP_PLANT']);
$grade              = old('GRADE', $material['GRADE']);
$description        = old('DESCRIPTION', $material['DESCRIPTION']);
$gsm                = old('GSM', $material['GSM']);
$uom                = old('UOM', $material['UOM']);
$item_type          = old('ITEM_TYPE', $material['ITEM_TYPE']);
$width              = old('WIDTH', $material['WIDTH']);
$length             = old('LENGTH', $material['LENGTH']);
$mr_material_code   = old('MR_MATERIAL_CODE', $material['MR_MATERIAL_CODE']);
$packaging_time     = old('PACKAGING_TIME', $material['PACKAGING_TIME']);
$material_id        = old('ID', $material['ID']);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<div class="row" style="float:left;width:100%">
	<form id="frm" autocomplete="off" method="POST" style="width:100%">
		<input type="hidden" name="material_id" value="<?php echo $material_id; ?>">
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>
		<div class="col-sm-6" style="float:left;margin-top:20px">

			<div class="ibox float-e-margins">
				<div style="background-color:#efd6bb; color:#000" class="ibox-title">
					<h5><?php echo $title; ?></h5>
				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Material Code</label>
											<input type="text" class="form-control" name="material_code" id="material_code"
											 maxlength="20" value="<?php echo $material_code; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>SAP Plant</label>
											<input type="text" class="form-control" name="sap_plant" id="sap_plant"
											 maxlength="5" value="<?php echo $sap_plant; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>


								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Grade</label>
											<input type="text" class="form-control" name="grade" id="grade"
											 maxlength="5" value="<?php echo $grade; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>GSM</label>
											<input type="text" class="form-control" name="gsm" id="gsm" maxlength="5"
											 value="<?php echo $gsm; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>UOM</label>
											<input type="text" class="form-control" name="uom" id="uom" maxlength="5"
											 value="<?php echo $uom; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Item Type</label>
											<input type="text" class="form-control" name="item_type" id="item_type"
											 value="<?php echo $item_type; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Width</label>
											<input type="number" class="form-control" name="width" id="width" 
											value="<?php echo $width; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Length</label>
											<input type="number" class="form-control" name="length" id="length"
											 value="<?php echo $length; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>MR Material Code</label>
											<input type="text" class="form-control" name="mr_material_code" id="mr_material_code"
											 maxlength="20" value="<?php echo $mr_material_code; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Packaging Time</label>
											<input type="number" class="form-control" name="packaging_time" 
											id="packaging_time" value="<?php echo $packaging_time; ?>" readonly>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Description</label>
											<textarea class="form-control" name="description" id="description" style="resize: none;"
											 readonly><?php echo $description; ?></textarea>
											<div class="error"></div>
										</div>
									</div>
								</div>


								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<a class="btn btn-dark btn-sm" href="<?php echo base_url() ?>Material">Back</a>
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