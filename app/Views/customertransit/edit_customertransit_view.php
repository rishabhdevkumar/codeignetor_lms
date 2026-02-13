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
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('/CustomerTransit/updateData/' . $transit_id) ?>" style="width:100%">
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
											<select class="form-control" name="from_country" id="from_country" required>
												<option value="">Select Country</option>
												<?php if (!empty($countries)): ?>
													<?php foreach ($countries as $country): ?>
														<option value="<?= esc($country['COUNTRY_ID']); ?>"
															<?= (isset($from_country) && $from_country == $country['COUNTRY_ID']) ? 'selected' : ''; ?>>
															<?= esc($country['COUNTRY_NAME']); ?>
														</option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>From PinCode</label>
											<input type="text" class="form-control" name="from_pincode" id="from_pincode" value="<?php echo $from_pincode; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>


								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>To Country</label>
											<select class="form-control" name="to_country" id="to_country" required>
												<option value="">Select Country</option>
												<?php if (!empty($countries)): ?>
													<?php foreach ($countries as $country): ?>
														<option value="<?= esc($country['COUNTRY_ID']); ?>"
															<?= (isset($to_country) && $to_country == $country['COUNTRY_ID']) ? 'selected' : ''; ?>>
															<?= esc($country['COUNTRY_NAME']); ?>
														</option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>To PinCode</label>
											<input type="text" class="form-control" name="to_pincode" id="to_pincode" value="<?php echo $to_pincode; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-4 col-xs-12">
											<label>Distance</label>
											<input type="number" class="form-control" name="distance" id="distance" value="<?php echo $distance; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-4 col-xs-12">
											<label>Transit Time</label>
											<input type="number" class="form-control" name="transit_time" id="transit_time" value="<?php echo $transit_time; ?>">
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