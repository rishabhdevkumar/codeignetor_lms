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
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('/Machine/updateData/' . $machine_id) ?>" style="width:100%">
		<input type="hidden" name="machine_id" value="<?php echo $machine_id; ?>">
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
											<label>Machine Code</label>
											<input type="text" class="form-control" name="machine_code" id="machine_code" maxlength="20" value="<?php echo $machine_code; ?>" readonly>
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Type</label>
											<select class="form-control" name="type" id="type">
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
										<div class="col-sm-12 col-xs-12">
											<label>Description</label>
											<textarea class="form-control" Placeholder="Enter Description" name="description" id="description"><?php echo $description; ?></textarea>
											<div class="error"></div>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>SAP Plant</label>
											<input type="text" class="form-control" name="sap_plant" id="sap_plant" maxlength="5" autocomplete="off" value="<?php echo $sap_plant; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Vendor Code</label>
											<input type="text" class="form-control" name="vendor_code" id="vendor_code" value="<?php echo $vendor_code; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>PinCode</label>
											<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $pincode; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Capacity Per Day (MTS)</label>
											<input type="number" class="form-control" name="capacity_per_day" id="capacity_per_day" value="<?php echo $capacity_per_day; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Grade Change Time (Min)</label>
											<input type="number" class="form-control" name="grade_change_time" id="grade_change_time" value="<?php echo $grade_change_time; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>GSM Change Time (Min)</label>
											<input type="number" class="form-control" name="gsm_change_time" id="gsm_change_time" value="<?php echo $gsm_change_time; ?>">
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
				url: baseurl + "Machine/fetch_plant/" + $(x).val(),
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
				url: baseurl + "Machine/fetch_store/" + $(x).val(),
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

		var plant = $("#tbody tr:first-child").find(".plant_id").val();
		var store = $("#tbody tr:first-child").find(".store_id").val();
		$('#tbody tr').each(function() {
			if (ctr > 0) {
				if ($(this).find(".plant_id").val() == plant && $(this).find(".store_id").val() == store) {
					err++;
				}
			}

			ctr++;
		});

		if (err > 1) {
			alert("Same Machine Can't be put in Same Plant & Store");
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

	//     function checkcode(machine_code)
	// 	{  
	// 	   	var machine_code = $('#machine_code').val(); 
	// 	   	var reg = /^[A-Za-z0-9]{11}[0-9]{3}$/;
	// 	   	if(machine_code =='')
	// 	   	{
	// 	   		alert('Machine code required');
	// 	   	} 
	// 	  	else if(machine_code.length !=14)
	// 		{
	// 			alert('machine code must be of 14 digits');
	// 		}
	// 		else if (reg.test(machine_code) == false) 
	// 		{
	// 		    alert('Invalid machine code');
	// 		    return (false);
	// 		}
	// 		else if(machine_code != '')  
	// 		{  
	// 		    $.ajax({  
	// 			        url:"<?php echo base_url(); ?>Machine/check_machine_code_avalibility",  
	// 			        method:"GET",  
	// 			        data:{machine_code:machine_code},  
	// 			        success:function(data){  
	// 			            $('#code_result').html(data);  
	// 			        } 
	// 		    	});  
	// 		}  	

	// 	}


	// 	function textcheck(inputtxt)
	// 	{
	// 		txt=inputtxt.value;
	// 		len=txt.length;	

	// 		if(len==11){
	// 			var pattrn='([a-zA-Z0-9]{11})';
	// 			if(!(txt.match(pattrn))){
	// 				document.getElementById('code_result').innerHTML='<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Wrong pattern, First 11 character is Must be AlphaNumeric</label>';
	// 				inputtxt.maxLength='11';
	// 			}else{
	// 				inputtxt.maxLength='14';
	// 				document.getElementById('code_result').innerHTML='';
	// 			}
	// 		}
	// 		if(len>11){
	// 			res = txt.substring(11,len);
	// 			inputtxt.maxLength='14';
	// 			var pattrn='([a-zA-Z0-9]{11})([0-9]{'+(len-11)+'})';
	// 			if(!(txt.match(pattrn))){
	// 				document.getElementById('code_result').innerHTML='<label class="text-danger"><span class="glyphicon glyphicon-remove"></span> Wrong pattern, Numerics will be must after first 11 characters </label>';
	// 				inputtxt.maxLength=len;
	// 			}else{
	// 				inputtxt.maxLength='14';
	// 				document.getElementById('code_result').innerHTML='';
	// 			}
	// 		}
	// 	}


	function getMat_subgroup(x) {
		//alert();

		if ($(x).val() != "") {
			$.ajax({
				url: baseurl + "Machine/fetch_machine_subgroup/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						// $(x).parent().find('.machine_type_id').append('<option value="'+ value.id +'">'+ value.machine_subgroup +'</option>'); 
						$("#machine_type_id").append('<option value="' + value.id + '" selected>' + value.machine_subgroup + '</option>');
					});
				}
			});
		}
	}
</script>