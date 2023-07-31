<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Parameter Search</h1>
      <ol class="breadcrumb">
         <li><a href="#">Parameter Search</a></li>
         <li class="active">Parameter List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD PARAMETER</strong>
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
            <form action="<?php echo ADMIN_SAVE_PARAMETER_URL; ?>" method="POST" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Category</label>
                        <select name="category_id" class="select2 form-control" id="category_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_id'] : ''; ?>">
                           <option selected disabled><--Select Category--></option>
                           <?php if(isset($category) && !empty($category)){
                              foreach ($category as $key => $value) {
                                  ?>
                           <option value="<?php echo $value['category_id']; ?>"<?php echo (isset($edit) && !empty($edit) && $edit['category_id']==$value['category_id']) ? 'selected' : ''; ?> >
                              <?php echo $value['category_name']; ?>
                           </option>
                           <?php }
                              } ?>          
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Parameter Name</label>
                        <input type="text" name="parameter_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['parameter_name'] : ''; ?>" required placeholder="Parameter Name">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Parameter Order</label>
                        <select name="parameter_order" class="form-control" id="parameter_order" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['parameter_order'] : ''; ?>">
                           <option selected disabled><--Select Parameter Order--></option>
                           <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='1') ? 'selected' : ''; ?>>1</option>
                           <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='2') ? 'selected' : ''; ?>>2</option>
                           <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='3') ? 'selected' : ''; ?>>3</option>
                           <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='4') ? 'selected' : ''; ?>>4</option>
                           <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='5') ? 'selected' : ''; ?>>5</option>
                           <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='6') ? 'selected' : ''; ?>>6</option>
                           <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='7') ? 'selected' : ''; ?>>7</option>
                           <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='8') ? 'selected' : ''; ?>>8</option>
                           <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='9') ? 'selected' : ''; ?>>9</option>
                           <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='10') ? 'selected' : ''; ?>>10</option>
                           <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='11') ? 'selected' : ''; ?>>11</option>
                           <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='12') ? 'selected' : ''; ?>>12</option>
                           <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='13') ? 'selected' : ''; ?>>13</option>
                           <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='14') ? 'selected' : ''; ?>>14</option>
                           <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['parameter_order']=='15') ? 'selected' : ''; ?>>15</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-4">
                        <label for="customer_loan_id">Advance Search</label>
                        <select name="advance_flag" class="form-control" id="advance_flag" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['advance_flag'] : ''; ?>">
                            <option selected disabled><--Select Flag--></option>
                           <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['advance_flag']=='Yes') ? 'selected' : ''; ?>>Yes</option>
                           <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['advance_flag']=='No') ? 'selected' : ''; ?>>No</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Database Fields</label>
                        <select name="related" class="select2 form-control" id="parameter_order" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['related'] : ''; ?>">
                           <option selected disabled><--Select Database Related Name--></option>
                           <option value="series_search" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='series_search') ? 'selected' : ''; ?>>series_search</option>
                           <option value="cross_size" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='cross_size') ? 'selected' : ''; ?>>cross_size</option>
                           <option value="plate_design" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='plate_design') ? 'selected' : ''; ?>>plate_design</option>
                           <option value="plate_diameter" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='plate_diameter') ? 'selected' : ''; ?>>plate_diameter</option>
                           <option value="hole_size" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='hole_size') ? 'selected' : ''; ?>>hole_size</option>
                           <option value="bolt_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='bolt_no') ? 'selected' : ''; ?>>bolt_no</option>
                           <option value="height" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='height') ? 'selected' : ''; ?>>height</option>
                           <option value="eve_part_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='eve_part_no') ? 'selected' : ''; ?>>eve_part_no</option>
                           <option value="model_display" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='model_display') ? 'selected' : ''; ?>>model_display</option>
                           <option value="stock" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='stock') ? 'selected' : ''; ?>>stock</option>
                           <option value="mrp" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='mrp') ? 'selected' : ''; ?>>mrp</option>
                           <!-- <option value="landing_price" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='landing_price') ? 'selected' : ''; ?>>landing_price</option> -->
                           <option value="remark" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='remark') ? 'selected' : ''; ?>>remark</option>
                           <option value="no_of_spline" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='no_of_spline') ? 'selected' : ''; ?>>no_of_spline</option>
                           <option value="mating_part_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='mating_part_no') ? 'selected' : ''; ?>>mating_part_no</option>
                           <option value="child_parts" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='child_parts') ? 'selected' : ''; ?>>child_parts</option>
                           <option value="discounted_price" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='discounted_price') ? 'selected' : ''; ?>>discounted_price</option>
                           <option value="pipe_diameter" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='pipe_diameter') ? 'selected' : ''; ?>>pipe_diameter</option>
                           <option value="length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='length') ? 'selected' : ''; ?>>length</option>
                           <option value="cup_size" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='cup_size') ? 'selected' : ''; ?>>cup_size</option>
                           <option value="crosslock_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='crosslock_length') ? 'selected' : ''; ?>>crosslock_length</option>
                           <option value="description" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='description') ? 'selected' : ''; ?>>description</option>
                           <option value="no_of_teeths" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='no_of_teeths') ? 'selected' : ''; ?>>no_of_teeths</option>
                           <option value="idod" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='idod') ? 'selected' : ''; ?>>idod</option>
                           <option value="yoke_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='yoke_length') ? 'selected' : ''; ?>>yoke_length</option>
                           <option value="teeth_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='teeth_length') ? 'selected' : ''; ?>>teeth_length</option>
                           <option value="bearing_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='bearing_no') ? 'selected' : ''; ?>>bearing_no</option>
                           <option value="type" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='type') ? 'selected' : ''; ?>>type</option>
                           <option value="total_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='total_length') ? 'selected' : ''; ?>>total_length</option>
                           <option value="steeve_yoke_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='steeve_yoke_length') ? 'selected' : ''; ?>>steeve_yoke_length</option>
                           <option value="rear_teeth_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='rear_teeth_length') ? 'selected' : ''; ?>>rear_teeth_length</option>
                           <option value="bearing_nodiameter" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='bearing_nodiameter') ? 'selected' : ''; ?>>bearing_nodiameter</option>
                           <option value="half_yoke_length" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='half_yoke_length') ? 'selected' : ''; ?>>half_yoke_length</option>
                           <option value="application" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='application') ? 'selected' : ''; ?>>application</option>
                           <option value="gst" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='gst') ? 'selected' : ''; ?>>gst</option>
                           <option value="everest_cross" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='everest_cross') ? 'selected' : ''; ?>>everest_cross</option>
                           <option value="no_of_holes" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='no_of_holes') ? 'selected' : ''; ?>>no_of_holes</option>
                           <option value="oil_seal" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='oil_seal') ? 'selected' : ''; ?>>oil_seal</option>
                           <option value="equivalent_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='equivalent_no') ? 'selected' : ''; ?>>equivalent_no</option>
                           <option value="oe_competitor_no" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='oe_competitor_no') ? 'selected' : ''; ?>>oe_competitor_no</option>
                           <option value="coupon" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='coupon') ? 'selected' : ''; ?>>coupon</option>
                           <option value="gst_amount" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='gst_amount') ? 'selected' : ''; ?>>gst_amount</option>
                           <option value="base_amount" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='base_amount') ? 'selected' : ''; ?>>base_amount</option>
                           <option value="profile_img" <?php echo (isset($edit) && !empty($edit) && $edit['related']=='profile_img') ? 'selected' : ''; ?>>profile_img</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-4">
                        <label for="customer_loan_id">Search By Dimension</label>
                        <select name="search_dimension" class="form-control" id="search_dimension" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['search_dimension'] : ''; ?>">
                            <option selected disabled><--Search By Dimension--></option>
                           <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['search_dimension']=='Yes') ? 'selected' : ''; ?>>Yes</option>
                           <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['search_dimension']=='No') ? 'selected' : ''; ?>>No</option>
                        </select>
                     </div>
                  </div>
                  
               </div>
               <div class="form-group">
                     <div class="col-md-4" style="margin-top: -20px">
                        <label for="customer_loan_id">Display </label>
                        <select name="display" class="form-control" id="display" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['display'] : ''; ?>">
                            <option selected disabled><--Select Display--></option>
                           <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['display']=='Yes') ? 'selected' : ''; ?>>Yes</option>
                           <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['display']=='No') ? 'selected' : ''; ?>>No</option>
                        </select>
                     </div>
                  </div>

                
                  <div class="row"> 
                  <div class="form-group">
                     <div class="col-md-4" style="margin-top: -10px">
                     <label for="customer_loan_id">Display Order</label>
                        <select name="display_order" class="form-control" id="display_details" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['display_details'] : ''; ?>">
                            <option selected disabled><--Select Display Order--></option>
                              <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='1') ? 'selected' : ''; ?>>1</option>
                              <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='2') ? 'selected' : ''; ?>>2</option>
                              <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='3') ? 'selected' : ''; ?>>3</option>
                              <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='4') ? 'selected' : ''; ?>>4</option>
                              <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='5') ? 'selected' : ''; ?>>5</option>
                              <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='6') ? 'selected' : ''; ?>>6</option>
                              <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='7') ? 'selected' : ''; ?>>7</option>
                              <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='8') ? 'selected' : ''; ?>>8</option>
                              <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='9') ? 'selected' : ''; ?>>9</option>
                              <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='10') ? 'selected' : ''; ?>>10</option>
                              <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='11') ? 'selected' : ''; ?>>11</option>
                              <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='12') ? 'selected' : ''; ?>>12</option>
                              <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='13') ? 'selected' : ''; ?>>13</option>
                              <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='14') ? 'selected' : ''; ?>>14</option>
                              <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='15') ? 'selected' : ''; ?>>15</option>
                              <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='16') ? 'selected' : ''; ?>>16</option>
                              <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='17') ? 'selected' : ''; ?>>17</option>
                              <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='18') ? 'selected' : ''; ?>>18</option>
                              <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='19') ? 'selected' : ''; ?>>19</option>
                              <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='20') ? 'selected' : ''; ?>>20</option>
                              <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='21') ? 'selected' : ''; ?>>21</option>
                              <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='22') ? 'selected' : ''; ?>>22</option>
                              <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='23') ? 'selected' : ''; ?>>23</option>
                              <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='24') ? 'selected' : ''; ?>>24</option>
                              <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['display_order']=='25') ? 'selected' : ''; ?>>25</option>
                        </select>
                  </div>
                     
                  </div>
           
               </div>   
               <div class="row">
                  <div class="col-md-4" style="margin-top: -20px">
                        <label for="customer_loan_id">Display Details</label>
                        <select name="display_details" class="form-control" id="display_details" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['display_details'] : ''; ?>">
                            <option selected disabled><--Display Details--></option>
                           <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['display_details']=='Yes') ? 'selected' : ''; ?>>Yes</option>
                           <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['display_details']=='No') ? 'selected' : ''; ?>>No</option>
                        </select>
                     </div>
                  <div class="col-md-4" style="margin-top: -10px">
                     <label for="customer_loan_id">Display Details Order</label>
                        <select name="display_details_order" class="form-control" id="display_details" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['display_details'] : ''; ?>">
                            <option selected disabled><--Select Display Details Order--></option>
                              <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='1') ? 'selected' : ''; ?>>1</option>
                              <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='2') ? 'selected' : ''; ?>>2</option>
                              <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='3') ? 'selected' : ''; ?>>3</option>
                              <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='4') ? 'selected' : ''; ?>>4</option>
                              <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='5') ? 'selected' : ''; ?>>5</option>
                              <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='6') ? 'selected' : ''; ?>>6</option>
                              <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='7') ? 'selected' : ''; ?>>7</option>
                              <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='8') ? 'selected' : ''; ?>>8</option>
                              <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='9') ? 'selected' : ''; ?>>9</option>
                              <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='10') ? 'selected' : ''; ?>>10</option>
                              <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='11') ? 'selected' : ''; ?>>11</option>
                              <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='12') ? 'selected' : ''; ?>>12</option>
                              <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='13') ? 'selected' : ''; ?>>13</option>
                              <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='14') ? 'selected' : ''; ?>>14</option>
                              <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='15') ? 'selected' : ''; ?>>15</option>
                              <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='16') ? 'selected' : ''; ?>>16</option>
                              <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='17') ? 'selected' : ''; ?>>17</option>
                              <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='18') ? 'selected' : ''; ?>>18</option>
                              <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='19') ? 'selected' : ''; ?>>19</option>
                              <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='20') ? 'selected' : ''; ?>>20</option>
                              <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='21') ? 'selected' : ''; ?>>21</option>
                              <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='22') ? 'selected' : ''; ?>>22</option>
                              <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='23') ? 'selected' : ''; ?>>23</option>
                              <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='24') ? 'selected' : ''; ?>>24</option>
                              <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['display_details_order']=='25') ? 'selected' : ''; ?>>25</option>
                        </select>
                  </div>
                  
               </div>

               <div class="text-center">
                  <input type="hidden" name="id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['id'] : ''; ?>">  
                  <button type="reset" class="btn btn-3d btn-default text-center"><i
                     class="fa fa-redo"></i>
                  Reset</button>
                  <button type="submit" class="btn btn-3d btn-green text-center"><i
                     class="fa fa-save"></i>
                  Submit</button>
               </div>
            </form>
         </div>
      </div>
