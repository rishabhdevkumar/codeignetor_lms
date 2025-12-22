<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<?php if (isset($error)) : ?>
		<div class="alert alert-danger">
			<?= $error ?>
		</div>
	<?php endif; ?>
	<form id="frm" autocomplete="off" method="post" action="<?= base_url('/Customer/add') ?>" enctype="multipart/form-data" style="width:100%">
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
											<input type="text" class="form-control" name="customer_code" id="customer_code" maxlength="5" value="<?php echo set_value('customer_code'); ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Customer Type</label>
											<select class="form-control" name="customer_type" id="customer_type" required >
												<option value="">Select</option>
												<option value="KC1">KC1</option>
												<option value="KC2">KC2</option>
												<option value="KC3">KC3</option>
												<option value="KC4">KC4</option>
											</select>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>


								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Customer Name</label>
											<textarea class="form-control" Placeholder="Enter Customer Name" name="customer_name" id="customer_name"><?php echo set_value('customer_name'); ?></textarea>
											<div class="error"></div>
										</div>
									</div>
								</div>


								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Price List No</label>
											<input type="text" class="form-control" name="price_list_no" id="price_list_no" maxlength="5" value="<?php echo set_value('price_list_no'); ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Parent Code</label>
											<input type="text" class="form-control" name="parent_code" id="parent_code" value="<?php echo set_value('parent_code'); ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Country</label>
											<input type="text" class="form-control" name="country" id="country" maxlength="5" value="<?php echo set_value('country'); ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Currency</label>
											<input type="text" class="form-control" name="currency" id="currency" value="<?php echo set_value('currency'); ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										
									   <div class="col-sm-6 col-xs-12">
											<label>State</label>
											<input type="text" class="form-control" name="state" id="state" value="<?php echo set_value('state'); ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>PinCode</label>
											<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo set_value('pincode'); ?>">
											<div class="error"></div>
										</div>

									

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Target</label>
											<input type="number" class="form-control" name="m_target" id="m_target" value="<?php echo set_value('m_target'); ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Dispatch</label>
											<input type="number" class="form-control" name="dispatch" id="dispatch" value="<?php echo set_value('dispatch'); ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<br><br>


								<div class="hr-line-dashed"></div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<button class="btn btn-info" type="submit">Add</button>
											<a class="btn btn-primary" href="<?php echo base_url() ?>Customer">Back</a>
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
				url: baseurl + "Customer/fetch_plant/" + $(x).val(),
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
				url: baseurl + "Customer/fetch_store/" + $(x).val(),
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
			alert("Same Customer Can't be put in Same Plant & Store");
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
				url: baseurl + "Customer/fetch_customer_subgroup/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						// $(x).parent().find('.customer_type_id').append('<option value="'+ value.id +'">'+ value.customer_subgroup +'</option>'); 
						$("#customer_type_id").append('<option value="' + value.id + '" >' + value.customer_subgroup + '</option>');

					});
				}
			});

			$.ajax({
				url: baseurl + "Customer/fetch_customer_group_desc/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						$("#customer_group_desc").val(data[0].customer_group);
					});
				}
			});



		}
	}


	function generateMat_code() {
		var codeprefix = '';
		var group = $('#customer_group_id').find(":selected").data("group");

		if ($('#customer_group_id').val() != '' && $('#mat_item_type').val() != '') {
			groupcode = group.substr(0, 4);
			codeprefix = $('#mat_item_type').val() == 'capital' ? 'CAP' + (groupcode.toUpperCase()) : groupcode.toUpperCase();
			$.ajax({
				url: baseurl + "Customer/reserve_code/",
				type: "GET",
				data: {
					codeprefix: codeprefix,
					lastcode: $('#customer_code').val()
				},
				dataType: "json",
				success: function(data) {
					$('#customer_code').val(data);
				}
			});

		} else {
			alert('Please Select both (Type & Group)');
		}
	}
</script>