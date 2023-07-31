<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i>Banner</h1>
      <ol class="breadcrumb">
         <li><a href="#">Banner</a></li>
         <li class="active">Banner List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD BANNER</strong>
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
            <form action="<?php echo ADMIN_SAVE_BANNER_URL; ?>" method="POST" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="customer_loan_id">Banner Name</label>
                        <input type="text" name="banner_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['banner_name'] : ''; ?>" class="form-control"  placeholder="Banner Name">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="customer_loan_id">Description</label>
                        <input type="text" name="description" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['description'] : ''; ?>" class="form-control"  placeholder="Description">
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="customer_loan_id">Order</label>
                        <!-- <input type="text" name="order" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['order'] : ''; ?>" class="form-control"  placeholder="Order"> -->
                        <select name="order" id="" class="form-control">
                          <option value="" class="disabled"><--Select Order--></option>
                          <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['order']=='1') ? 'selected' : ''; ?>>1</option>
                          <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['order']=='2') ? 'selected' : ''; ?>>2</option>
                          <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['order']=='3') ? 'selected' : ''; ?>>3</option>
                          <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['order']=='4') ? 'selected' : ''; ?>>4</option>
                          <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['order']=='5') ? 'selected' : ''; ?>>5</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="type">Page</label>
                        <select name="page" class="form-control" id="type" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['page'] : ''; ?>"  >
                           <option selected disabled><--Select Page--></option>
                           <option value="Category" <?php echo (isset($edit) && !empty($edit) && $edit['page']=='Category') ? 'selected' : ''; ?>>Category Page</option>
                           <option value="Product" <?php echo (isset($edit) && !empty($edit) && $edit['page']=='Product') ? 'selected' : ''; ?>>Product Page</option>
                           <option value="Series" <?php echo (isset($edit) && !empty($edit) && $edit['page']=='Series
                           ') ? 'selected' : ''; ?>>Series Page</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label for="customer_loan_id">Hyper Link</label>
                        <input type="text" name="hyper_link" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['hyper_link'] : ''; ?>" class="form-control"  placeholder="Hyper Link">
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
                           <div class="col-md-4">
                              <img src="<?php echo (isset($edit['image']) && !empty($edit['image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                  </div>
               </div>
               <div class="row">
                  <div class="text-center">
                     <input type="hidden" name="id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['id'] : ''; ?>">
                     <button type="reset" class="btn btn-3d btn-default text-center"><i
                        class="fa fa-redo"></i>
                     Reset</button>
                     <button type="submit" class="btn btn-3d btn-green text-center"><i
                        class="fa fa-save"></i>
                     Submit</button>
                  </div>
               </div>
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
               <strong>Banner List</strong>
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
                        <th scope="col" class="text-center">Image</th>
                        <th scope="col" class="text-center">Banner Name</th>
                        <th scope="col" class="text-center">Page</th>
                        <th scope="col" class="text-center">Description</th>
                        <th scope="col" class="text-center">Status</th>
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
                           src="<?php echo (isset($value['image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['image'] : BLANK_IMG; ?>" height="70px;"></td>
                        <td><?php echo $value['banner_name']; ?></td>
                        <td><?php echo $value['page']; ?></td>
                        <td><?php echo $value['description']; ?></td>
                        <td class="text-center">
                           <?php if($value['status']==1)
                              {?>
                           <span class="badge badge-success">Active</span>
                           <?php }else{  ?>
                           <span class="badge badge-error">Inactive</span>
                           <?php } ?>
                        </td>
                        <td>
                           <a href="<?php echo ADMIN_BANNER_URL . $value['id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-handshake"
                              style="padding-right: 0;"></i></a>

                           <a href="<?php echo ADMIN_DELETE_BANNER_URL.$value['id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-info " id="first"><i class="fa fa-trash"
                              style="padding-right: 0;"></i></a>

                           <?php if($value['status']==0)
                              {?>
                           <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['id']; ?>','1','<?php echo ADMIN_USER_UPDATE_STTUS_URL ?>');"
                              class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                           <?php } else
                              {?>
                           <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['id']; ?>','0','<?php echo ADMIN_USER_UPDATE_STTUS_URL ?>');"
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
   function statusChange(id,status)
   {
    $.ajax({
        type: "POST",
        url: "<?php echo ADMIN_USER_UPDATE_STTUS_URL; ?>",
        data: {id:id,status:status},
        cache: false,
        success:function(responseData)
        {            
           location.reload();
        }   
    });
   }
</script>

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