<?php $this->load->view('admin/header');?>
<section id="middle">
	<!-- page title -->
	<header id="page-header">
	<h1><i class="fa fa-user"></i> Change Password</h1>
	<ol class="breadcrumb">
		<li><a href="#">User</a></li>
		<li class="active">Change Password</li>
	</ol>
	</header>
	<!-- /page title -->

	<!-- /page title -->
	<div id="content" class="padding-20">
		<div class="row">
			<div class="col-md-12">
				<!-- ------ -->
				<div class="panel panel-default">
					<!-- <div class="panel-heading panel-heading-transparent">
						<strong>Details</strong>
					</div> -->
					<div class="panel-body">
							<form action="<?php echo ADMIN_CHANGE_PASSWORD_DETAILS_SUBMIT_URL; ?>" method="post" class="validate" enctype="multipart/form-data">
								<fieldset>
									<!-- required [php action request] -->
									<input type="hidden" name="action" value="contact_send" />

									<div class="row">
										<div class="form-group">
											<div class="col-md-6 col-sm-6">
												<label>Old Password <span style="color: red;">*</span></label>
												<input type="password" name="old_password" id="old_password" value="" placeholder="Old Password..." class="form-control required" required="required">
											</div>
											
										</div>
									</div>

									<div class="row">
										<div class="col-md-6 col-sm-6">
											<label>New Password <span style="color: red;">*</span></label>
											<input type="password" name="new_password" id="new_password" value="" placeholder="New Password..." class="form-control required" required="required">
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-md-6 col-sm-6">
												<label>Confirm Password <span style="color: red;">*</span></label>
												<input type="password" name="confirm_password" id="confirm_password" value="" placeholder="Confirm Password..." class="form-control required" required="required">
											</div>
										</div>
									</div>

								</fieldset>
								<br>
								 <div class="row">
									<div class="col-md-12">
										<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
										<button type="submit" class="btn btn-3d btn-green">
											<i class="fa fa-save"></i>&nbsp;Submit
											<!-- <span class="block font-lato">We'll get back to you within 48 hours</span> -->
										</button>
										<a href="<?php echo ADMIN_DASHBOARD_URL; ?>" class="btn btn-3d btn-red">
											<i class="fa fa-close"></i>&nbsp;Cancel</a>
									</div>
								</div> 
							</form>
					</div>

				</div>
				<!-- /----- -->
			</div>
		</div>
	</div>
</section>
<?php $this->load->view('admin/footer');?>
