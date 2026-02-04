<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<div class="row" style="float:left;width:100%">
	<?php if (isset($error)) : ?>
		<div class="alert alert-danger">
			<?= $error ?>
		</div>
	<?php endif; ?>
	<form id="frm" autocomplete="off" method="post" action="<?= base_url('/CustomerTransit/insertData') ?>" enctype="multipart/form-data" style="width:100%">
		<div class="col-sm-2" style="float:left;margin-top:20px"></div>
		<div class="col-sm-8" style="float:left;margin-top:20px">
			<div class="ibox float-e-margins">
				<div style="background-color:#efd6bb; color:#000" class="ibox-title">
					<h5><?php echo $title; ?><small> </small></h5>
				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>From Country</label>
											<input type="text" class="form-control" name="from_country" id="from_country"
											 maxlength="20" value="<?php echo set_value('from_country'); ?>" required>
											<div class="error"></div>
										</div>
										<div class="col-sm-6 col-xs-12">
											<label>From PinCode</label>
											<input type="text" class="form-control" name="from_pincode" id="from_pincode"
											 value="<?php echo set_value('from_pincode'); ?>" required>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>To Country</label>
											<input type="text" class="form-control" name="to_country" id="to_country" maxlength="20" 
											value="<?php echo set_value('to_country'); ?>" required>
											<div class="error"></div>
										</div>
										<div class="col-sm-6 col-xs-12">
											<label>To PinCode</label>
											<input type="text" class="form-control" name="to_pincode" id="to_pincode"
											 value="<?php echo set_value('to_pincode'); ?>" required>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Distance</label>
											<input type="number" class="form-control" name="distance" id="distance"
											 value="<?php echo set_value('distance'); ?>" required>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Transit Time</label>
											<input type="number" class="form-control" name="transit_time" id="transit_time" 
											value="<?php echo set_value('transit_time'); ?>" required>
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<button class="btn btn-info btn-sm" type="submit">Add</button>
											<a class="btn btn-dark btn-sm" href="<?php echo base_url() ?>CustomerTransit">Back</a>
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