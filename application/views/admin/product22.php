<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Product</a></li>
         <li class="active">Add Product</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Add Product</strong>
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
                  <form action="<?php echo ADMIN_SAVE_PRODUCT_URL; ?>" method="POST" enctype="multipart/form-data" >
                     <div class="row new_html">
                        <div class="col-md-4">
                           <div class="form-group" style="display:none">
                              <label for="customer">Type<span class="required">*</span></label>
                              <select name="type" id="article" class="form-control">
                                 <option selected disabled>--Select Type--</option>
                                 <option value="New Product" <?php echo (isset($edit_product) && !empty($edit_product) && $edit_product['type']=='New Product') ? 'selected' : ''; ?>>New Product</option>
                                 <option value="Upcoming Product" <?php echo (isset($edit_product) && !empty($edit_product) && $edit_product['type']=='Upcoming Product') ? 'selected' : ''; ?>>Upcoming Product</option>
                                 <option value="Regular Product" <?php echo (isset($edit_product) && !empty($edit_product) && $edit_product['type']=='Regular Product') ? 'selected' : ''; ?>> Regular Product</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group" >
                              <label for="city">Categories<span class="required">*</span></label>
                              <select name="category_id" class="form-control"
                                 id="category_id" onchange="Categories();">
                                 <option selected disabled>--Select Categories--</option>
                                 <?php if(isset($categories) && !empty($categories)){
                                    foreach ($categories as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['category_id']; ?>" <?php echo (isset($edit_product) && !empty($edit_product) && $edit_product['category_id']==$value['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['category_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>
                        </div>
                        <?php if(isset($edit_product))
                           { ?>
                        <div class="col-md-4">
                           <div class="form-group" style="display:none">
                              <label for="" >View Series</label>
                              <a href="<?php echo ADMIN_ADD_SERIES_URL.$edit_product['product_id'] ?>" class="form-control text-center">View Series</a>
                           </div>
                        </div>
                        <?php } 
                           else
                           { ?>
                        <div class="col-md-4">
                           <div class="form-group" style="display:none">
                              <label for="address">Series<span class="required">*</span></label>
                              <select name="series_id" class="form-control" id="series">
                                 <option selected disabled>--Select Series--</option>
                                 <?php if(isset($get_series) && !empty($get_series)){
                                    foreach ($get_series as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['series_id']; ?>">
                                    <?php echo $value['series_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>
                        </div>
                        <?php }
                           ?>
                        
                        <div class="col-md-4 cross_size_div" style="display: none">
                           <div class="form-group" style="display:none">
                              <label for="city">Cross Size<span class="required"></span></label>
                              <input name="cross_size" type="text" class="form-control" value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['cross_size'] : ''; ?>" placeholder="Cross Size" >
                           </div> 
                        </div>
                        <div class="col-md-4 plate_design_div" style="display: none">
                           <div class="form-group" style="display:none">
                              <label for="customer">Plate Design<span class="required"></span></label>
                              <input name="plate_design" type="text" class="form-control" value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['plate_design'] : ''; ?>" placeholder="Plate Design">     
                           </div>
                        </div>
                        
                         
                        
                        <div class="col-md-4 plate_diameter_div" style="display: none">
                           <div class="form-group" style="display:none">
                              <label for="customer">Plate Diameter</label>
                              <input name="plate_diameter" type="text" class="form-control"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['plate_diameter'] : ''; ?>"
                                 placeholder="Plate Diameter">
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 hole_size_div" style="display: none">
                           <div class="form-group" style="display:none">
                              <label for="customer">Hole Size</label>
                              <input name="hole_size" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['hole_size'] : ''; ?>"
                                 placeholder="Hole Size">
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 bolt_no_div" style="display: none">
                           <div class="form-group" style="display:none">
                              <label for="customer">Bolt No.</label>
                              <input name="bolt_no" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['bolt_no'] : ''; ?>"
                                 placeholder="Bolt No.">
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 height_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Height</label>
                              <input name="height" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['height'] : ''; ?>"
                                 placeholder="Bolt No.">
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 eve_part_no_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Everest Part<span class="required">*</span></label>
                              <input oninput="this.value = this.value.toUpperCase()" name="eve_part_no" type="text" class="input form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['eve_part_no'] : ''; ?>" placeholder="Everest Part No"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 model_display_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Model Display</label>
                              <input name="model_display" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['model_display'] : ''; ?>"
                                 placeholder="Model Display">
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 mrp_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">MRP</label>
                              <input name="mrp" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['mrp'] : ''; ?>"
                                 placeholder="MRP">
                           </div>
                        </div>
                        
                         
                        
                        <div class="col-md-4 remark_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Remark</label>
                              <input name="remark" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['remark'] : ''; ?>" placeholder="Remark"
                                 >
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 no_of_spline_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">No Of Spline</label>
                              <input name="no_of_spline" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['no_of_spline'] : ''; ?>" placeholder="No Of Spline"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 mating_part_no_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Mating Part No</label>
                              <input name="mating_part_no" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['mating_part_no'] : ''; ?>" placeholder="Mating Part No"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 child_parts_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Child Parts</label>
                              <input name="child_parts" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['child_parts'] : ''; ?>" placeholder="Child Parts"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 pipe_diameter_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Pipe Diameter</label>
                              <input name="pipe_diameter" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['pipe_diameter'] : ''; ?>" placeholder="Pipe Diameter"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Length</label>
                              <input name="length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['length'] : ''; ?>" placeholder="Length"
                                 >
                           </div>
                        </div>
                        
                         
                           
                        <div class="col-md-4 cup_size_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Cup Size</label>
                              <input name="cup_size" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['cup_size'] : ''; ?>" placeholder="Cup Size"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 crosslock_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Crosslock Length</label>
                              <input name="crosslock_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['crosslock_length'] : ''; ?>" placeholder="Crosslock Length"
                                 >
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 no_of_teeths_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">No Of Teeths</label>
                              <input name="no_of_teeths" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['no_of_teeths'] : ''; ?>" placeholder="No Of Teeths"
                                 >
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 idod_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Inner/Outter Diameter</label>
                              <input name="idod" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['idod'] : ''; ?>" placeholder="Inner/Outter Diameter"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 yoke_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Yoke Length</label>
                              <input name="yoke_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['yoke_length'] : ''; ?>" placeholder="Yoke Length"
                                 >
                           </div>
                        </div>
                        
                         
                           
                        <div class="col-md-4 teeth_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Teeth Length</label>
                              <input name="teeth_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['teeth_length'] : ''; ?>" placeholder="Teeth Length"
                                 >
                           </div>
                        </div>
                        
                         
                         
                        <div class="col-md-4 bearing_no_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Bearing No.</label>
                              <input name="bearing_no" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['bearing_no'] : ''; ?>" placeholder="Bearing No."
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 bearing_nodiameter_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Bearing Diameter</label>
                              <input name="bearing_nodiameter" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['bearing_nodiameter'] : ''; ?>" placeholder="Bearing Diameter"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 total_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Total Length</label>
                              <input name="total_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['total_length'] : ''; ?>" placeholder="Total Length"
                                 >
                           </div>
                        </div>
                        
                         
                        <div class="col-md-4 steeve_yoke_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Steeve Yoke Length</label>
                              <input name="steeve_yoke_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['steeve_yoke_length'] : ''; ?>" placeholder="Steeve Yoke Length"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 rear_teeth_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Rear Teeth Length</label>
                              <input name="rear_teeth_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['rear_teeth_length'] : ''; ?>" placeholder="Rear Teeth Length"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 half_yoke_length_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Half Yoke Length</label>
                              <input name="half_yoke_length" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['half_yoke_length'] : ''; ?>" placeholder="Half Yoke Length"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 application_div">
                           <div class="form-group">
                              <label for="customer">Application</label>
                              <input name="application" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['application'] : ''; ?>" placeholder="Application"
                                 >
                           </div>
                        </div>
                        
                         
                          
                        <div class="col-md-4 gst_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">GST</label>
                              <input name="gst" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['gst'] : ''; ?>" placeholder="GST"
                                 >
                           </div>
                        </div>
                        
                         
                           
                        <!-- <div class="col-md-4 everest_cross_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">Everest Cross</label>
                              <input name="everest_cross" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['everest_cross'] : ''; ?>" placeholder="Everest Cross"
                                 >
                           </div>
                        </div> -->
                        
                         
                           
                       <!--  <div class="col-md-4 no_of_holes_div">
                           <div class="form-group" style="display:none">
                              <label for="customer">No Of Holes</label>
                              <input name="no_of_holes" type="text" class="form-control" id="customer"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['no_of_holes'] : ''; ?>" placeholder="No Of Holes"
                                 >
                           </div>
                        </div> -->
                        
                       
                        <!-- <div class="col-md-4 oil_seal_div">
                           <div class="form-group" style="display:none">
                              <label for="email">Oil Seal<span class="required"></span></label>
                              <input name="oil_seal" type="text" class="form-control" id="email"
                                 placeholder="Oil Seal"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['oil_seal'] : ''; ?>">
                           </div>
                        </div>
                       -->


                        
                        <div class="col-md-4 equivalent_no_div">
                           <div class="form-group">
                              <label for="phone">Equivalent No.</label>
                              <input name="equivalent_no" type="text" pattern="[0-9]+" minlength="10" maxlength="10"
                                 class="form-control" id="phone" placeholder="Equivalent No."
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['equivalent_no'] : ''; ?>"
                                 >
                           </div>
                        </div>
                        
                        <div class="col-md-4 oe_competitor_no_div">
                           <div class="form-group" style="display:none">
                              <label for="address">OE Competitor No.<span class="required"></span></label>
                              <input name="oe_competitor_no" type="text" class="form-control" id="address"
                                 placeholder="OE Competitor No."
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['oe_competitor_no'] : ''; ?>">
                           </div>
                        </div>

                        <div class="col-md-4 coupon_div">
                           <div class="form-group" style="display:none">
                              <label for="address">Coupon<span class="required"></span></label>
                              <input name="coupon" type="text" class="form-control" id="address"
                                 placeholder="Coupon"
                                 value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['coupon'] : ''; ?>">
                           </div>
                        </div>
                        
                        <div class="form-group" style="display:none">
                           <div class="col-md-4">
                              <div class="form-group" style="display:none" >
                                 <label for="customer_image">Banner Image<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="profile_img" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-4">
                              <img src="<?php echo (isset($edit_product['profile_img']) && !empty($edit_product['profile_img'])) ? PROFILE_DISPLAY_PATH_NAME.$edit_product['profile_img'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                        </div>
                     </div>
                     <div class="text-center " style="margin-top: 400px;">
                        <input type="hidden" name="product_id"
                           value="<?php echo (isset($edit_product) && !empty($edit_product)) ? $edit_product['product_id'] : ''; ?>">
                        <a href="<?php echo ADMIN_PRODUCT_URL; ?>" class="btn btn-3d btn-red text-center">
                        <i class="fa fa-backward"></i> Back
                        </a>
                        <button type="reset" class="btn btn-3d btn-default text-center">
                        <i class="fa fa-redo"></i> Reset
                        </button>
                        <?php if(isset($edit_product)){?>
                        <button type="submit"  class="btn btn-3d btn-green text-center">
                        <i class="fa fa-save"></i> Submit
                        </button>
                        <?php }else {?>
                        <button type="submit" id="add" class="btn btn-3d btn-green text-center">
                        <i class="fa fa-save"></i> Submit
                        </button>
                        <?php } ?>
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

<script>
   function Categories() {
    var category_id = $('#category_id').val();
    var data = "category_id=" + category_id;
    $.ajax({
        type: "POST",
        url: "<?php echo ADMIN_CHANGE_PRODUCT_URL; ?>",
        data: data,
        cache: false,
        success: function(res_data) {
            // var result = res_data.split(',');
           
            var equivalent_flag = 0;
            // var oil_seal_flag = 0;
            // var cross_size_flag = 0;
            // var coupon_flag = 0;
            // var oe_competitor_no_flag = 0;
            // var no_of_holes_flag = 0;
            // var gst_flag = 0;
            var application_flag = 0;
            // var half_yoke_length_flag =0;
            // var rear_teeth_length_flag =0;
            // var steeve_yoke_length_flag=0;
            // var total_length_flag = 0;
            // var bearing_nodiameter_flag =0;
            // var bearing_no_flag = 0;
            // var teeth_length_flag =0;
            // var yoke_length_flag = 0;
            // var idod_flag = 0;
            // var no_of_teeths_flag = 0;
            // var crosslock_length_flag = 0;
            // var length_flag = 0;
            // var pipe_diameter_flag = 0;
            // var child_parts_flag = 0;
            // var mating_part_no_flag = 0;
            // var no_of_spline_flag = 0;
            // var remark_flag = 0;
            // var mrp_flag = 0;
            // var model_display_flag = 0;
            // var eve_part_no_flag = 0;
            // var height_flag = 0;
            for (var i = 0; i < result.length; i++)
            {
               // console.log(result[i]);
               // if(result[i] == 'gst')
               // {
               //     gst_flag = 1;
                  
               // }
               // if(result[i] == 'height')
               // {
               //     height_flag = 1;
                  
               // }
               // if(result[i] == 'eve_part')
               // {
               //     eve_part_no_flag = 1;
                  
               // }
               // if(result[i] == 'model_display')
               // {
               //     model_display_flag = 1;
                  
               // }

               // if(result[i] == 'mrp')
               // {
               //     mrp_flag = 1;
                  
               // }

               // if(result[i] == 'remark')
               // {
               //     remark_flag = 1;
                  
               // }
               // if(result[i] == 'no_of_spline')
               // {
               //     no_of_spline_flag = 1;
                  
               // }
               // if(result[i] == 'mating_part_no')
               // {
               //     mating_part_no_flag = 1;
                  
               // }
               // if(result[i] == 'child_parts')
               // {
               //     child_parts_flag = 1;
                  
               // }
               // if(result[i] == 'pipe_diameter')
               // {
               //     pipe_diameter_flag = 1;
                  
               // }
               // if(result[i] == 'length')
               // {
               //     length_flag = 1;
                  
               // }
               // if(result[i] == 'crosslock_length')
               // {
               //     crosslock_length_flag = 1;
                  
               // }
               // if(result[i] == 'no_of_teeths')
               // {
               //     no_of_teeths_flag = 1;
                  
               // }
               // if(result[i] == 'idod')
               // {
               //     idod_flag = 1;
                  
               // }
               // if(result[i] == 'yoke_length')
               // {
               //     yoke_length_flag = 1;
                  
               // }
               // if(result[i] == 'teeth_length')
               // {
               //     teeth_length_flag = 1;
                  
               // }
               // if(result[i] == 'bearing_no')
               // {
               //     bearing_no_flag = 1;
                  
               // }
               // if(result[i] == 'bearing_nodiameter')
               // {
               //     bearing_nodiameter_flag = 1;
                  
               // }
               // if(result[i] == 'total_length')
               // {
               //     total_length_flag = 1;
                  
               // }

               // if(result[i] == 'steeve_yoke_length')
               // {
               //     steeve_yoke_length_flag = 1;
                  
               // }

               // if(result[i] == 'rear_teeth')
               // {
               //     rear_teeth_length_flag = 1;
                  
               // }
               if(result[i] == 'application')
               {
                   application_flag = 1;
                  
               }
               if(result[i] == 'equivalent_no')
               {
                   equivalent_flag = 1;
                  
               }
               // if(result[i] == 'oil_seal')
               // {
               //     oil_seal_flag = 1;
               // }
               // if(result[i] == 'cross_size')
               // {
               //     cross_size_flag = 1;
               // }
               // if(result[i] == 'coupon')
               // {
               //     coupon_div_flag = 1;
               // }

               // if(result[i] == 'oe_competitor_no')
               // {
               //     oe_competitor_no_flag = 1;
               // }
               // if(result[i] == 'no_of_holes')
               // {
               //     no_of_holes_flag = 1;
               // }

            }

            //  if(height_flag != 1)
            // {
            //    $('.height_div').hide();
            // }
            // else
            // {
            //    $('.height_div').show();
               
            // }

            // if(eve_part_no_flag != 1)
            // {
            //    $('.eve_part_no_div').hide();
            // }
            // else
            // {
            //    $('.eve_part_no_div').show();
               
            // }

            // if(model_display_flag != 1)
            // {
            //    $('.model_display_div').hide();
            // }
            // else
            // {
            //    $('.model_display_div').show();
               
            // }

            // if(mrp_flag != 1)
            // {
            //    $('.mrp_div').hide();
            // }
            // else
            // {
            //    $('.mrp_div').show();
               
            // }

            // if(remark_flag != 1)
            // {
            //    $('.remark_div').hide();
            // }
            // else
            // {
            //    $('.remark_div').show();
               
            // }

            // if(no_of_spline_flag != 1)
            // {
            //    $('.no_of_spline_div').hide();
            // }
            // else
            // {
            //    $('.no_of_spline_div').show();
               
            // }

            // if(mating_part_no_flag != 1)
            // {
            //    $('.mating_part_no_div').hide();
            // }
            // else
            // {
            //    $('.mating_part_no_div').show();
               
            // }

            // if(child_parts_flag != 1)
            // {
            //    $('.child_parts_div').hide();
            // }
            // else
            // {
            //    $('.child_parts_div').show();
               
            // }

            // if(pipe_diameter_flag != 1)
            // {
            //    $('.pipe_diameter_div').hide();
            // }
            // else
            // {
            //    $('.pipe_diameter_div').show();
               
            // }

            // if(length_flag != 1)
            // {
            //    $('.length_div').hide();
            // }
            // else
            // {
            //    $('.length_div').show();
               
            // }

            // if(crosslock_length_flag != 1)
            // {
            //    $('.crosslock_length_div').hide();
            // }
            // else
            // {
            //    $('.crosslock_length_div').show();
               
            // }

            // if(no_of_teeths_flag != 1)
            // {
            //    $('.no_of_teeths_div').hide();
            // }
            // else
            // {
            //    $('.no_of_teeths_div').show();
               
            // }

            // if(idod_flag != 1)
            // {
            //    $('.idod_div').hide();
            // }
            // else
            // {
            //    $('.idod_div').show();
               
            // }

            // if(yoke_length_flag != 1)
            // {
            //    $('.yoke_length_div').hide();
            // }
            // else
            // {
            //    $('.yoke_length_div').show();
               
            // }

            //  if(teeth_length_flag != 1)
            // {
            //    $('.teeth_length_div').hide();
            // }
            // else
            // {
            //    $('.teeth_length_div').show();
               
            // }

            // if(bearing_no_flag != 1)
            // {
            //    $('.bearing_no_div').hide();
            // }
            // else
            // {
            //    $('.bearing_no_div').show();
               
            // }
            // if(bearing_nodiameter_flag != 1)
            // {
            //    $('.bearing_nodiameter_div').hide();
            // }
            // else
            // {
            //    $('.bearing_nodiameter_div').show();
               
            // }

            // if(total_length_flag != 1)
            // {
            //    $('.total_length_div').hide();
            // }
            // else
            // {
            //    $('.total_length_div').show();
               
            // }

            // if(steeve_yoke_length_flag != 1)
            // {
            //    $('.steeve_yoke_length_div').hide();
            // }
            // else
            // {
            //    $('.steeve_yoke_length_div').show();
               
            // }

            // if(rear_teeth_length_flag != 1)
            // {
            //    $('.rear_teeth_length_div').hide();
            // }
            // else
            // {
            //    $('.rear_teeth_length_div').show();
               
            // }

            if(application_flag != 1)
            {
               $('.application_div').hide();
            }
            else
            {
               $('.application_div').show();
               
            }

            // if(gst_flag != 1)
            // {
            //    $('.gst_div').hide();
            // }
            // else
            // {
            //    $('.gst_div').show();
               
            // }

            //  if(no_of_holes_flag != 1)
            // {
            //    $('.no_of_holes_flag_div').hide();
            // }
            // else
            // {
            //    $('.no_of_holes_flag_div').show();
               
            // }


            // if(oe_competitor_no_flag != 1)
            // {
            //    $('.oe_competitor_no_div').hide();
            // }
            // else
            // {
            //    $('.oe_competitor_no_div').show();
               
            // }


            // if(coupon_div_flag != 1)
            // {
            //    $('.coupon_div').hide();
            // }
            // else
            // {
            //    $('.coupon_div').show();
               
            // }


            if(equivalent_flag != 1)
            {
               $('.equivalent_no_div').hide();
            }
            else
            {
               $('.equivalent_no_div').show();
               
            }
         }
            // if(oil_seal_flag != 1)
            // {
            //    $('.oil_seal_div').hide();
            // }
            // else
            // {
            //    $('.oil_seal_div').show();

            // }
            // if(cross_size_flag != 1)
            // {
            //    $('.cross_size_div').hide();
            // }
            // else
            // {
            //    $('.cross_size_div').show();

            // }
        }
    });
}
</script>


<script type="text/javascript">
   $(document).ready(function() {   
     $('#add').click(function(event) {
       var article = $("#article").val();
       var model = $("#model").val();
       var series = $("#series").val();
       var categories = $("#type").val();
       if (article == null) {
         alert('Please Select Product Type');
         return false;
       }
       if (categories == null) {
         alert('Please Select categories');
         return false;
       }
       if (model == null) {
         alert('Please Select model');
         return false;
       }
       if (series == null) {
         alert('Please Select series');
         return false;
       }      
       });   
   });  
</script>
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