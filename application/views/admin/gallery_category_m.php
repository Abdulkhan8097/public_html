<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Gallery Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Gallery List</a></li>
         <li class="active"> Add Gallery</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD Gallery</strong>
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
               <form action="<?php echo base_url('admin/Gallery_category_m/save_gallery/') ?>" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Gallery Category Name<span class="required">*</span></label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" name="g_category_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['g_category_name'] : ''; ?>" class="form-control" required placeholder="Gallery Category Name">
                     </div>
                  </div>
                  <div class="col-md-6">
                     

                     <div class="form-group">
                              <label for="city">Gallery Category Sequence<span class="required"></span></label>
                              <select name="g_category_seq"  class="form-control" id="article">
                                 <option selected disabled><---Select Gallery Category Sequence---></option>
                                 
                                 <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='1') ? 'selected' : ''; ?>>1</option>
                                 <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='2') ? 'selected' : ''; ?>>2</option>
                                 <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='3') ? 'selected' : ''; ?>>3</option>
                                 <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='4') ? 'selected' : ''; ?>>4</option>
                                 <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='5') ? 'selected' : ''; ?>>5</option>
                                 <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='6') ? 'selected' : ''; ?>>6</option>
                                 <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='7') ? 'selected' : ''; ?>>7</option>
                                 <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='8') ? 'selected' : ''; ?>>8</option>
                                 <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='9') ? 'selected' : ''; ?>>9</option>
                                 <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='10') ? 'selected' : ''; ?>>10</option>
                                 <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='11') ? 'selected' : ''; ?>>11</option>
                                    <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='12') ? 'selected' : ''; ?>>12</option>
                                    <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='13') ? 'selected' : ''; ?>>13</option>
                                    <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='14') ? 'selected' : ''; ?>>14</option>
                                    <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='15') ? 'selected' : ''; ?>>15</option>
                                    <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='16') ? 'selected' : ''; ?>>16</option>
                                    <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='17') ? 'selected' : ''; ?>>17</option>
                                    <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='18') ? 'selected' : ''; ?>>18</option>
                                    <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='19') ? 'selected' : ''; ?>>19</option>
                                    <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_seq']=='20') ? 'selected' : ''; ?>>20</option>

                                        
                              </select>
                           </div>
                  </div>
                   
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="g_category_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['g_category_id'] : ''; ?>">
            
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
<strong>Gallery LIST</strong>
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
<th scope="col" class="text-center">Gallery Category Name</th>
<th scope="col" class="text-center">Gallery Category Sequence</th>
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

<td><?php echo $value['g_category_name']; ?></td>
<td><?php echo $value['g_category_seq']; ?></td>
<td>
   <a href="<?php echo base_url('admin/gallery_category_m/index/') . $value['g_category_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
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