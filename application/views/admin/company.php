<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Company Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">company List</a></li>
         <li class="active"> Add company</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD COMPANY</strong>
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
               <form action="<?php echo base_url('admin/Company/save_company/') ?>" method="POST" enctype="multipart/form-data">
               <div class="col-md-6">
                  <div class="form-group">
                              <label for="city">Company Type<span class="required"></span></label>
                              <select name="company_type"  class="form-control" id="colorselector">
                                
                                 <option value="Parent" <?php echo (isset($edit) && !empty($edit) && $edit['company_type']=='Parent') ? 'selected' : ''; ?>>Parent</option>
                                 <option value="Distributor" <?php echo (isset($edit) && !empty($edit) && $edit['company_type']=='Distributor') ? 'selected' : ''; ?>>Distributor</option>
                                 <option value="Dealer" <?php echo (isset($edit) && !empty($edit) && $edit['company_type']=='Dealer') ? 'selected' : ''; ?>>Dealer</option>
                                 <option value="Customer" <?php echo (isset($edit) && !empty($edit) && $edit['company_type']=='Customer') ? 'selected' : ''; ?>>Customer</option>
                             
                              </select>
                           </div>
               </div>

               <div class="col-md-6">
                  <div style="display:none;" id="red" class="form-group colors red">
                              <label for="city">Company Type<span class="required"></span></label>
                              <select name="d_type" class="form-control" id="type">
                                  <option selected disabled>--Select Type--</option>
                                <!--  <option value="1" >Parent</option> -->

                             
                              </select>
                           </div>
               </div>

               <div class="col-md-6">
                  <div style="display: none;" id="Distributor" class="form-group colors Distributor">
                              <label for="city">Company Type1<span class="required"></span></label>
                              <select name="dealer_distributor_id" class="form-control" id="type">
                                  
                                 <?php if(isset($list1) && !empty($list1)){
                           foreach ($list1 as $key => $value) {
                               ?>
                        <option value="<?php echo $value['company_id']; ?>">
                           <?php echo $value['c_name']; ?>
                        </option>
                        <?php }
                           } ?>   
                              </select>
                           </div>
               </div>
               <div class="col-md-6">
                  <div style="display: none;" id="Dealer" class="form-group colors Dealer">
                              <label for="city">Company Type2<span class="required"></span></label>
                              <select name="dealer_distributor_id" class="form-control" id="type">
                                 
                                 <?php if(isset($list2) && !empty($list2)){
                           foreach ($list2 as $key => $value) {
                               ?>
                        <option value="<?php echo $value['company_id']; ?>">
                           <?php echo $value['c_name']; ?>
                        </option>
                        <?php }
                           } ?>   
                             
                              </select>
                           </div>
               </div>
               <div class="col-md-6">
                  <div style="display: none;" id="Customer" class="form-group colors Customer">
                              <label for="city">Company Type3<span class="required"></span></label>
                              <select name="d_type" class="form-control" id="type">
                                  
                                 <?php if(isset($list3) && !empty($list3)){
                           foreach ($list3 as $key => $value) {
                               ?>
                        <option value="<?php echo $value['company_id']; ?>">
                           <?php echo $value['c_name']; ?>
                        </option>
                        <?php }
                           } ?>   
                              </select>
                           </div>
               </div>
               
               
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Company Name<span class="required">*</span></label>
                        <input oninput="this.value = this.value.toUpperCase()" type="text" name="c_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['c_name'] : ''; ?>" class="form-control" required placeholder="Company Name">
                     </div>
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Order Email Id<span class="required">*</span></label>
                        <input  type="email" name="order_email_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['order_email_id'] : ''; ?>" class="form-control" required placeholder="Order Email Id">
                     </div>
                  </div>



                   <div class="col-md-6">
                     

                     <div class="form-group">
                              <label for="city">Hide Company<span class="required"></span></label>
                              <select name="h_com"  class="form-control" id="article">
                                <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['h_com']=='Yes') ? 'selected' : ''; ?>>Yes</option>
                                 <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['h_com']=='No') ? 'selected' : ''; ?>>No</option>
                                 
                              </select>
                           </div>
                  </div>
                   
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="company_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['company_id'] : ''; ?>">
            
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
<strong>company LIST</strong>
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
<th scope="col" class="text-center">Company Name</th>
<th scope="col" class="text-center">Order Email Id</th>
<th scope="col" class="text-center">Company Hide</th>
<th scope="col" class="text-center">Company Type</th>

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

<td><?php echo $value['c_name']; ?></td>
<td><?php echo $value['order_email_id']; ?></td>
<td><?php echo $value['h_com']; ?></td>
<td><?php echo $value['company_type']; ?></td>
<td>
   <a href="<?php echo base_url('admin/Company/index/') . $value['company_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
   <a href="<?php echo base_url('admin/Add_competitor/index/') . $value['company_id']; ?>" class="btn btn-warning" title="Asign Competer"><i class="fas fa-plus" style="padding-right: 0;"></i></a>
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
   $(function() {
  $('#colorselector').change(function(){
    $('.colors').hide();
    $('#' + $(this).val()).show();
    if($(this).val() == 'all') {
      $('.colors').show();
    }
  });
});

</script>