</section>
<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Parameter List</strong>
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
            <div class="table-responsive">
               <table class="table table-bordered table-hover text-center example" financer_name>
                  <thead>
                     <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Category Name</th>
                        <th scope="col" class="text-center">Parameter Name</th>

                         <th scope="col" class="text-center">Search Dimension</th> 
                        <th scope="col" class="text-center">Parameter Order</th>

                         <th scope="col" class="text-center">Display</th>
                        <th scope="col" class="text-center">Display Order</th>

                         <th scope="col" class="text-center">Display Details</th>
                        <th scope="col" class="text-center">Display Details Order</th>

                        <th scope="col" class="text-center">Database Fields</th>                      
                        <th scope="col" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (isset($list) && !empty($list)) {
                             $i = 0;
                        foreach ($list as $key => $value ) {
                            $i++;
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $value['category_name']; ?></td>
                        <td><?php echo $value['parameter_name']; ?></td>
                        <td><?php echo $value['search_dimension']; ?></td>
                        <td><?php echo $value['parameter_order']; ?></td>
                        <td><?php echo $value['display']; ?></td>
                        <td><?php echo $value['display_order']; ?></td>
                        <td><?php echo $value['display_details']; ?></td>
                        <td><?php echo $value['display_details_order']; ?></td>
                        <td><?php echo $value['related'] ?></td>
                       
                        <td>
                           <a href="<?php echo ADMIN_PARAMETER_URL . $value['id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                              style="padding-right:0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_PARAMETER_URL.$value['id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger" title="delete"><i class="fa fa-trash"
                              style="padding-right:0;"></i></a>
                        </td>
                     </tr>
                     <?php }}  ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</section>
</div>
<?php $this->load->view('admin/footer'); ?>