<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">User List</a></li>
         <li class="active">Product Details</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>PRODUCT DETAILS</strong>
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
                  <!-- <h6 class="mt-6 font-weight-semibold">Product Details</h6> -->
                  <table class="table table-striped table-bordered m-top20">
                     <tbody>
                        <?php if(isset($get_product['eve_part_no']) && !empty($get_product['eve_part_no'])){
                           ?>  
                        <tr>
                           <th scope="row">Everest Part Number</th>
                           <td><?php echo $get_product['eve_part_no']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>

                        <?php if(isset($get_product['cross_size']) && !empty($get_product['cross_size'])){
                           ?>  
                        <tr>
                           <th scope="row">Cross Size</th>
                           <td><?php echo $get_product['cross_size']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>

                        <?php if(isset($get_product['application']) && !empty($get_product['application'])){
                           ?>  
                        <tr>
                           <th scope="row">Application</th>
                           <td><?php echo $get_product['application']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['series']) && !empty($get_product['series'])){
                           ?>  
                        <tr>
                           <th scope="row">Series</th>
                           <td><?php echo $get_product['series']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['mrp']) && !empty($get_product['mrp'])){
                           ?>  
                        <tr>
                           <th scope="row">MRP</th>
                           <td><?php echo $get_product['mrp']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['length']) && !empty($get_product['length'])){
                           ?>  
                        <tr>
                           <th scope="row">Length</th>
                           <td><?php echo $get_product['length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['cup_size']) && !empty($get_product['cup_size'])){
                           ?>  
                        <tr>
                           <th scope="row">Cup Size</th>
                           <td><?php echo $get_product['cup_size']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['gst']) && !empty($get_product['gst'])){
                           ?>  
                        <tr>
                           <th scope="row">GST</th>
                           <td><?php echo $get_product['gst']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['coupon']) && !empty($get_product['coupon'])){
                           ?>  
                        <tr>
                           <th scope="row">Coupon</th>
                           <td><?php echo $get_product['coupon']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['plate_design']) && !empty($get_product['plate_design'])){
                           ?>  
                        <tr>
                           <th scope="row">Plate Design</th>
                           <td><?php echo $get_product['plate_design']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['plate_diameter']) && !empty($get_product['plate_diameter'])){
                           ?>  
                        <tr>
                           <th scope="row">Plate Diameter</th>
                           <td><?php echo $get_product['plate_diameter']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['hole_size']) && !empty($get_product['hole_size'])){
                           ?>  
                        <tr>
                           <th scope="row">Hole Size</th>
                           <td><?php echo $get_product['hole_size']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['height']) && !empty($get_product['height'])){
                           ?>  
                        <tr>
                           <th scope="row">Height</th>
                           <td><?php echo $get_product['height']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['model_display']) && !empty($get_product['model_display'])){
                           ?>  
                        <tr>
                           <th scope="row">Model Display</th>
                           <td><?php echo $get_product['model_display']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['stock']) && !empty($get_product['stock'])){
                           ?>  
                        <tr>
                           <th scope="row">Stock</th>
                           <td><?php echo $get_product['stock']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['landing_price']) && !empty($get_product['landing_price'])){
                           ?>  
                        <tr>
                           <th scope="row">Landing Price</th>
                           <td><?php echo $get_product['landing_price']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['remark']) && !empty($get_product['remark'])){
                           ?>  
                        <tr>
                           <th scope="row">Remark</th>
                           <td><?php echo $get_product['remark']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['no_of_spline']) && !empty($get_product['no_of_spline'])){
                           ?>  
                        <tr>
                           <th scope="row">No. Of Spline</th>
                           <td><?php echo $get_product['no_of_spline']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['mating_part_no']) && !empty($get_product['mating_part_no'])){
                           ?>  
                        <tr>
                           <th scope="row">Malting Part Number</th>
                           <td><?php echo $get_product['mating_part_no']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['child_parts']) && !empty($get_product['child_parts'])){
                           ?>  
                        <tr>
                           <th scope="row">Childs Parts</th>
                           <td><?php echo $get_product['child_parts']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['discounted_price']) && !empty($get_product['discounted_price'])){
                           ?>  
                        <tr>
                           <th scope="row">Discounted Price</th>
                           <td><?php echo $get_product['discounted_price']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['pipe_diameter']) && !empty($get_product['pipe_diameter'])){
                           ?>  
                        <tr>
                           <th scope="row">Pipe Diameter</th>
                           <td><?php echo $get_product['pipe_diameter']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['crosslock_length']) && !empty($get_product['crosslock_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Cross Lock Length</th>
                           <td><?php echo $get_product['crosslock_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['description']) && !empty($get_product['description'])){
                           ?>  
                        <tr>
                           <th scope="row">Description</th>
                           <td><?php echo $get_product['description']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['no_of_teeths']) && !empty($get_product['no_of_teeths'])){
                           ?>  
                        <tr>
                           <th scope="row">No. Of Teeth</th>
                           <td><?php echo $get_product['no_of_teeths']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['idod']) && !empty($get_product['idod'])){
                           ?>  
                        <tr>
                           <th scope="row">IDOD</th>
                           <td><?php echo $get_product['idod']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['yoke_length']) && !empty($get_product['yoke_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Yoke Length</th>
                           <td><?php echo $get_product['yoke_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['teeth_length']) && !empty($get_product['teeth_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Teeth Length</th>
                           <td><?php echo $get_product['teeth_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['bearing_no']) && !empty($get_product['bearing_no'])){
                           ?>  
                        <tr>
                           <th scope="row">Bearing Number</th>
                           <td><?php echo $get_product['bearing_no']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['type']) && !empty($get_product['type'])){
                           ?>  
                        <tr>
                           <th scope="row">Type</th>
                           <td><?php echo $get_product['type']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['total_length']) && !empty($get_product['total_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Total Length</th>
                           <td><?php echo $get_product['total_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['steeve_yoke_length']) && !empty($get_product['steeve_yoke_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Steeve Yoke Length</th>
                           <td><?php echo $get_product['steeve_yoke_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['rear_teeth_length']) && !empty($get_product['rear_teeth_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Rear Teeth Length</th>
                           <td><?php echo $get_product['rear_teeth_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['bearing_nodiameter']) && !empty($get_product['bearing_nodiameter'])){
                           ?>  
                        <tr>
                           <th scope="row">Bearing Number Diameter</th>
                           <td><?php echo $get_product['bearing_nodiameter']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['half_yoke_length']) && !empty($get_product['half_yoke_length'])){
                           ?>  
                        <tr>
                           <th scope="row">Half Yoke Length</th>
                           <td><?php echo $get_product['half_yoke_length']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['everest_cross']) && !empty($get_product['everest_cross'])){
                           ?>  
                        <tr>
                           <th scope="row">Everest Cross</th>
                           <td><?php echo $get_product['everest_cross']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['no_of_holes']) && !empty($get_product['no_of_holes'])){
                           ?>  
                        <tr>
                           <th scope="row">Number Of Holes</th>
                           <td><?php echo $get_product['no_of_holes']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['oil_seal']) && !empty($get_product['oil_seal'])){
                           ?>  
                        <tr>
                           <th scope="row">Oil Seal</th>
                           <td><?php echo $get_product['oil_seal']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>
                        <?php if(isset($get_product['equivalent_no']) && !empty($get_product['equivalent_no'])){
                           ?>  
                        <tr>
                           <th scope="row">Equivalent Number</th>
                           <td><?php echo $get_product['equivalent_no']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>  
                        <?php if(isset($get_product['oe_competitor_no']) && !empty($get_product['oe_competitor_no'])){
                           ?>  
                        <tr>
                           <th scope="row">OE Competitor Number</th>
                           <td><?php echo $get_product['oe_competitor_no']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?>  
                        <?php if(isset($get_product['gst_amount']) && !empty($get_product['gst_amount'])){
                           ?>  
                        <tr>
                           <th scope="row">GST Ammount</th>
                           <td><?php echo $get_product['gst_amount']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?> 
                        <?php if(isset($get_product['base_amount']) && !empty($get_product['base_amount'])){
                           ?>  
                        <tr>
                           <th scope="row">Base Ammount</th>
                           <td><?php echo $get_product['base_amount']; ?></td>
                        </tr>
                        <?php }else{ echo''; } ?> 
                        <tr>
                           <th scope="row">Created Date</th>
                           <td><?php echo $get_product['created_date']; ?></td>
                        </tr>
                     </tbody>
                  </table>
                  <a href="<?php echo ADMIN_PRODUCT_URL; ?>" class="btn btn-warning w-100" style="width:100%;">Cancel</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php $this->load->view('admin/footer'); ?>
