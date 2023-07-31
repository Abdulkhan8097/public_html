<?php $this->load->view('admin/header'); ?>
<style type="text/css">
.upload-btn-wrapper {
        margin-top: 11px;
  position: relative;
  overflow: hidden;
  display: inline-block;
}
.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
</style>

<!--  MIDDLE  -->
<section id="middle">
  <!-- page title -->
  <header id="page-header">
  <h1><i class="fa fa-user"></i> Product Management</h1>
  <ol class="breadcrumb">
    <li><a href="#">Product</a></li>
    <li class="active">Product List</li>
  </ol>
  </header>
    <div id="content" class="dashboard padding-20">

        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>Product list</strong>
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
                        <a href="<?php echo ADMIN_ADD_PRODUCT_URL; ?>" class="btn btn-primary btn-sm ">
                            <i class="fa fa-plus"></i>Add Product
                        </a>
                    </li>
 <li>
                         <form action="<?php echo base_url('admin/Product/import'); ?>" method="POST" enctype="multipart/form-data">
                            <div class="upload-btn-wrapper ">
                              <button class="btn btn-primary btn-sm">Update Product</button>
                              <input type="file" name="file" />
                            </div>
                     </li>
                    <li>
                     <input type="submit" class="btn btn-primary btn-sm" name="uploadFile" value="IMPORT">
                     
                    </li>
                     </form>
                </ul>

            </div>
 <?php if(!empty($success_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    </div>
    <?php if(!empty($error_msg)){ ?>
    <div class="col-xs-12">
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    </div>
    <?php }} ?>
            <div class="panel-body">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Product Image</th>
                                <th scope="col" class="text-center">Part Number</th>
                                <th scope="col" class="text-center">Stock</th>
                                <!--  <th scope="col" class="text-center">User Mobile</th> -->
                                <td scope="col" class="text-center">MRP</td>
                                <td scope="col" class="text-center">Status</td>
                                <!-- <td scope="col" class="text-center">Created Date</td> -->
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                if (isset($product) && !empty($product)) {
                                     $i = 0;
                                foreach ($product as $key => $value ) {
                                    $i++;
                            ?>
                           <tr>
                            <td><?php echo $i; ?></td>
                            <td><img class=" user-avatar" alt=""
                                src="<?php echo (isset($value['profile_img'])) ? PROFILE_DISPLAY_PATH_NAME . $value['profile_img'] : BLANK_IMG; ?>" style="cursor:pointer;" onclick="onClick(this)" height="70px;"></td>
                                <div id="modal01" class="w3-modal" onclick="this.style.display='none'">
                                  <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
                                  <div class="w3-modal-content w3-animate-zoom">
                                    <img id="img01" style="width:100%">
                                  </div>
                                </div>
                                <script>
                                function onClick(element) {
                                  document.getElementById("img01").src = element.src;
                                  document.getElementById("modal01").style.display = "block";
                                }
                                </script>
                            <td><?php echo $value['eve_part_no']; ?></td>
                            <td><?php echo $value['stock']; ?></td>
                            <td><?php echo $value['mrp']; ?></td>

                           
                            <td class="text-center">
                                             <?php if($value['status']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                            </td>
                           <td>
                          
                             <a href="<?php echo ADMIN_ADD_MODEL_URL . $value['product_id']; ?>"
                              class="btn btn-info" title="Detail"><i class="fas fa-eye" style="padding-right: 0;"></i></a> 

                              <a href="<?php echo ADMIN_ADD_PRODUCT_URL . $value['product_id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                                  style="padding-right: 0;"></i></a>

                                  <a href="<?php echo ADMIN_ADD_SERIES_URL . $value['product_id']; ?>"
                              class="btn btn-warning" title="Add Series"><i class="fas fa-plus-circle" style="padding-right: 0;"></i></a>

                                  <a href="<?php echo ADMIN_ADD_COMPTITOR_URL . $value['product_id']; ?>"
                                  class="btn btn-success" title="Add competitor"><i class="fas fa-plus-circle" style="padding-right: 0;"></i></a>
                                   <?php if($value['status']==0)
                                    {?>


                                    <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['product_id']; ?>','1','<?php echo PRODUCT_STATUS_URL ?>');"
                                      class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                <?php } else
                                    {?>
                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['product_id']; ?>','0','<?php echo PRODUCT_STATUS_URL ?>');"
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
                     function statusChange(user_id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo ADMIN_USER_UPDATE_STATUS_URL; ?>",
                          data: {user_id:user_id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>
<script type="text/javascript">
                     function statusChange(product_id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo PRODUCT_STATUS_URL; ?>",
                          data: {product_id:product_id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>

