<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Parameter Details</strong>
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
                        <th scope="col" class="text-center">parameter_name</th>
                        <th scope="col" class="text-center">parameter_order</th>
                        <th scope="col" class="text-center">advance_Search</th>
                        <th scope="col" class="text-center">related</th>
                        <th scope="col" class="text-center">search_dimension</th>
                        <th scope="col" class="text-center">display</th>
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
                        <td><?php echo $value['parameter_order']; ?></td>
                        <td><?php echo $value['advance_Search']; ?></td>
                        <td><?php echo $value['related']; ?></td>
                        <td><?php echo $value['search_dimension']; ?></td>
                        <td><?php echo $value['display']; ?></td>

                        <!-- <td><label><a target="blank" href="<?php echo PROFILE_DISPLAY_PATH_NAME .'/'. $value['files_name']; ?>" class="text-dark-medium text-info"><?php echo $value['files_name']; ?></a></label></td>
                        <td>
                           <a href="<?php echo ADMIN_DOWNLOAD_URL . $value['id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-handshake"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_DOWNLOAD_URL.$value['id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;"></i></a>
                        </td> -->
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