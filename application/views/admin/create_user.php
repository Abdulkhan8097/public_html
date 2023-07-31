<?php $this->load->view('admin/header'); ?>


<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i>User Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">User</a></li>
         <li class="active">Create User</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Create User</strong>
               <!-- <small class="size-12 weight-300 text-mutted hidden-xs">2015</small> -->
            </span>
            <ul class="options pull-right list-inline">
               <li>
                  <a href="#" class="opt panel_colapse" data-toggle="tooltip" title="Colapse"
                     data-placement="bottom"></a>
               </li>
               <li>
                  <a href="#" class="opt panel_fullscreen hidden-xs" data-toggle="tooltip" title="Fullscreen"
                     data-placement="bottom"><i class="fa fa-expand"></i></a>
               </li>
            </ul>
         </div>
         <div class="panel-body">
           
            <div class="row">
               <div class="col-md-12">  
                  <form action="<?php echo ADMIN_SAVE_USER_URL; ?>" id="myform" method="POST" enctype="multipart/form-data" >
                     <div class="row">
                        <div class="col-md-4">
                            <?php 
         $this->CI = &get_instance();
         $shop_id = $this->session->userdata('user_id');
      $this->CI->load->model('admin/User_model', 'user_model');
      $user_id = $this->session->userdata('user_id');
      $userdetails = $this->CI->user_model->getUserDetails($shop_id);
         if($userdetails['user_type']=='3'){
          ?>

                           <div class="form-group">
                              <label for="city">User Type<span class="required"></span></label>
                              <select name="user_type" class="form-control" id="type">
                                 <option selected disabled>--Select Type--</option>
                                 
                                 <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='3') ? 'selected' : ''; ?>>Sales</option>
                                 <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='4') ? 'selected' : ''; ?>>Dealer</option>
                                 <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='5') ? 'selected' : ''; ?>>Distributor</option>

                                 <?php }elseif($userdetails['user_type']=='1'){ ?>
<div class="form-group">
                              <label for="city">User Type<span class="required"></span></label>
                              <select name="user_type" class="form-control" id="type">
<option selected disabled>--Select Type--</option>
                                 <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='1') ? 'selected' : ''; ?>>Admin</option>
                                 <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='2') ? 'selected' : ''; ?>>Customer</option>
                                 <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='3') ? 'selected' : ''; ?>>Sales</option>
                                 <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='4') ? 'selected' : ''; ?>>Dealer</option>
                                 <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['user_type']=='5') ? 'selected' : ''; ?>>Distributor</option>

<?php } ?>

                              </select>
                           </div>
                        </div>


                        <div class="col-md-4"> 
                           <div class="form-group">
                              <label for="city">Company Name<span ></span></label>
                              <select name="company_id" class="form-control" id="article" required>
                                 <option value="">Select Company Name</option>
                                 
                                 <?php if(isset($company) && !empty($company)){foreach ($company as $key => $value) {                       
                                        ?>
                                 <option value="<?php echo $value['company_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['company_id']==$value['company_id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['c_name']; ?>
                                 </option>
                                 <?php }}
                                     ?>          
                              </select>
                           </div>
                        </div>


                        

                        <?php if(isset($edit))
                        { ?>
                           <div class="col-md-4">
                           <div class="form-group">
                              <label for="customer">Customer ID<span class="required"></span></label>
                              <input name="customer_id" type="text" class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['customer_id'] : ''; ?>" placeholder="Customer ID" readonly>     
                           </div>
                        </div>
                     <?php } else{ ?>
                     <div class="col-md-4">
                           <div class="form-group">
                              <label for="customer">Customer ID<span class="required"></span></label>
                              <input name="customer_id" type="text" class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['customer_id'] : ''; ?>" placeholder="Customer ID">     
                           </div>
                        </div>
                      
                       <?php } ?>
