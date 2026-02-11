<?php
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$contact_no = isset($_POST["contact_no"]) ? $_POST["contact_no"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
$status = isset($_POST["status"]) ? $_POST["status"] : "";
$role = isset($_POST["role"]) ? $_POST["role"] : "";
$authorities = isset($_POST["authorities"]) ? $_POST["authorities"] : array();
?>

<div class="row" style="float:left;width:100%">
	<form action="<?= base_url('User/insertData') ?>" id="frm" autocomplete="off" method="POST" style="width:100%">
		<!-- <input type="hidden" name="<?= csrf_field() ?>" value="<?= csrf_field() ?>" /> -->
		<?= csrf_field() ?>
		<div class="col-sm-3" style="float:left;margin-top:20px"></div>
		<div class="col-sm-6" style="float:left;margin-top:20px">
			<?= session()->getFlashdata('message'); ?>
			<div class="ibox float-e-margins">
				<div class="ibox-title">

				</div>
				<div class="ibox-content">
					<div class="form-horizontal">
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-xs-6">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('name'); ?>" name="name" id="name" Placeholder="Enter Name" autocomplete="off" value="<?php echo $name; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('name'); ?></div>
											<?php endif; ?>
										</div>
										<div class="col-sm-6 col-xs-6">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('user_name'); ?>" name="user_name" id="user_name" Placeholder="Enter User Name" autocomplete="off" value="<?php echo $user_name; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('user_name'); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<!-- <div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('user_name'); ?>" name="user_name" id="user_name" Placeholder="Enter User Name" autocomplete="off" value="<?php echo $user_name; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('user_name'); ?></div>
											<?php endif; ?>
										</div>
									</div>
								</div> -->

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-6 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('contact_no'); ?>" name="contact_no" id="contact_no" Placeholder="Enter Contact No." autocomplete="off" value="<?php echo $contact_no; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('contact_no'); ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="col-sm-6 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('email'); ?>" name="email" id="email" Placeholder="Enter Email." autocomplete="off" value="<?php echo $email; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('email'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<!-- <div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('email'); ?>" name="email" id="email" Placeholder="Enter Email." autocomplete="off" value="<?php echo $email; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('email'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div> -->

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<input type="password" class="form-control <?php if (isset($validation)) $validation->getError('password'); ?>" name="password" id="password" Placeholder="Enter Password" autocomplete="off" value="<?php echo $password; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('password'); ?>
												</div>
											<?php endif; ?>
										</div>

										<div class="col-sm-6 col-xs-12">
											<input type="password" class="form-control <?php if (isset($validation)) $validation->getError('confirm_password'); ?>" name="confirm_password" id="confirm_password" Placeholder="Retype Password" autocomplete="off" value="<?php echo $confirm_password; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('confirm_password'); ?>
												</div>
											<?php endif; ?>
										</div>

									</div>
								</div>

								<div class="hr-line-dashed"></div>

								<!-- <div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<input type="password" class="form-control <?php if (isset($validation)) $validation->getError('confirm_password'); ?>" name="confirm_password" id="confirm_password" Placeholder="Retype Password" autocomplete="off" value="<?php echo $confirm_password; ?>">
											<?php if (isset($validation)) : ?>
												<div class="error">
													<?= $validation->getError('confirm_password'); ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div> -->

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">

										<div class="col-sm-6 col-xs-12">
											<select class="form-control <?php if (isset($validation)) $validation->getError('status'); ?>" name="status" id="status">
												<option value="">Select</option>
												<option value="1" <?php if ($status == 1) echo "selected"; ?>>Active</option>
												<option value="0" <?php if ($status == 0) echo "selected"; ?>>Deactive</option>
											</select>
											<?php if (isset($validation)) : ?>
												<div class="error"><?= $validation->getError('status'); ?></div>
											<?php endif; ?>
										</div>

										<div class="col-sm-6 col-xs-12">
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

								<!-- <div class="form-group">
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
								</div> -->

								<div class="hr-line-dashed"></div>

								<div class="form-group">
									<div class="row">
										<?php foreach ($auth as $row): ?>
											<div class="col-sm-6 col-xs-12">
												<label style="font-weight:bold">
													<input
														type="checkbox"
														name="authorities[]"
														value="<?= esc($row['ORDER_ID']) ?>"
														id="authorities-<?= esc($row['ORDER_ID']) ?>"
														style="vertical-align: text-bottom;width:24px;height:24px;"
														onclick="deselect_submenu(this)"
														<?= in_array($row['ORDER_ID'], $authorities ?? []) ? 'checked' : '' ?>>
													<?= esc($row['MENU_NAME']) ?>
												</label>
											</div>
										<?php endforeach; ?>
									</div>
								</div>

								<div class="form-group">
									<div class="row">
										<div class="col-sm-12 col-xs-12">
											<button class="btn btn-info" type="submit">Add</button>
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
					<h5>Sub Menu Authorities <small> </small></h5> <br />
					<input id="myInput" class="form-control" style="max-width:150px;" type="text" placeholder="Filter..">
					<label style="vertical-align: -3px;"> <input style="width:20px;height:20px;" type="checkbox" id="selection"><span id="text_select" style="font-size:20px;color:#2C8F7B">Select All</span></label>

				</div>

				<div class="ibox-content">

					<div class="row" id="sub_menus" style="max-height:500px;overflow-y: scroll;">

						
						<?php
						$actions = ['index' => 'Index', 'add' => 'Add', 'edit' => 'Edit', 'view' => 'View'];

						foreach ($sub_menu_auth as $row) {
							$ppId = $row['PP_ID'];
							$menuName = $row['SUB_MENU3'] ?: $row['SUB_MENU2'];
							$selected = $menu_control[$ppId] ?? [];
						?>
							<div class="col-sm-4 col-xs-4 mb-0 menu_row">
								<div style="color:#2C8F7B;">
									<label><b><?= htmlspecialchars($menuName) ?></b></label>
								</div>

								<?php foreach ($actions as $key => $label): ?>
									<label>
										<input type="checkbox"
											class="sub_menu_auth"
											style="width:18px;height:18px;"
											data-order_id="<?= $row['ORDER_ID'] ?>"
											onclick="check_menu(this)"
											name="sub_auth_control[<?= $ppId ?>][]"
											value="<?= $key ?>"
											<?= in_array($key, $selected) ? 'checked' : '' ?>>
										<?= $label ?>
									</label>
								<?php endforeach; ?>
							</div>
						<?php
						}
						?>

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

	// function check_menu(x) {
	// 	order_id = $(x).data("order_id");

	// 	if ($(x).prop("checked")) {
	// 		$("input[value='" + order_id + "']").prop("checked", true);
	// 	} else {
	// 		//$("input[value='"+order_id+"']").prop("checked",false);
	// 	}

	// }

	function check_menu(x) {
		const $row = $(x).closest('.menu_row');

		if ($(x).prop('checked') && $(x).val() !== 'index') {
			$row.find("input[value='index']").prop('checked', true);
		}

		// If index is unchecked → uncheck all
		if ($(x).val() === 'index' && !$(x).prop('checked')) {
			$row.find("input[type='checkbox']").prop('checked', false);
		}

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