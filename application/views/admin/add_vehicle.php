<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Model List</a></li>
         <li class="active"> Add Vechicle</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD VECHICLE</strong>
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
               <form action="<?php echo ADMIN_SAVE_VECHICLE_URL; ?>" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Brand Name<span class="required">*</span></label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" name="vehicle_make_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['vehicle_make_name'] : ''; ?>" class="form-control" required placeholder="Brand Name">
                     </div>
                  </div>
 <div class="col-md-6">
                        <div class="form-group">
                           <label for="customer_loan_id">Display Sequence No.<span class="required">*</span></label>
                           <select name="vm_sequence" class="form-control" id="vm_sequence" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['vm_sequence'] : ''; ?>">
                              <option selected disabled><--Select Display Sequence No.--></option>
                              <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='1') ? 'selected' : ''; ?>>1</option>
                              <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='2') ? 'selected' : ''; ?>>2</option>
                              <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='3') ? 'selected' : ''; ?>>3</option>
                              <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='4') ? 'selected' : ''; ?>>4</option>
                              <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='5') ? 'selected' : ''; ?>>5</option>
                              <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='6') ? 'selected' : ''; ?>>6</option>
                              <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='7') ? 'selected' : ''; ?>>7</option>
                              <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='8') ? 'selected' : ''; ?>>8</option>
                              <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='9') ? 'selected' : ''; ?>>9</option>
                              <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='10') ? 'selected' : ''; ?>>10</option>
                              <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='11') ? 'selected' : ''; ?>>11</option>
                              <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='12') ? 'selected' : ''; ?>>12</option>
                              <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='13') ? 'selected' : ''; ?>>13</option>
                              <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='14') ? 'selected' : ''; ?>>14</option>
                              <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='15') ? 'selected' : ''; ?>>15</option>
                              <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='16') ? 'selected' : ''; ?>>16</option>
                              <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='17') ? 'selected' : ''; ?>>17</option>
                              <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='18') ? 'selected' : ''; ?>>18</option>
                              <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='19') ? 'selected' : ''; ?>>19</option>
                              <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='20') ? 'selected' : ''; ?>>20</option>
                              <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='21') ? 'selected' : ''; ?>>21</option>
                              <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='22') ? 'selected' : ''; ?>>22</option>
                              <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='23') ? 'selected' : ''; ?>>23</option>
                              <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='24') ? 'selected' : ''; ?>>24</option>
                              <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='25') ? 'selected' : ''; ?>>25</option>
         <option value="26" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='26') ? 'selected' : ''; ?>>26</option>
         <option value="27" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='27') ? 'selected' : ''; ?>>27</option>
         <option value="28" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='28') ? 'selected' : ''; ?>>28</option>
         <option value="29" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='29') ? 'selected' : ''; ?>>29</option>
         <option value="30" <?php echo (isset($edit) && !empty($edit) && $edit['vm_sequence']=='30') ? 'selected' : ''; ?>>30</option>
                           </select>
                        </div>
                     </div>
   <div class="form-group">
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label for="customer_image">Banner Image<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" 
                                 name="image" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-2">
                              <img src="<?php echo (isset($edit['image']) && !empty($edit['image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                  </div>
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="vm_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['vm_id'] : ''; ?>">
            <a href="<?php echo ADMIN_MODEL_URL; ?>" class="btn btn-3d btn-red text-center">
             <i class="fa fa-backward"></i> Back
             </a>
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
<strong>Vechicle LIST</strong>
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
<li>
</li>
</ul>
</div>
<div class="panel-body">
<div class="table-responsive">
<table class="table table-bordered table-hover text-center example" financer_name>
<thead>
<tr>
<th scope="col" class="text-center">#</th>
<th scope="col" class="text-center">Image</th>
<th scope="col" class="text-center">Brand Name</th>
<th scope="col" class="text-center">Sequence</th>
<th scope="col" class="text-center">Action</th>
</tr>
</thead>
<tbody>
<?php
   if (isset($vechicle) && !empty($vechicle)) {
        $i = 0;
   foreach ($vechicle as $key => $value ) {
       $i++;
   ?>
<tr>
<td><?php echo $i; ?></td>
<td><img class=" user-avatar" alt=""
                           src="<?php echo (isset($value['image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['image'] : BLANK_IMG; ?>" height="70px;"></td>
<td><?php echo $value['vehicle_make_name']; ?></td>
<td><?php echo $value['vm_sequence']; ?></td>
<td>
   <a href="<?php echo ADMIN_ADD_VEHICLE_URL . $value['vm_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
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