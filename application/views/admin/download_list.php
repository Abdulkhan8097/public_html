<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Downloads</h1>
      <ol class="breadcrumb">
         <li><a href="#">Downloads</a></li>
         <li class="active">Downloads List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>DOWNLOAD LIST</strong>
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
            <form action="<?php echo ADMIN_SAVE_DOWNLOAD_URL; ?>" method="POST" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Download Name<span class="required">*</span></label>
                        <input type="text" name="download_type_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['download_type_name'] : ''; ?>" required placeholder="Download Name">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">File Type<span class="required">*</span></label>
                        <input type="text" name="file_type_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['file_type_name'] : ''; ?>" required placeholder="File Type"> 
                     </div>
                  </div>
                  <div class="form-group">
                           <div class="col-md-3">
                              <div class="form-group" >
                                 <label for="customer_image">Choose File<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="files_name" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-3">
                              <img src="<?php echo (isset($edit['files_name']) && !empty($edit['files_name'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['files_name'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                  </div>

<div class="form-group">
                           <div class="col-md-3">
                              <div class="form-group" >
                                 <label for="image">Choose Icon<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="image" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-3">
                              <img src="<?php echo (isset($edit['image']) && !empty($edit['image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                        </div>
                  <div class="text-center" style="margin-top:20%;">
                     <input type="hidden" name="id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['id'] : ''; ?>">  
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
</section>
<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Download List</strong>
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
                        <th scope="col" class="text-center">Download Type Name</th>
                        <th scope="col" class="text-center">File Type Name</th>
                        <th scope="col" class="text-center">File Name</th>
                        <th scope="col" class="text-center">Icon</th>
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
                        <td><?php echo $value['download_type_name']; ?></td>
                        <td><?php echo $value['file_type_name']; ?></td>
                        <td><label><a target="blank" href="<?php echo PROFILE_DISPLAY_PATH_NAME .'/'. $value['files_name']; ?>" class="text-dark-medium text-info"><?php echo $value['files_name']; ?></a></label></td>
<td><img class=" user-avatar" alt=""
                                src="<?php echo (isset($value['image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['image'] : BLANK_IMG; ?>" height="70px;"></td>
<td class="text-center">
                                             <?php if($value['status']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                        </td>
                        <td>
                           <a href="<?php echo ADMIN_DOWNLOAD_URL . $value['id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_DOWNLOAD_URL.$value['id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;"></i></a>
				 <?php if($value['status']==0)
                                                    {?>


                                                      <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['id']; ?>','1','<?php echo ADMIN_DOWNLOADS_STATUS_URL ?>');"
                                        class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                                <?php } else
                                                    {?>
                                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['id']; ?>','0','<?php echo ADMIN_DOWNLOADS_STATUS_URL ?>');"
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
                          url: "<?php echo ADMIN_DOWNLOADS_STATUS_URL; ?>",
                          data: {id:id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>
