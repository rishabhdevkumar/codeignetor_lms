<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row">

	<?php if (isset($error)) : ?>
		<div class="alert alert-danger text-center fw-bold">
			<?= $error ?>
		</div>
	<?php endif; ?>

	<form id="frm" autocomplete="off" method="post"
		action="<?= base_url('/Material/insertData') ?>"
		enctype="multipart/form-data"
		style="width:100%">

		<div class="col-sm-3" style="float:left;margin-top:20px"></div>

		<div class="col-sm-6" style="float:left;margin-top:20px;">
			<div class="bg-white shadow-sm">

				<div style="background-color:#efd6bb; color:#000" class="ibox-title">
					<h5><?php echo $title; ?><small> </small></h5>
				</div>

				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">
										<div class="col-sm-4 col-xs-12">
											<label class="text-secondary">Material Code<em>*</em></label>
											<input type="text" class="form-control"
												name="material_code" id="material_code"
												maxlength="20" required
												value="<?php echo set_value('material_code'); ?>">
										</div>

										<div class="col-sm-4 col-xs-12">
											<label class="text-secondary">SAP Plant<em>*</em></label>
											<input type="text" class="form-control"
												name="sap_plant" id="sap_plant"
												maxlength="5" required
												value="<?php echo set_value('sap_plant'); ?>">
										</div>

										<div class="col-sm-4 col-xs-12">
											<label class="text-secondary">Grade<em>*</em></label>
											<input type="text" class="form-control"
												name="grade" id="grade"
												maxlength="20" required
												value="<?php echo set_value('grade'); ?>">
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-4 col-xs-12">
											<label class="text-secondary">GSM<em>*</em></label>
											<input type="text" class="form-control"
												name="gsm" id="gsm"
												maxlength="5" required
												value="<?php echo set_value('gsm'); ?>">
										</div>

										<div class="col-sm-4 col-xs-12">
											<label class="text-secondary">UOM<em>*</em></label>
											<input type="text" class="form-control"
												name="uom" id="uom"
												maxlength="5" required
												value="<?php echo set_value('uom'); ?>">
										</div>

										<div class="col-sm-4 col-xs-12 mt-2">
											<label class="text-secondary">Item Type<em>*</em></label>
											<input type="text" class="form-control"
												name="item_type" id="item_type" required
												value="<?php echo set_value('item_type'); ?>">
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<label class="text-secondary">Width<em>*</em></label>
											<input type="number" class="form-control"
												name="width" id="width" required
												value="<?php echo set_value('width'); ?>">
										</div>

										<div class="col-sm-6 col-xs-12">
											<label class="text-secondary">Length<em>*</em></label>
											<input type="number" class="form-control"
												name="length" id="length" required
												value="<?php echo set_value('length'); ?>">
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<label class="text-secondary">MR Material Code<em>*</em></label>
											<input type="text" class="form-control"
												name="mr_material_code" id="mr_material_code"
												maxlength="20" required
												value="<?php echo set_value('mr_material_code'); ?>">
										</div>

										<div class="col-sm-6 col-xs-12">
											<label class="text-secondary">Packaging Time<em>*</em></label>
											<input type="number" class="form-control"
												name="packaging_time" id="packaging_time" required
												value="<?php echo set_value('packaging_time'); ?>">
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label class="text-secondary">Description<em>*</em></label>
											<textarea class="form-control" style="resize: none;"
												name="description" required
												id="description"><?php echo set_value('description'); ?></textarea>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12 text-center">
											<button class="btn btn-info btn-sm px-4" type="submit">
												Add
											</button>

											<a class="btn btn-outline-dark btn-sm px-4"
												href="<?php echo base_url() ?>Material">
												 Back
											</a>
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
<!-- </div> -->

<script>
	// 	$(document).ready(function() {

	// 	});

	document.getElementById('material_code').addEventListener('input', function() {
		this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
		this.value = this.value.toUpperCase();
	});

	// $(document).on('input', '#material_code', function () {
	//     this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
	// });

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 45 || charCode > 57)) {
			return false;
		}
		return true;
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