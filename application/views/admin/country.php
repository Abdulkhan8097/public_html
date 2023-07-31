<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Country Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">country List</a></li>
         <li class="active"> Add country</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD country</strong>
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
               <form action="<?php echo base_url('admin/Country/save_country/') ?>" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Country Name<span class="required">*</span></label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" name="country_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['country_name'] : ''; ?>" class="form-control" required placeholder="Country Name">
                     </div>
                  </div>
                   
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="country_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['country_id'] : ''; ?>">
            
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
<strong>country LIST</strong>
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
<td>
   <a href="<?php echo base_url('admin/Country/index/') . $value['country_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
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