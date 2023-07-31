<?php $this->load->view('admin/header');?>
<section id="middle">
	<!-- page title -->
	<header id="page-header">
	<h1><i class="fa fa-user"></i> User Profile</h1>
	<ol class="breadcrumb">
		<li><a href="#">User</a></li>
		<li class="active">Profile Details</li>
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
						<strong>Profile Details</strong>
					</div> -->
					<div class="panel-body">
							<form action="<?php echo ADMIN_PROFILE_DETAILS_SUBMIT_URL; ?>" method="post" class="validate" enctype="multipart/form-data">
								<fieldset>
									<!-- required [php action request] -->
									<input type="hidden" name="action" value="contact_send" />

									<div class="row">
										<div class="form-group">
											<div class="col-md-6 col-sm-6">
												<label>First Name </label>
												<input type="text" name="first_name" value="<?php echo (isset($user_details['first_name']))?$user_details['first_name']:'';?>" placeholder="Enter First Name..." class="form-control required" required="required">
											</div>
											<div class="col-md-6 col-sm-6">
												<label>Last Name </label>
												<input type="text" name="last_name" value="<?php echo (isset($user_details['last_name']))?$user_details['last_name']:'';?>" placeholder="Enter Last Name..." class="form-control required">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-md-6 col-sm-6">
												<label>Email </label>
												<input type="email" name="email" value="<?php echo (isset($user_details['email']))?$user_details['email']:'';?>" placeholder="Enter Email..." class="form-control required">
											</div>
											<div class="col-md-6 col-sm-6">
												<label>Phone </label>
												<input type="text" name="phone" minlength="10" maxlength="10" pattern="[0-9]+" title="Please Enter 10 digit no." value="<?php echo (isset($user_details['phone']))?$user_details['phone']:'';?>" placeholder="Enter Phone..." class="form-control required">
											</div>
										</div>
									</div>

									<div class="row">
										<div class="form-group">
											<div class="col-md-6">
												<label>
													File Attachment
												</label>

												<!-- custom file upload -->
												<div class="fancy-file-upload fancy-file-success">
													<i class="fa fa-upload"></i>
													<input type="file" class="form-control" name="image" onchange="jQuery(this).next('input').val(this.value);" />
													<input type="text" class="form-control"  placeholder="no file selected" readonly="" />
													<span class="button">Choose File</span>
												</div>
												<small class="text-muted block">Max file size: 10Mb (jpg/jpeg/png/gif)</small>

											</div>
											<div class="col-md-6 col-sm-6">
												<?php $profile_img = (isset($user_details['profile_img']))?$user_details['profile_img']:'';?>
												<label>Preview </label>
												<img height="100" width="100" class="img-circle" src="<?php echo PROFILE_DISPLAY_PATH_NAME; ?><?php echo $profile_img; ?>">
											</div>
										</div>
									</div>

								

								</fieldset>
								<br>
								 <div class="row text-center">
									<div class="col-md-12">
										<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
										<button type="submit" class="btn btn-3d btn-green">
											<i class="fa fa-save"></i>&nbsp;Submit
											<!-- <span class="block font-lato">We'll get back to you within 48 hours</span> -->
										</button>
										<a href="<?php echo ADMIN_DASHBOARD_URL; ?>" class="btn btn-3d btn-red">
										<i class="fa fa-close"></i>&nbsp;Cancel
									    </a>
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
