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
	<form id="frm" autocomplete="off" method="POST" action="<?= base_url('/Material/updateData/' . $material_id) ?>" style="width:100%">
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

										<div class="col-sm-6 col-xs-12">
											<label>Material Code</label>
											<input type="text" class="form-control" name="material_code" id="material_code" maxlength="20" value="<?php echo $material_code; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>SAP Plant</label>
											<input type="text" class="form-control" name="sap_plant" id="sap_plant" maxlength="5" value="<?php echo $sap_plant; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Grade</label>
											<input type="text" class="form-control" name="grade" id="grade" maxlength="5" value="<?php echo $grade; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>GSM</label>
											<input type="text" class="form-control" name="gsm" id="gsm" maxlength="5" value="<?php echo $gsm; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>UOM</label>
											<input type="text" class="form-control" name="uom" id="uom" maxlength="5" value="<?php echo $uom; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Item Type</label>
											<input type="text" class="form-control" name="item_type" id="item_type" value="<?php echo $item_type; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>Width</label>
											<input type="number" class="form-control" name="width" id="width" value="<?php echo $width; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Length</label>
											<input type="number" class="form-control" name="length" id="length" value="<?php echo $length; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<label>MR Material Code</label>
											<input type="text" class="form-control" name="mr_material_code" id="mr_material_code" maxlength="20" value="<?php echo $mr_material_code; ?>">
											<div class="error"></div>
										</div>

										<div class="col-sm-6 col-xs-12">
											<label>Packaging Time</label>
											<input type="number" class="form-control" name="packaging_time" id="packaging_time" value="<?php echo $packaging_time; ?>">
											<div class="error"></div>
										</div>

									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<label>Description</label>
											<textarea class="form-control" Placeholder="Enter Description" name="description" id="description"><?php echo $description; ?></textarea>
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

	//     function checkcode(material_code)
	// 	{  
	// 	   	var material_code = $('#material_code').val(); 
	// 	   	var reg = /^[A-Za-z0-9]{11}[0-9]{3}$/;
	// 	   	if(material_code =='')
	// 	   	{
	// 	   		alert('Material code required');
	// 	   	} 
	// 	  	else if(material_code.length !=14)
	// 		{
	// 			alert('material code must be of 14 digits');
	// 		}
	// 		else if (reg.test(material_code) == false) 
	// 		{
	// 		    alert('Invalid material code');
	// 		    return (false);
	// 		}
	// 		else if(material_code != '')  
	// 		{  
	// 		    $.ajax({  
	// 			        url:"<?php echo base_url(); ?>Material/check_material_code_avalibility",  
	// 			        method:"GET",  
	// 			        data:{material_code:material_code},  
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
				url: baseurl + "Material/fetch_material_subgroup/" + $(x).val(),
				type: "GET",
				dataType: "json",
				success: function(data) {
					$.each(data, function(key, value) {
						// $(x).parent().find('.material_type_id').append('<option value="'+ value.id +'">'+ value.material_subgroup +'</option>'); 
						$("#material_type_id").append('<option value="' + value.id + '" selected>' + value.material_subgroup + '</option>');
					});
				}
			});
		}
	}
</script>