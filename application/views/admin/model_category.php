

<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i>MODEL CATEGORIES MANAGEMENT</h1>
      <ol class="breadcrumb">
         <li><a href="#">Model_Category</a></li>
         <li class="active"> Model Category List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>add MODEL</strong>
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
               <form action="<?php echo CATEGORY_MODEL_SAVE_URL; ?>" method="POST" enctype="multipart/form-data">   
                  <div class="col-md-6">
                           <div class="form-group">
                              <label for="city">Vechicle Brand<span class="required"></span></label>
                              <select name="fk_vm_category_id" class="select2 form-control" id="type">
                                 <option selected disabled><--Select Vechicle Brand--></option>
                                 <?php if(isset($v_brand) && !empty($v_brand)){
                                    foreach ($v_brand as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['vm_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['vm_id']=$value['vm_id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['vehicle_make_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>
                        </div>
                    <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Modal Category Name<span class="required">*</span></label>
                        <input type="text" name="model_category_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_category_name'] : ''; ?>" class="form-control" required placeholder="Model Name">
                     </div>
                  </div>
              
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Order</label>
                        <select name="category_sequence" class="form-control" id="category_sequence" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_sequence'] : ''; ?>">
                           <option selected disabled><--Sequence Order--></option>
                           <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='1') ? 'selected' : ''; ?>>1</option>
                           <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='2') ? 'selected' : ''; ?>>2</option>
                           <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='3') ? 'selected' : ''; ?>>3</option>
                           <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='4') ? 'selected' : ''; ?>>4</option>
                           <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='5') ? 'selected' : ''; ?>>5</option>
                           <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='6') ? 'selected' : ''; ?>>6</option>
                           <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='7') ? 'selected' : ''; ?>>7</option>
                           <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='8') ? 'selected' : ''; ?>>8</option>
                           <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='9') ? 'selected' : ''; ?>>9</option>
                           <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='10') ? 'selected' : ''; ?>>10</option>
                           <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='11') ? 'selected' : ''; ?>>11</option>
                           <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='12') ? 'selected' : ''; ?>>12</option>
                           <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='13') ? 'selected' : ''; ?>>13</option>
                           <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='14') ? 'selected' : ''; ?>>14</option>
                           <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['category_sequence']=='15') ? 'selected' : ''; ?>>15</option>
                        </select>
                     </div>
                  </div>
              </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="category_model_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_model_id'] : ''; ?>">
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
               <strong>Model LIST</strong>
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
               <li></li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="table-responsive">
               <table class="table table-bordered table-hover text-center example" financer_name>
                  <thead>
                     <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Vechicle Brand</th>
                        <th scope="col" class="text-center">Vechicle Type</th>
                        <th scope="col" class="text-center">Order</th>
 <td scope="col" class="text-center">Status</td>
                                              <th scope="col" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    
                     <?php
                     // print_r($details);
                        if (isset($get_data) && !empty($get_data)) {
                             $i = 0;
                        foreach ($get_data as $key => $value ) {
                            $i++;                                              

                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                       
                        <td><?php echo $value['vehicle_make_name']; ?></td>
                        <td><?php echo $value['model_category_name']; ?></td>
                        <td><?php echo $value['category_sequence']; ?></td>

 <td class="text-center">
                                             <?php if($value['status']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                        </td>
                        <td>


                           <a href="<?php echo CATEGORY_MODEL_URL . $value['category_model_id']; ?>" 
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo CATEGORY_MODEL_DELETE_URL.$value['category_model_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;" title="Delete"></i></a>


 <?php if($value['status']==0)
                                                    {?>


                                                      <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_model_id']; ?>','1','<?php echo CATEGORY_MODEL_STATUS_URL ?>');"
                                        class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                                <?php } else
                                                    {?>
                                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_model_id']; ?>','0','<?php echo CATEGORY_MODEL_STATUS_URL ?>');"
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
                     function statusChange(id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo CATEGORY_MODEL_STATUS_URL; ?>",
                          data: {id:id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>