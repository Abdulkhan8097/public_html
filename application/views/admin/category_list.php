<?php $this->load->view('admin/header'); ?>
<head></head>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Category</a></li>
         <li class="active">Category List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>CATEGORY LIST</strong>
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
                  <form action="<?php echo ADMIN_SAVE_CATEGORY_URL; ?>" method="POST" enctype="multipart/form-data">
                     <div class="form-group">
                        <div class="col-md-4">
                           <label for="category_name">Category Name<span class="required">*</span></label>
                           <input type="text" name="category_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_name'] : ''; ?>" required placeholder="Category Name">                             
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-md-4">
                           <label for="category_name">Remark</label>
                           <!-- <input type="text" name="remark"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['remark'] : ''; ?>" required placeholder="Remark">  -->
                           <textarea name="remark"  class="form-control" placeholder="Reamrk"><?php echo (isset($edit) && !empty($edit)) ? $edit['remark'] : ''; ?></textarea>                            
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label for="customer_loan_id">Display Sequence No.<span class="required">*</span></label>
                           <select name="sequence_no" class="form-control" id="sequence_no" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['sequence_no'] : ''; ?>">
                              <option selected disabled><--Select Display Sequence No.--></option>
                              <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='1') ? 'selected' : ''; ?>>1</option>
                              <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='2') ? 'selected' : ''; ?>>2</option>
                              <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='3') ? 'selected' : ''; ?>>3</option>
                              <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='4') ? 'selected' : ''; ?>>4</option>
                              <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='5') ? 'selected' : ''; ?>>5</option>
                              <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='6') ? 'selected' : ''; ?>>6</option>
                              <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='7') ? 'selected' : ''; ?>>7</option>
                              <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='8') ? 'selected' : ''; ?>>8</option>
                              <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='9') ? 'selected' : ''; ?>>9</option>
                              <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='10') ? 'selected' : ''; ?>>10</option>
                              <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='11') ? 'selected' : ''; ?>>11</option>
                              <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='12') ? 'selected' : ''; ?>>12</option>
                              <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='13') ? 'selected' : ''; ?>>13</option>
                              <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='14') ? 'selected' : ''; ?>>14</option>
                              <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='15') ? 'selected' : ''; ?>>15</option>
                              <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='16') ? 'selected' : ''; ?>>16</option>
                              <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='17') ? 'selected' : ''; ?>>17</option>
                              <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='18') ? 'selected' : ''; ?>>18</option>
                              <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='19') ? 'selected' : ''; ?>>19</option>
                              <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='20') ? 'selected' : ''; ?>>20</option>
                              <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='21') ? 'selected' : ''; ?>>21</option>
                              <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='22') ? 'selected' : ''; ?>>22</option>
                              <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='23') ? 'selected' : ''; ?>>23</option>
                              <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='24') ? 'selected' : ''; ?>>24</option>
                              <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='25') ? 'selected' : ''; ?>>25</option>
			<option value="26" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='26') ? 'selected' : ''; ?>>26</option>
			<option value="27" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='27') ? 'selected' : ''; ?>>27</option>
			<option value="28" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='28') ? 'selected' : ''; ?>>28</option>
			<option value="29" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='29') ? 'selected' : ''; ?>>29</option>
			<option value="30" <?php echo (isset($edit) && !empty($edit) && $edit['sequence_no']=='30') ? 'selected' : ''; ?>>30</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-md-5">
                           <div class="form-group" >
                              <label for="customer_image">Category Image<span class="required"></span></label>
                              <input type="file" onchange="display_img(this);" class="form-control" 
                              name="category_image" id="customer_image" >
                           </div>
                        </div>
                        <div class="col-md-2">
                           <img src="<?php echo (isset($edit['category_image']) && !empty($edit['category_image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['category_image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-4">
                           <label for="category_name">Discount<span class="required">*</span></label>
                           <input type="text" name="discount"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['discount'] : ''; ?>" required placeholder="Discount">                             
                        </div>
                     </div>
               </div>
               <div class="row" style="margin-top: 22%;">
               <div class="text-center">
               <input type="hidden" name="category_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_id'] : ''; ?>">  
               <button type="reset" class="btn btn-3d btn-default text-center"><i
                  class="fa fa-redo"></i>
               Reset</button>
               <button type="submit" class="btn btn-3d btn-green text-center"><i
                  class="fa fa-save"></i>
               Submit</button>
               </div>
               </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Category List</strong>
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
                        <th scope="col" class="text-center">Category Image</th>
                        <th scope="col" class="text-center">Category Name</th>
                         <th scope="col" class="text-center">Remark</th>
                        <th scope="col" class="text-center">Sequence No.</th>
                        <th scope="col" class="text-center">Discount</th>
                        <td scope="col" class="text-center">Status</td>
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
                        <td><img class=" user-avatar" alt=""
                           src="<?php echo (isset($value['category_image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['category_image'] : BLANK_IMG; ?>" height="70px;"></td>
                        <td><?php echo $value['category_name']; ?></td>
                        <td><?php echo $value['remark_category']; ?></td>
                        <td><?php echo $value['sequence_no']; ?></td>
                        <td><?php echo $value['discount']; ?></td>
                        <td class="text-center">
                                             <?php if($value['status']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                            </td>
                        <td>
                           <a href="<?php echo ADMIN_CATEGORY_URL . $value['category_id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                              style="padding-right: 0;"></i></a>
                          <!--  <a href="<?php echo ADMIN_DELETE_CATEGORY_URL.$value['category_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-info " id="first"><i class="fa fa-trash"
                              style="padding-right: 0;"></i></a> -->
                           <a href="<?php echo ADMIN_DETAILS_CATEGORY_URL.$value['category_id']; ?>"
                              class="btn btn-warning " title="Parameter List" id="first"><i class="fas fa-eye" style="padding-right: 0;"></i></a>

                              <a href="<?php echo ADMIN_DETAILS_PRODUCT_URL.$value['category_id']; ?>"
                              class="btn btn-info" title="Product List" id="first"><i class="fas fa-eye" style="padding-right: 0;"></i></a>

                                            <?php if($value['status']==0)
                                                    {?>


                                                      <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_id']; ?>','1','<?php echo CATEGORY_STATUS_URL ?>');"
                                        class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                                <?php } else
                                                    {?>
                                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_id']; ?>','0','<?php echo CATEGORY_STATUS_URL ?>');"
                                        class="btn btn-danger" title="Inactive"><i class="fas fa-toggle-off"  style="padding-right: 0;"></i></a>
                                                <?php } ?> 
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
<script type="text/javascript">
   function display_img(input) {
     if (input.files && input.files[0]) {
       var reader = new FileReader()
       reader.onload  =function (e) {
         $('#display_image_here')
           .attr('src', e.target.result)
           .width(100)
           .height(100)
       }
       reader.readAsDataURL(input.files[0])
     }
   }
</script>
<!-- status change script -->
<script type="text/javascript">
                     function statusChange(category_id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo CATEGORY_STATUS_URL; ?>",
                          data: {category_id:category_id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>
<!-- status change script end -->
