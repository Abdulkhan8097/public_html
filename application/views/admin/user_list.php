<?php $this->load->view('admin/header'); ?>

<!--  MIDDLE  -->
<section id="middle">
  <!-- page title -->
  <header id="page-header">
  <h1><i class="fa fa-user"></i> User Management</h1>
  <ol class="breadcrumb">
    <li><a href="#">User</a></li>
    <li class="active">User List</li>
  </ol>
  </header>
    <div id="content" class="dashboard padding-20">

        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>user list</strong>
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
                        <a href="<?php echo base_url('admin/User_login/index/'); ?>" class="btn btn-primary btn-sm ">
                            <i class="fa fa-plus"></i>Log in User
                        </a>
                    </li>
                     <li>
                        <a href="<?php echo ADMIN_CREATE_USER_URL; ?>" class="btn btn-primary btn-sm ">
                            <i class="fa fa-plus"></i>Create User
                        </a>
                    </li>
                </ul>

            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">User Image</th>
                                <th scope="col" class="text-center">User Name</th>
                                <th scope="col" class="text-center">Company Name</th>
                                <th scope="col" class="text-center">User Email</th>
                                <th scope="col" class="text-center">Country</th>
                                 <th scope="col" class="text-center">Contact No.</th>
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
                                src="<?php echo (isset($value['profile_img'])) ? PROFILE_DISPLAY_PATH_NAME . $value['profile_img'] : BLANK_IMG; ?>" height="70px;"></td>
                            <td><?php echo $value['first_name']; ?></td>
                            <td><?php echo $value['c_name']; ?></td>
                            <td><?php echo $value['email']; ?></td>
                            <td><?php echo $value['country']; ?></td>
                            <td><?php echo $value['phone']; ?></td>
                            <td><?php echo $value['discount']; ?></td>
                            <td class="text-center">
                                             <?php if($value['isActive']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                            </td>
 
                            <td>
                                    
                                    <!-- <a href="<?php echo ADMIN_CREATE_USER_URL.$value['user_id']; ?>"
                                        class=""><span class="badge badge-warning">Edit</span></a> -->

                                        <a href="<?php echo ADMIN_CREATE_USER_URL . $value['user_id']; ?>"
                                        class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                                            style="padding-right: 0;"></i></a>

                                            <?php if($value['isActive']==0)
                                                    {?>


                                                      <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['user_id']; ?>','1','<?php echo ADMIN_USER_UPDATE_STATUS_URL ?>');"
                                        class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                                <?php } else
                                                    {?>
                                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['user_id']; ?>','0','<?php echo ADMIN_USER_UPDATE_STATUS_URL ?>');"
                                        class="btn btn-danger" title="Inactive"><i class="fas fa-toggle-off"  style="padding-right: 0;"></i></a>
                                                <?php } ?> 

                                               
                                </td>
                           </tr>

                       <?php }}  ?>
                        </tbody>
                    </table>
                    <iframe id="dummyFrame" style="display:none"></iframe>
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


