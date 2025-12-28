<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<?php if (isset($error)) : ?>
		<div class="alert alert-danger">
			<?= $error ?>
		</div>
	<?php endif; ?>
		<form id="frm" autocomplete="off" method="post" action="<?= base_url('/Material/insertData') ?>" enctype="multipart/form-data" style="width:100%">
			<div class="col-sm-3" style="float:left;margin-top:20px"></div>
			
<<<<<<< HEAD

			<div class="col-sm-6" style="float:left;margin-top:20px">
				<div class="ibox float-e-margins card">
=======
	<div class="">
			<div class="col-sm-8" style="float:left;margin-top:20px">
				<div>
>>>>>>> a6925c6df4e3f5b56456845976c6dff9d8589c58
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
												<label>Material Code</label>
												<input type="text" class="form-control" name="material_code" id="material_code" maxlength="20" value="<?php echo set_value('material_code'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-4 col-xs-12">
												<label>SAP Plant</label>
												<input type="text" class="form-control" name="sap_plant" id="sap_plant" maxlength="5" value="<?php echo set_value('sap_plant'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-4 col-xs-12">
												<label>Grade</label>
												<input type="text" class="form-control" name="grade" id="grade" maxlength="20" value="<?php echo set_value('grade'); ?>">
												<div class="error"></div>
										</div>
									</div>


									<div class="hr-line-dashed"></div>

									<div class="form-group">
										<div class="row">

											

											<div class="col-sm-6 col-xs-12">
												<label>GSM</label>
												<input type="text" class="form-control" name="gsm" id="gsm" maxlength="5" value="<?php echo set_value('gsm'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-6 col-xs-12">
												<label>UOM</label>
												<input type="text" class="form-control" name="uom" id="uom" maxlength="5" value="<?php echo set_value('uom'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-6 col-xs-12">
												<label>Item Type</label>
												<input type="text" class="form-control" name="item_type" id="item_type" value="<?php echo set_value('item_type'); ?>">
												<div class="error"></div>
											</div>

										</div>
									</div>

									<div class="hr-line-dashed"></div>

									<!-- <div class="form-group">
										<div class="row">

											

										</div>
									</div> -->

									<div class="form-group">
										<div class="row">

											<div class="col-sm-6 col-xs-12">
												<label>Width</label>
												<input type="number" class="form-control" name="width" id="width" value="<?php echo set_value('width'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-6 col-xs-12">
												<label>Length</label>
												<input type="number" class="form-control" name="length" id="length" value="<?php echo set_value('length'); ?>">
												<div class="error"></div>
											</div>

										</div>
									</div>

									<div class="form-group">
										<div class="row">

											<div class="col-sm-6 col-xs-12">
												<label>MR Material Code</label>
												<input type="text" class="form-control" name="mr_material_code" id="mr_material_code" maxlength="20" value="<?php echo set_value('mr_material_code'); ?>">
												<div class="error"></div>
											</div>

											<div class="col-sm-6 col-xs-12">
												<label>Packaging Time</label>
												<input type="number" class="form-control" name="packaging_time" id="packaging_time" value="<?php echo set_value('packaging_time'); ?>">
												<div class="error"></div>
											</div>

										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-12 col-xs-12">
												<label>Description</label>
												<textarea class="form-control" Placeholder="Enter Description" name="description" id="description"><?php echo set_value('description'); ?></textarea>
												<div class="error"></div>
											</div>
										</div>
									</div>



									<!-- <div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<label>Machine Output (KG/hr)</label>
											<input type="number" class="form-control" name="machine_output" id="machine_output">
											<div class="error"></div>
										</div>
									</div>
								</div> -->

									<br><br>


									<div class="hr-line-dashed"></div>

									<div class="hr-line-dashed"></div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-12 col-xs-12">
												<button class="btn btn-info" type="submit">Add</button>
												<a class="btn btn-primary" href="<?php echo base_url() ?>Material">Back</a>
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

	function fetch_plant(x) {

		if ($(x).val() != "") {
			$.ajax({
				url: baseurl + "Material/fetch_plant/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$(x).parent().parent().find('.plant_id').html("");
					$(x).parent().parent().find('.plant_id').append('<option value="">Plants</option>');
					$.each(data, function(key, value) {
						$(x).parent().parent().find('.plant_id').append('<option value="' + value.id + '">' + value.plant_name + '</option>');
					});
				}
			});
		}
	}

	function fetch_store(x) {

		if ($(x).val() != "") {
			$.ajax({
				url: baseurl + "Material/fetch_store/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$(x).parent().parent().find('.store_id').html("");
					$(x).parent().parent().find('.store_id').append('<option value="">Store</option>');
					$.each(data, function(key, value) {
						$(x).parent().parent().find('.store_id').append('<option value="' + value.id + '">' + value.store_name + '</option>');
					});
				}
			});
		}
	}

	function check() {
		var err = 1;
		var ctr = 0;

		// var plant = $("#tbody tr:first-child").find(".plant_id").val();
		// var store = $("#tbody tr:first-child").find(".store_id").val();
		// $('#tbody tr').each(function() {
		// 	if (ctr > 0) {
		// 		if ($(this).find(".plant_id").val() == plant && $(this).find(".store_id").val() == store) {
		// 			err++;
		// 		}
		// 	}

		// 	ctr++;
		// });

		if (err > 1) {
			alert("Same Material Can't be put in Same Plant & Store");
			return false;
		} else {
			return true;
		}
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



	function getMat_subgroup(x) {
		//alert();

		if ($(x).val() != "") {
			$.ajax({
				url: baseurl + "Material/fetch_material_subgroup/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						// $(x).parent().find('.material_type_id').append('<option value="'+ value.id +'">'+ value.material_subgroup +'</option>'); 
						$("#material_type_id").append('<option value="' + value.id + '" >' + value.material_subgroup + '</option>');

					});
				}
			});

			$.ajax({
				url: baseurl + "Material/fetch_material_group_desc/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						$("#material_group_desc").val(data[0].material_group);
					});
				}
			});



		}
	}


	function generateMat_code() {
		var codeprefix = '';
		var group = $('#material_group_id').find(":selected").data("group");

		if ($('#material_group_id').val() != '' && $('#mat_item_type').val() != '') {
			groupcode = group.substr(0, 4);
			codeprefix = $('#mat_item_type').val() == 'capital' ? 'CAP' + (groupcode.toUpperCase()) : groupcode.toUpperCase();
			$.ajax({
				url: baseurl + "Material/reserve_code/",
				type: "GET",
				data: {
					codeprefix: codeprefix,
					lastcode: $('#material_code').val()
				},
				dataType: "json",
				success: function(data) {
					$('#material_code').val(data);
				}
			});

		} else {
			alert('Please Select both (Type & Group)');
		}
	}
</script>