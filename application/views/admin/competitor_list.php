<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Competitor</a></li>
         <li class="active"> Competitor List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD COMPETITOR</strong>
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
               <form action="<?php echo ADMIN_SAVE_COMPETITOR_URL; ?>" method="POST">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="company name">Company Name<span class="required">*</span></label>
                        <input type="text" name="company_name" id="company name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['company_name'] : ''; ?>" class="form-control" required placeholder="Company Name">
                     </div>
                  </div>
<div class="col-md-6">
                           <div class="form-group">
                              <label for="discount">Discount  (Percentage)</label>
                              <input name="discount2" type="tel" step="any" maxlength='6'
                                 class="form-control" id="discount" placeholder="Discount..."
                                 value="<?php echo (isset($edit) && !empty($edit)) ? $edit['discount2'] : ''; ?>"
                                 >
                           </div>
                        </div>


                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="city">Hide Price Comparision<span class="required"></span></label>
                              <select name="hide_price_comparision" class="form-control" id="type">
                                  <option value="No" <?php echo (isset($edit) && !empty($edit) && $edit['hide_price_comparision']=='No') ? 'selected' : ''; ?>>NO</option>
                                 <option value="Yes" <?php echo (isset($edit) && !empty($edit) && $edit['hide_price_comparision']=='Yes') ? 'selected' : ''; ?>>YES</option>
                                
                                
                              </select>
                           </div>
                        </div>


               </div>    
            <!-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="customer_loan_id">Part No<span class="required">*</span></label>
                        <input type="text" name="part_no" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['part_no'] : ''; ?>" class="form-control" required placeholder="Part Number">
                    </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Mrp<span class="required">*</span></label>
                        <input type="number" name="mrp" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['mrp'] : ''; ?>" class="form-control" required placeholder="MRP"> 
                     </div>
                  </div>
           
         </div> -->
         <div class="text-center" style="margin-top: 20px;">
         <input type="hidden" name="id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['id'] : ''; ?>">
         <button type="reset" class="btn btn-3d btn-default text-center"><i
            class="fa fa-redo"></i>
         Reset</button>
         <button type="submit" class="btn btn-3d btn-green text-center"><i
            class="fa fa-save"></i>
         Submit</button>
         </div>
         </form>
      <!-- </div> -->
   </div>
</section>
<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>COMPETITOR LIST</strong>
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
                       <th scope="col" class="text-center">Discount</th>
                       <th scope="col" class="text-center">Hide Price Comparision</th>
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
                        <td><?php echo $value['company_name']; ?></td>
                        <td><?php echo $value['discount2']; ?></td>
                        <td><?php echo $value['hide_price_comparision']; ?></td>
                        <td>
                           <a href="<?php echo ADMIN_COMPETITOR_URL . $value['id']; ?>"
                                        class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                                            style="padding-right: 0;"></i></a>


                           <!-- <a href="<?php echo ADMIN_COMPETITOR_URL.$value['id']; ?>"
                              class="btn btn-success btn-sm"><i class="fas fa-pen"
                              style="padding-right: 0;"></i></a> -->
                           <a href="<?php echo ADMIN_DELETE_COMPETITOR_URL.$value['id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;"></i></a>
                              <a href="<?php echo ADMIN_ADD_COMP_URL.$value['id']; ?>"
                                        class="btn btn-warning" title="Add Series"><i class="fas fa-plus-circle" style="padding-right: 0;"></i></a>
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
   function getAppliedAmount()
   {
      $(document).ready(function () {
        var customer_loan_id = $('#customer_loan_id').find(":selected").val();
        var parameters = "customer_loan_id=" + customer_loan_id;
        $.ajax({
            type: "POST",
            url: '<?php echo ADMIN_GET_APPLIED_AMOUNT_URL; ?>',
            data: parameters ,
            success: function (response) {
                var responseData = $.parseJSON(response);
                $('#applied_amount').val(responseData.amount);
                $('#bank_id').val(responseData.financer_name_id);
            }
        }); 
    });
   }
</script>