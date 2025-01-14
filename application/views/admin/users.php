<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/backend/mybuild/css/intlTelInput.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/backend/mybuild/css/demo.css">

				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $this->lang->line('users') ?></h2>
					
					</header>

					<!-- start: page -->
					<div class="row">
						<div class="col-md-12">
							<section class="panel">
								<div class="panel-body">
									<a class="modal-with-form m_add" href="#add_modal"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp<?php echo $this->lang->line('add_new_user') ?></a>
									<br/>
									<br/>
									<table class="table table-bordered table-striped mb-none" id="datatable-default">
										<thead>
											<tr>
												<th class="center"><?php echo $this->lang->line('number') ?></th>
												<th class="center"><?php echo $this->lang->line('photo') ?></th>
												<th class="center"><?php echo $this->lang->line('name') ?></th>
												<th class="center"><?php echo $this->lang->line('email') ?></th>
												<th class="center"><?php echo $this->lang->line('password') ?></th>
												<th class="center"><?php echo $this->lang->line('phone_number') ?></th>
												<th class="center"><?php echo $this->lang->line('photos') ?></th>
												<th class="center"><?php echo $this->lang->line('secret') ?></th>
												<th class="center"><?php echo $this->lang->line('school_code') ?></th>
												<th class="center"><?php echo $this->lang->line('action') ?></th>
											</tr>
										</thead>
										<tbody>
											<?php for ($i=0; $i < count($data); $i++) { ?>
											<tr class="gradeX">
												<td class="center"><?php echo $i+1; ?></td>
												<td class="center"><img src="<?php echo base_url().'assets/user/'.$data[$i]['photo']; ?>" style="width:50px; height:auto;"/></td>
												<td class="center"><?php echo $data[$i]['name']; ?></td>
												<td class="center"><?php echo $data[$i]['email']; ?></td>
												<td class="center"><?php echo $data[$i]['password']; ?></td>
												<td class="center"><?php echo $data[$i]['country_code'].' '. $data[$i]['number']; ?></td>
												<td class="center"><?php echo $data[$i]['students']; ?></td>
												<td class="center"><?php echo $data[$i]['secret']; ?></td>
												<td class="center"><?php echo $data[$i]['school_code']; ?></td>
												<td class="center">
													<input type="hidden" class="m_hide" value="<?php echo $i; ?>" />
													<a class="modal-with-form m_edit" href="#edit_modal" style="color: blue;"><?php echo $this->lang->line('edit') ?></a> &nbsp|&nbsp
													<a class="modal-basic" href="#modalBasic_<?php echo $i;?>" style="color: red;"><?php echo $this->lang->line('delete') ?></a>

													<div id="modalBasic_<?php echo $i;?>" class="modal-block mfp-hide">
														<section class="panel">
															<header class="panel-heading">
																<h2 class="panel-title"><?php echo $this->lang->line('warning') ?></h2>
															</header>
															<div class="panel-body">
																		<p><?php echo $this->lang->line('delete_item') ?></p>
															</div>
															<footer class="panel-footer">
																<div class="row">
																	<div class="col-md-12 text-right">
																		<a class="btn btn-primary" href="<?php echo base_url().'admin/delete_item/tbl_user/'.$data[$i]['id']; ?>" ><?php echo $this->lang->line('confirm') ?></a>
																		<a class="btn btn-default modal-dismiss"><?php echo $this->lang->line('cancel') ?></a>
																	</div>
																</div>
															</footer>
														</section>
													</div>
													
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>							
								</div>
							</section>
						</div>
					</div>
				</section>
			</div>
		</section>
		<!-- Modal Form -->
		<div id="add_modal" class="modal-block modal-block-primary mfp-hide">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title"><?php echo $this->lang->line('add_new_user') ?></h2>
				</header>
				<div class="panel-body">
					<form id="add-form" action="#" method="post" class="form-horizontal mb-lg" >
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('name') ?></label>
							<div class="col-sm-9">
								<input id="add_name" type="text" name="add_name" class="form-control" placeholder="Type name..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('email') ?></label>
							<div class="col-sm-9">
								<input id="add_email" type="email" name="add_email" class="form-control" placeholder="Type email..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('password') ?></label>
							<div class="col-sm-9">
								<input id="add_password" type="text" name="add_password" class="form-control" placeholder="Type password..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('school_code') ?></label>
							<div class="col-sm-9">
								<input id="add_school" type="text" name="add_school" class="form-control" placeholder="Type school code..." required/>
							</div>
						</div>
						<div class="form-group mt-lg">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('phone_number') ?></label>
							<div class="col-sm-9">
								<!-- <input id="add_site" type="text" name="add_site" class="form-control" placeholder="Type site name..." required/> -->
								<input id="add_phone" name="add_phone" type="tel"  class="form-control" required>
							</div>
						</div>
						
					</form>
					<p id="add_warning" style="color: red; text-align: center;" hidden="hidden"><?php echo $this->lang->line('warning') ?> </p>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button id="add_button" class="btn btn-primary"><?php echo $this->lang->line('submit') ?></button>
							<button class="btn btn-default modal-dismiss"><?php echo $this->lang->line('cancel') ?></button>
						</div>
					</div>
				</footer>
			</section>
		</div>
		<!-- Modal Form -->
		<div id="edit_modal" class="modal-block modal-block-primary mfp-hide">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title"><?php echo $this->lang->line('edit_user') ?></h2>
				</header>
				<div class="panel-body">
					<form id="edit-form" action="#" method="post" class="form-horizontal mb-lg" novalidate="novalidate">
						<input id="edit_id" name="edit_id" type="hidden">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('name') ?></label>
							<div class="col-sm-9">
								<input id="edit_name" type="text" name="edit_name" class="form-control" placeholder="Type name..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('email') ?></label>
							<div class="col-sm-9">
								<input id="edit_email" type="email" name="edit_email" class="form-control" placeholder="Type email..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('password') ?></label>
							<div class="col-sm-9">
								<input id="edit_password" type="text" name="edit_password" class="form-control" placeholder="Type password..." required/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('school_code') ?></label>
							<div class="col-sm-9">
								<input id="edit_school" type="text" name="edit_school" class="form-control" placeholder="Type school code..." required/>
							</div>
						</div>
						<div class="form-group mt-lg">
							<label class="col-sm-3 control-label"><?php echo $this->lang->line('phone_number') ?></label>
							<div class="col-sm-9">
								<input id="edit_phone" name="edit_phone" type="tel"  class="form-control" required>
							</div>
						</div>
					</form>
					<p id="edit_warning" style="color: red; text-align: center;" hidden="hidden"><?php echo $this->lang->line('warning') ?> </p>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button id="edit_button" class="btn btn-primary"><?php echo $this->lang->line('submit') ?></button>
							<button class="btn btn-default modal-dismiss"><?php echo $this->lang->line('cancel') ?></button>
						</div>
					</div>
				</footer>
			</section>
		</div>
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url(); ?>/assets/backend/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="<?php echo base_url(); ?>/assets/backend/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>/assets/backend/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/theme.init.js"></script>


		<!-- Examples -->
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/tables/examples.datatables.default.js"></script>
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/tables/examples.datatables.tabletools.js"></script>
		<!-- Examples -->
		<script src="<?php echo base_url(); ?>/assets/backend/javascripts/ui-elements/examples.modals.js"></script>
		<script src="<?php echo base_url(); ?>/assets/backend/mybuild/js/intlTelInput.js"></script>
<script>

	var add_phone_instance = window.intlTelInput(document.querySelector("#add_phone"), {
		initialCountry: "ci",
		placeholderNumberType: "MOBILE",
       		preferredCountries: ['ci'],
		separateDialCode: true,
		utilsScript: "<?php echo base_url(); ?>/assets/backend/mybuild/js/utils.js",
	});

	var edit_phone_instance = window.intlTelInput(document.querySelector("#edit_phone"), {
		initialCountry: "ci",
		placeholderNumberType: "MOBILE",
		preferredCountries: ['ci'],
		separateDialCode: true,
		utilsScript: "<?php echo base_url(); ?>/assets/backend/mybuild/js/utils.js",
	});
	
	var data = [];

        <?php for ($i = 0; $i < count($data); $i++) {?>
		data[<?php echo $i; ?>] = [];
		data[<?php echo $i; ?>]['id'] = "<?php echo $data[$i]['id']; ?>";
		data[<?php echo $i; ?>]['name'] = "<?php echo $data[$i]['name']; ?>";
		data[<?php echo $i; ?>]['email'] = "<?php echo $data[$i]['email']; ?>";
		data[<?php echo $i; ?>]['password'] = "<?php echo $data[$i]['password']; ?>";
		data[<?php echo $i; ?>]['school'] = "<?php echo $data[$i]['school_code']; ?>";
		data[<?php echo $i; ?>]['code'] = "<?php echo $data[$i]['country_code']; ?>";
		data[<?php echo $i; ?>]['number'] = "<?php echo $data[$i]['number']; ?>";
        <?php } ?>

        $("a.m_edit").click(function() {
		var i = $(this).parent().find(".m_hide").val();
		console.log(i);
		$("#edit_id").val(data[i]['id']);
		$("#edit_name").val(data[i]['name']);
		$("#edit_email").val(data[i]['email']);
		$("#edit_password").val(data[i]['password']);
		$("#edit_school").val(data[i]['school']);
		edit_phone_instance.setNumber("+"+data[i]['code']+data[i]['number']);
		$("#edit_warning").hide();
	});

        $("a.m_add").click(function() {
		$("#add_email").val("");
		$("#add_site").val('');
		$("#add_password").val("");
		$("#add_date").val('');
		$("#add_warning").hide();
        });

        $("#add_button").click(function() {
		var name = $("#add_name").val().trim();
		if (name == '') {
			$("#add_warning").show();
			$("#add_warning").text("Please input 'Name' field.");
			$("#add_name").val('');
			return;
		}
		var email = $("#add_email").val().trim();
		if (email == '') {
			$("#add_warning").show();
			$("#add_warning").text("Please input 'Email' field.");
			$("#add_email").val('');
			return;
		}
		var password = $("#add_password").val().trim();
		if (password == '') {
			$("#add_warning").show();
			$("#add_warning").text("Please input 'Password' field.");
			$("#add_password").val('');
			return;
		}
		var school = $("#add_school").val().trim();
		if (school == '') {
			$("#add_warning").show();
			$("#add_warning").text("Please input 'School Code' field.");
			$("#add_school").val('');
			return;
		}
		var phone = $("#add_phone").val().replace(/\s/g, '');
		if (phone == '') {
			$("#add_warning").show();
			$("#add_warning").text("Please input 'Phone number' field.");
			$("#add_phone").val('');
			return;
		}
		var isValidEmail = validateEmail(email);
		if (!isValidEmail) {
			$("#add_warning").show();
			$("#add_warning").text("Please input valid email.");
			$("#add_email").val('');
			return;
		}
		var isValidPhone = add_phone_instance.isValidNumber();
		if (!isValidPhone) {
			$("#add_warning").show();
			$("#add_warning").text("Please input valid phone number.");
			$("#add_phone").val('');
			return;
		}
		var countryData = add_phone_instance.getSelectedCountryData();
		$("#add_warning").hide();
		var data = {
			'name'         : name,
			'email'        : email,
			'password'     : password, 
			'school'       : school, 
			'phone'        : phone, 
			'country_code' : countryData.dialCode
		};
		$.ajax({
			url : "<?php echo base_url(); ?>admin/add_user", 
			data : data, 
			type : 'POST', 
			success: function(result) {
				if (result == 200) {
					// window.history.go(0);
					location.reload();                    
				} else if (result == 400) {
					$("#add_warning").show();
					$("#add_warning").text("Phone number already exists. Please type another number.");
				} else {
					alert('Add Error');
				}
			}
		});
        });

        $("#edit_button").click(function() {
		var id = $("#edit_id").val().trim();
		var name = $("#edit_name").val().trim();
		if (name == '') {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input 'Name' field.");
			$("#edit_name").val('');
			return;
		}
		var email = $("#edit_email").val().trim();
		if (email == '') {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input 'Email' field.");
			$("#edit_email").val('');
			return;
		}
		var password = $("#edit_password").val().trim();
		if (password == '') {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input 'Password' field.");
			$("#edit_password").val('');
			return;
		}
		var school = $("#edit_school").val().trim();
		if (school == '') {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input 'School Code' field.");
			$("#edit_school").val('');
			return;
		}
		var phone = $("#edit_phone").val().replace(/\s/g, '');
		if (phone == '') {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input 'Phone number' field.");
			$("#edit_phone").val('');
			return;
		}
		var isValidEmail = validateEmail(email);
		if (!isValidEmail) {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input valid email.");
			$("#edit_email").val('');
			return;
		}
		var isValidPhone = edit_phone_instance.isValidNumber();
		if (!isValidPhone) {
			$("#edit_warning").show();
			$("#edit_warning").text("Please input valid phone number.");
			$("#edit_phone").val('');
			return;
		}

		var countryData = edit_phone_instance.getSelectedCountryData();

		$("#edit_warning").hide();

		var data = {
			'id'           : id, 
			'name'         : name,
			'email'        : email,
			'password'     : password, 
			'school'       : school, 
			'phone'        : phone, 
			'country_code' : countryData.dialCode
		};

		$.ajax({
			url : "<?php echo base_url(); ?>admin/edit_user", 
			data : data, 
			type : 'POST', 
			success: function(result) {
				if (result == 200) {
					// window.history.go(0);
					location.reload();                    
				} else if (result == 400) {
					$("#edit_warning").show();
					$("#edit_warning").text("Phone number already exists. Please type another number.");
				} else {
					alert('Add Error');
				}
			}
		});
	});

	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}

</script>
</body>
</html>
