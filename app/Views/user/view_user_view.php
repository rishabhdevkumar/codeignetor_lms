<?php
$name = isset($_POST["name"]) ? $_POST["name"] : $user[0]["name"];
$user_name = isset($_POST["user_name"]) ? $_POST["user_name"] : $user[0]["user_name"];
$status = isset($_POST["status"]) ? $_POST["status"] : $user[0]["status"];
$role = isset($_POST["role"]) ? $_POST["role"] : $user[0]["role"];
$authorities = isset($_POST["authorities"]) ? $_POST["authorities"] : $user[0]["authorities"];
$authorities = explode(",", $authorities);

$menu_control = json_decode($user[0]["sub_menu_auth"], true);

//echo $user[0]["user_name"]." : "."<pre>";print_r($menu_control);echo "<br>";print_r($sub_menu_auth);die;
$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : $user[0]["id"];
$email = isset($_POST["email"]) ? $_POST["email"] : $user[0]["email"];
$contact_no = isset($_POST["contact_no"]) ? $_POST["contact_no"] : $user[0]["contact_no"];
?>
<div class="row" style="float:left;width:100%">

	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	<div class="col-sm-3" style="float:left;" style="margin-top:20px"></div>
	<div class="col-sm-6" style="float:left;" style="margin-top:20px">
		<?php echo $this->session->flashdata('message'); ?>
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
										<input type="text" class="form-control <?php if (!empty(form_error('name'))) echo 'is-invalid'; ?>" name="name" id="name" Placeholder="Enter Name" disabled autocomplete="off" value="<?php echo $name; ?>">
									</div>
								</div>
							</div>
							<div class="hr-line-dashed"></div>


							<div class="form-group">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<select class="form-control <?php if (!empty(form_error('status'))) echo 'is-invalid'; ?>" name="status" id="status" disabled>
											<option value="">Select</option>
											<option value="1" <?php if ($status == 1) echo "selected"; ?>>Active</option>
											<option value="0" <?php if ($status == 0) echo "selected"; ?>>Deactive</option>
										</select>
										<div class="error"><?php echo form_error('status'); ?></div>
									</div>
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12 col-xs-12">
										<select class="form-control <?php if (!empty(form_error('role'))) echo 'is-invalid'; ?>" name="role" id="role" disabled>

											<option value="">Select</option>
											<option value="1" <?php if ($role == 1) echo "selected"; ?>>Admin</option>
											<option value="2" <?php if ($role == 2) echo "selected"; ?>>Staff</option>
											<option value="3" <?php if ($role == 3) echo "selected"; ?>>Employee</option>
										</select>
										<div class="error"><?php echo form_error('role'); ?></div>
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
											<div class="i-checks"><label> <input disabled type="checkbox" name="authorities[]" value="<?php echo $auth[$k]['order_id'] ?>" <?php echo (in_array($auth[$k]['order_id'], $authorities)) ? "checked='checked'" : "" ?>> <?php echo $auth[$k]['menu_name'] ?> </label></div>
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
	<div class="col-sm-12" style="float:left;" style="margin-top:20px">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Sub Menu Authorities <small> </small></h5> <br />

				<input id="myInput" class="form-control" style="max-width:150px;" type="text" placeholder="Filter..">
			</div>

			<div class="ibox-content" id="sub_menus" style="max-height:500px;overflow-y: scroll;">

				<div class="row">

					<?php
					foreach ($sub_menu_auth as $k => $v) {
						if (isset($menu_control[$sub_menu_auth[$k]['id']])) {
							if (in_array('index', $menu_control[$sub_menu_auth[$k]['id']])) {
					?>
								<div class="col-sm-4 col-xs-4 mb-0 menu_row">
									<div class="" style="color:#2C8F7B;"><label> <b> <?php echo $sub_menu_auth[$k]['sub_menu3'] ? $sub_menu_auth[$k]['sub_menu3'] : $sub_menu_auth[$k]['sub_menu2'] ?> </b><br></label>
									</div>
									<label> <input disabled type="checkbox" class='sub_menu_auth' style="vertical-align: text-bottom;width:18px;height:18px;" data-order_id="<?php echo $sub_menu_auth[$k]['order_id'] ?>" onclick="check_menu(this)" name="sub_auth_control[<?php echo $sub_menu_auth[$k]['id'] ?>][]" value="index" <?php echo isset($menu_control[$sub_menu_auth[$k]['id']]) ? in_array('index', $menu_control[$sub_menu_auth[$k]['id']]) ? "checked='checked'" : "" : ""; ?>>Index</label>
									<label> <input disabled type="checkbox" class='sub_menu_auth' style="vertical-align: text-bottom;width:18px;height:18px;" data-order_id="<?php echo $sub_menu_auth[$k]['order_id'] ?>" onclick="check_menu(this)" name="sub_auth_control[<?php echo $sub_menu_auth[$k]['id'] ?>][]" value="add" <?php echo isset($menu_control[$sub_menu_auth[$k]['id']]) ? in_array('add', $menu_control[$sub_menu_auth[$k]['id']]) ? "checked='checked'" : "" : ""; ?>>Add</label>
									<label> <input disabled type="checkbox" class='sub_menu_auth' style="vertical-align: text-bottom;width:18px;height:18px;" data-order_id="<?php echo $sub_menu_auth[$k]['order_id'] ?>" onclick="check_menu(this)" name="sub_auth_control[<?php echo $sub_menu_auth[$k]['id'] ?>][]" value="edit" <?php echo isset($menu_control[$sub_menu_auth[$k]['id']]) ? in_array('edit', $menu_control[$sub_menu_auth[$k]['id']]) ? "checked='checked'" : "" : ""; ?>>Edit</label>
									<label> <input disabled type="checkbox" class='sub_menu_auth' style="vertical-align: text-bottom;width:18px;height:18px;" data-order_id="<?php echo $sub_menu_auth[$k]['order_id'] ?>" onclick="check_menu(this)" name="sub_auth_control[<?php echo $sub_menu_auth[$k]['id'] ?>][]" value="view" <?php echo isset($menu_control[$sub_menu_auth[$k]['id']]) ? in_array('view', $menu_control[$sub_menu_auth[$k]['id']]) ? "checked='checked'" : "" : ""; ?>>View</label>
								</div>
					<?php
							}
						}
					}

					?>
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