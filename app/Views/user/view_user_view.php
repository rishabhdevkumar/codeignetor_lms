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

	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	<div class="col-sm-3" style="float:left;margin-top:20px"></div>
	<div class="col-sm-6" style="float:left;margin-top:20px">
		<?= session()->getFlashdata('message'); ?>
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
									<div class="col-sm-12 col-xs-12">
										<label>Name</label>
										<input type="text" class="form-control <?php if (isset($validation)) $validation->getError('name'); ?>" name="name" id="name" Placeholder="Enter Name" disabled autocomplete="off" value="<?php echo $name; ?>">
									</div>
								</div>
							</div>

							<div class="hr-line-dashed"></div>

							<div class="form-group">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<select class="form-control <?php if (isset($validation)) $validation->getError('status'); ?>" name="status" id="status" disabled>
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
										<select class="form-control <?php if (isset($validation)) $validation->getError('role'); ?>" name="role" id="role" disabled>

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
											<div class="i-checks"><label> <input disabled type="checkbox" name="authorities[]" value="<?php echo $auth[$k]['ORDER_ID'] ?>" <?php echo (in_array($auth[$k]['ORDER_ID'], $authorization)) ? "checked='checked'" : "" ?>> <?php echo $auth[$k]['MENU_NAME'] ?> </label></div>
										</div>
									<?php
									}
									?>
								</div>
							</div>

							<div class="form-group">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
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

<!-- </div>

<div class="row" style="float:left;width:100%"> -->
	<div class="col-sm-12" style="float:left;margin-top:20px;">
		<div class="ibox float-e-margins">

			<div class="ibox-title">
				<h5>Sub Menu Authorities <small> </small></h5> <br/>
				<input id="myInput" class="form-control" style="max-width:150px;" type="text" placeholder="Filter..">
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
									<input disabled
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
</div>

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