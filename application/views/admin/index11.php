<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Model</a></li>
         <li class="active"> Model List</li>
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
               <li>
                  <a href="<?php echo ADMIN_ADD_VEHICLE_URL; ?>" class="btn btn-primary btn-sm ">
                      <i class="fa fa-plus"></i>Add Vehicle</a>
                </li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="row">
               <form action="<?php echo ADMIN_SAVE_MODAL_URL; ?>" method="POST" enctype="multipart/form-data">   <div class="col-md-6">
                           <div class="form-group">
                              <label for="city">Vechicle Brand<span class="required"></span></label>
                              <select name="vechicle_make" class="select2 form-control" id="type">
                                 <option selected disabled>--Select Vechicle Brand--</option>
                                 <?php if(isset($vechicle) && !empty($vechicle)){
                                    foreach ($vechicle as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['vm_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['vm_id']==$value['vm_id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['vehicle_make_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>
                        </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Model Name<span class="required">*</span></label>
                        <input type="text" name="model_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_name'] : ''; ?>" class="form-control" required placeholder="Model Name">
                     </div>
                  </div>
                  <div class="form-group">
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label for="customer_image">Model Image<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="image" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-6">
                              <img src="<?php echo (isset($edit['image']) && !empty($edit['image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                  </div>
              </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="model_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_id'] : ''; ?>">
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
                        <th scope="col" class="text-center">image</th>
                        <th scope="col" class="text-center">Vechicle Brand</th>
                        <th scope="col" class="text-center">Model Name</th>
                        <!--  <th scope="col" class="text-center">User Mobile</th> -->
                        <th scope="col" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    
                     <?php
                     // print_r($details);
                        if (isset($list) && !empty($list)) {
                             $i = 0;
                        foreach ($list as $key => $value ) {
                            $i++;                                              

                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><img class=" user-avatar" alt="" src="<?php echo (isset($value['image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['image'] : BLANK_IMG; ?>" height="70px;"></td>
                        <td><?php echo $value['vehicle_make_name']; ?></td>
                        <td><?php echo $value['model_name']; ?></td>
                        <td>
                           <a href="<?php echo ADMIN_MODEL_URL . $value['model_id']; ?>" 
                              class="btn btn-primary" title="Edit"><i class="fas fa-handshake"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_MODEL_URL.$value['model_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;" title="Delete"></i></a>
                              <a href="<?php echo ADMIN_ASSIGN_MODEL_URL . $value['model_id']; ?>"
                                        class="btn btn-info" title="Add Model"><i class="fas fa-plus-circle" style="padding-right: 0;"></i></a>

                              <a href="<?php echo ADMIN_DETAILS_MODEL_URL . $value['model_id']; ?>" 
                              class="btn btn-warning" title="View Product"><i class="fas fa-eye"
                              style="padding-right: 0;"></i></a>
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