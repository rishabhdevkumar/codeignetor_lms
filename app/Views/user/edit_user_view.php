<?php
$name = isset($_POST["name"]) ? $_POST["name"] : $user[0]["NAME"];
$username = isset($_POST["username"]) ? $_POST["username"] : $user[0]["USERNAME"];
$status = isset($_POST["status"]) ? $_POST["status"] : $user[0]["STATUS"];
$role = isset($_POST["role"]) ? $_POST["role"] : $user[0]["ROLE"];
$authorization = isset($_POST["authorization"]) ? $_POST["authorization"] : $user[0]["AUTHORIZATION"];
$authorization = explode(",", $authorization);

$menu_control = json_decode(html_entity_decode($user[0]["SUB_MENU_AUTH"]), true);

//echo $user[0]["USERNAME"]." : "."<pre>";print_r($menu_control);echo "<br>";print_r($sub_menu_auth);die;
$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : $user[0]["PP_ID"];
$email = isset($_POST["email"]) ? $_POST["email"] : $user[0]["EMAIL"];
$contact_no = isset($_POST["contact_no"]) ? $_POST["contact_no"] : $user[0]["CONTACT_NO"];
?>
<div class="row" style="float:left;width:100%">
	<form action="<?= base_url('User/updateData/' . $user_id) ?>" id="frm" autocomplete="off" method="POST" style="width:100%">
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		<?= csrf_field() ?>
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>
		<div class="col-sm-6" style="float:left;margin-top:20px">
			<?= session()->getFlashdata('message'); ?>
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<!-- <h5><?php echo $title; ?><small> </small></h5> -->
				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('name'); ?>" name="name" id="name" Placeholder="Enter Name" autocomplete="off" value="<?php echo $name; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('name'); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('contact_no'); ?>" name="contact_no" id="contact_no" Placeholder="Enter Contact No" autocomplete="off" value="<?php echo $contact_no; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('contact_no'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('email'); ?>" name="email" id="email" Placeholder="Enter Email" autocomplete="off" value="<?php echo $email; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('email'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<select class="form-control <?php if (isset($validation)) $validation->getError('status'); ?>" name="status" id="status">
												<option value="">Select</option>
												<option value="1" <?php if ($status == 1) echo "selected"; ?>>Active</option>
												<option value="0" <?php if ($status == 0) echo "selected"; ?>>Deactive</option>
											</select>
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('status'); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<select class="form-control <?php if (isset($validation)) $validation->getError('role'); ?>" name="role" id="role">

												<option value="">Select</option>
												<option value="1" <?php if ($role == 1) echo "selected"; ?>>Admin</option>
												<option value="2" <?php if ($role == 2) echo "selected"; ?>>Staff</option>
												<option value="3" <?php if ($role == 3) echo "selected"; ?>>Employee</option>
											</select>
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('role'); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<?php
										foreach ($auth as $k => $v) {
										?>
											<div class="col-sm-6 col-xs-12">
												<div class=""><label> <input type="checkbox" name="authorization[]" style="vertical-align: text-bottom;width:24px;height:24px;" onclick="deselect_submenu(this)" value="<?php echo $auth[$k]['ORDER_ID'] ?>" id="authorization-<?php echo $auth[$k]['ORDER_ID'] ?>" <?php echo (in_array($auth[$k]['ORDER_ID'], $authorization)) ? "checked='checked'" : "" ?>> <?php echo $auth[$k]['MENU_NAME'] ?> </label>
												</div>
											</div>
										<?php
										}

										?>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<button class="btn btn-info" type="submit">Update</button>
											<a class="btn btn-primary" href="<?php echo base_url() ?>User">Back</a>
										</div>
									</div>
								</div>

							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12" style="float:left;margin-top:20px">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Sub Menu AUTHORIZATION <small> </small></h5> <br />
					<input id="myInput" class="form-control" style="max-width:150px;" type="text" placeholder="Filter..">
					<label style="vertical-align: -3px;"> <input style="vertical-align: text-bottom;width:20px;height:20px;" type="checkbox" id="selection"><span id="text_select" style="font-size:20px;color:#2C8F7B">Select All</span></label>
				</div>

				<div class="ibox-content">

					<div class="row" id="sub_menus" style="max-height:500px;overflow-y: scroll;">

						<?php
						$actions = ['index' => 'Index', 'add' => 'Add', 'edit' => 'Edit', 'view' => 'View'];

						foreach ($sub_menu_auth as $menu) :

							$menu_id  = $menu['PP_ID'];
							$order_id = $menu['ORDER_ID'];

							$title = !empty($menu['SUB_MENU3']) ? $menu['SUB_MENU3'] : $menu['SUB_MENU2'];

							$selected_actions = $menu_control[$menu_id] ?? [];
						?>

							<div class="col-sm-4 col-xs-4 mb-0 menu_row">

								<div style="color:#2C8F7B;">
									<label>
										<b><?= esc($title) ?></b><br>
									</label>
								</div>

								<?php foreach ($actions as $value => $label) : ?>

									<label>
										<input
											type="checkbox"
											class="sub_menu_auth"
											style="vertical-align:text-bottom;width:18px;height:18px;"
											data-order_id="<?= esc($order_id) ?>"
											onclick="check_menu(this)"
											name="sub_auth_control[<?= esc($menu_id) ?>][]"
											value="<?= esc($value) ?>"
											<?= in_array($value, $selected_actions) ? 'checked' : '' ?>>
										<?= esc($label) ?>
									</label>

								<?php endforeach; ?>

							</div>

						<?php endforeach; ?>

					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$("input[id='selection']").change(function() {
			var cheksts = $("input[id='selection']").is(":Checked");
			$(".sub_menu_auth").prop("checked", cheksts);
			if (cheksts)
				$("#text_select").html("Deselect All");
			else
				$("#text_select").html("Select All");
		});


	});

	function check_menu(x) {
		order_id = $(x).data("order_id");

		if ($(x).prop("checked")) {
			$("input[value='" + order_id + "']").prop("checked", true);
		} else {
			//$("input[value='"+order_id+"']").prop("checked",false);
		}

	}

	function deselect_submenu(x) {
		order_id = $(x).val();
		if ($(x).prop("checked")) {
			// $("input[data-order_id='"+order_id+"']").each(function(){
			//     $(this).prop("checked",true);
			//     // alert(1);
			// });
		} else {
			$("input[data-order_id='" + order_id + "']").each(function() {
				$(this).prop("checked", false);
				// alert(2);
			});
		}
	}
</script>
<script>
	$(document).ready(function() {
		$("#myInput").on("keyup", function() {
			var value = $(this).val().toLowerCase();
			$("#sub_menus .menu_row").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	});
</script>