<div class="col-md-4">
                           <div class="form-group">
                              <label for="city">Price Comparion Aceess<span class="required"></span></label>
                              <select name="Price_Comparison_Aceess" class="form-control" id="type">
                                 <option selected disabled>--Select Type--</option>
                                 <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['Price_Comparison_Aceess']=='Yes') ? 'selected' : ''; ?>>YES</option>
                                 <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['Price_Comparison_Aceess']=='No') ? 'selected' : ''; ?>>NO</option>
                                
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="customer">First Name<span class="required">*</span></label>
                              <input name="first_name" type="text" class="form-control"
                                 placeholder="First Name" onfocusout="checkName(this)"
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['first_name'] : ''; ?>"
                                 >
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="customer">Last Name<span class="required">*</span></label>
                              <input name="last_name" type="text" class="form-control" id="customer"
                                 placeholder="Last Name"
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['last_name'] : ''; ?>"
                                 >
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="customer">Password<span class="required">*</span></label>
                              <input name="password" type="text" class="form-control" id="customer"
                                 placeholder="Password"
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['password'] : ''; ?>"
                                 >
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="email">Email<span class="required"></span></label>
                              <input name="email" type="email" class="form-control" onblur="checkMailStatus()" id="email" placeholder="Email" required
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['email'] : ''; ?>">
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="phone">Phone No.<span class="required">*</span></label>
                              <input name="phone" type="tel" pattern="[0-9]+" minlength="10" maxlength="10"
                                 class="form-control" id="phone" placeholder="Phone No."
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['phone'] : ''; ?>"
                                 >
                           </div>
                        </div>
			<div class="col-md-4">
                           <div class="form-group">
                              <label for="discount">Discount  (Percentage)</label>
                              <input name="discount" type="tel" step="any"  maxlength="6"
                                 class="form-control" id="discount" placeholder="Discount..."
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['discount'] : ''; ?>"
                                 >
                           </div>
                        </div>
                       

					   <div class="col-md-4">
                           <div class="form-group">
                              <label for="address">GSTN<span class="required"></span></label>
                              <input name="gst" type="text" class="form-control" id="address"
                                 placeholder="GSTN"
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['gst'] : ''; ?>">
                           </div>
                        </div> 
						
						<?php
						
						// $countryList = array("IN" => "India","NP" => "Nepal");
						// $country= @$edit['country'];
						#print $country;exit;
						?>
						<div class="col-md-4">
                           <!-- <div class="form-group">
                              <label for="address">Country<span class="required"></span></label>
                              <select name="country" class="form-control" id="country">
                                 
                                 <?php
								 foreach($countryList as $code => $name) {
									 if($code == $country)
									 {
										echo "<option selected value=\"$code\">$name</option>\n";
									 }else{
										echo "<option value=\"$code\">$name</option>\n"; 
									 }
								 }
								 
								 ?>
                                
                              </select>
                           </div> -->

                           <div class="form-group">
                              <label for="city">Country Name<span class="required"></span></label>
                              <select name="country" class="form-control" id="article">
                                 <option selected disabled><---Country Name---></option>
                                 
                                 <?php if(isset($list) && !empty($list)){foreach ($list as $key => $value) {                       
                                        ?>
                                 <option value="<?php echo $value['country_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['country']==$value['country_name']) ? 'selected' : ''; ?>>
                                    <?php  echo $value['country_name']; ?>
                                 </option> 
                                
                                 <?php }}
                                     ?>          
                              </select>
                           </div>



                           
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                            <label for="title">State Name</label>
                            <select name="state" class="form-control" required>
                           <option><?php echo (isset($edit) && !empty($edit)) ? $edit['state'] : ''; ?></option>
                           
                            </select>
                            </div> 
                        </div>

                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="address">Area<span class="required"></span></label>
                              <input name="area" type="text" class="form-control" id="address"
                                 placeholder="Area"
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['area'] : ''; ?>">
                           </div>
                        </div>

                      

                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="city">Master User<span class="required"></span></label>
                              <select name="master_user" class="form-control" id="type">
                                 <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['master_user']=='No') ? 'selected' : ''; ?>>NO</option>
                                 <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['master_user']=='Yes') ? 'selected' : ''; ?>>YES</option>
                                 
                                
                              </select>
                           </div>
                        </div>
						
						
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="address">Address<span class="required"></span></label>
                              <textarea name="address" class="form-control" placeholder="Address"><?php echo (isset($edit) && !empty($edit)) ? $edit['address'] : ''; ?></textarea>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label for="customer_image">User Image<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="profile_img" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-6">
                              <img src="<?php echo (isset($edit['profile_img']) && !empty($edit['profile_img'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['profile_img'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                        </div>
                        <!-- <div class="col-md-4">
                           <div class="form-group">
                             <input type="checkbox" name="isActive"  value="1" id="active" onclick="$(this).attr('value', this.checked ? 1 : 0)" checked>
                              <label class="" for="exampleCheck1">Is Active</label>
                             </div>   
                           </div>   -->                              
                     </div>
                     <div class="text-center">
                        <input type="hidden" name="user_id"
                           value="<?php echo (isset($edit) && !empty($edit)) ? $edit['user_id'] : ''; ?>">
                        <a href="<?php echo ADMIN_USER_URL; ?>" class="btn btn-3d btn-red text-center">
                        <i class="fa fa-backward"></i> Back
                        </a>
                        <button type="reset" class="btn btn-3d btn-default text-center">
                        <i class="fa fa-redo"></i> Reset
                        </button>
                        <button type="submit" id="Submit" name="Submit" class="btn btn-3d btn-green text-center">
                        <i class="fa fa-save"></i> Submit
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
</div>
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
   function display_img(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader()
       reader.onload = function (e) {
         $('#display_image_here')
           .attr('src', e.target.result)
           .width(100)
           .height(100)
       }
       reader.readAsDataURL(input.files[0])
     }
   }
</script>


<script type="text/javascript">


    $(document).ready(function() {
        $('select[name="country"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: '/admin/User/myformajax/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                         $('select[name="state"]').empty();
                        $.each(data, function(key, value) {
      $('select[name="state"]').append('<option value="'+ value.country_id +'">'+ value.state_name +'</option>');
       
                        });
                    }
                });
            }else{
                $('select[name="state"]').empty();
            }
        });
    });
</script>