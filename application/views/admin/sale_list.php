<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
  <header id="page-header">
      <h1><i class="fa fa-user"></i>Discount</h1>
      <ol class="breadcrumb">
         <li><a href="#">Sale Discount</a></li>
         <li class="active">Sale Discount List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD EVEREST DISCOUNT</strong>
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
                  <form action="<?php echo ADMIN_SALE_URL; ?>" method="POST">
                  <div class="row">
                     <div class="form-group">
                        <div class="col-md-6">
                           <label for="type">Name<span class="required">*</span></label>
                           <select name="user_id" class="form-control" id="type">
                              <option selected disabled>--Select Type--</option>
                              <?php print_r($user); ?>
                              <?php if(isset($user) && !empty($user)){
                                 foreach ($user as $key => $value) {
                                     ?>
                              <option value="<?php echo $value['user_id']; ?>">
                                 <?php echo $value['first_name'].' '.$value['last_name']; ?>
                              </option>
                              <?php }
                                 } ?>          
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="card mb-3 catag">
                     <div class="text-center">
                        <button type="submit" id="save-button" class="btn btn-3d btn-green text-center"><i
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
               <strong>Sales</strong>
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
 <th scope="col" class="text-center">User Name</th>
                        <th scope="col" class="text-center">Company Name</th>
                        <th scope="col" class="text-center">Discount</th>
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
 <td><?php echo $value['first_name'].' '.$value['last_name']; ?></td>
                        <td><?php echo $value['company_name2']; ?></td>
                        <td><?php echo $value['discount1']; ?></td>
                        <td>
                           <a href="<?php echo ADMIN_DELETE_SALE_URL.$value['sales_competitor_discount_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
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