<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> State Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">State List</a></li>
         <li class="active"> Add State</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD State</strong>
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
               <form action="<?php echo base_url('admin/State/save_state/') ?>" method="POST" enctype="multipart/form-data">
<div class="col-md-6">
                  <div class="form-group">
                              <label for="city">Country Name<span class="required"></span></label>
                              <select name="country_name" class="form-control" id="type">
                                  <option selected disabled>--Select Type--</option>
                                 <?php if(isset($list1) && !empty($list1)){
                           foreach ($list1 as $key => $value) {
                               ?>
                        <option value="<?php echo $value['country_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['country_id']==$value['country_id']) ? 'selected' : ''; ?>>
                           <?php echo $value['country_name']; ?>
                        </option>
                        <?php }
                           } ?>   
                              </select>
                           </div>
               </div>


                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">State Name<span class="required">*</span></label>
                        <input type="text" name="state_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['state_name'] : ''; ?>" class="form-control" required placeholder="State Name">
                     </div>
                  </div>

                  
                   
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="state_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['state_id'] : ''; ?>">
            
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
<strong>State LIST</strong>
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
<th scope="col" class="text-center">Country Name</th>
<th scope="col" class="text-center">State Name</th>
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
<td><?php echo $value['country_name']; ?></td>
<td><?php echo $value['state_name']; ?></td>
<td>
   <a href="<?php echo base_url('admin/State/index/') . $value['state_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
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