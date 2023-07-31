<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
  <header id="page-header">
      <h1><i class="fa fa-user"></i>Discount</h1>
      <ol class="breadcrumb">
         <li><a href="#">Everest Discount</a></li>
         <li class="active">Everest Discount List</li>
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
                  <form action="<?php echo ADMIN_SAVE_DISCOUNT_URL; ?>" method="POST">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="Discount">Discount<span class="required">*</span></label>
                              <input type="number" name="discount" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['discount'] : ''; ?>" class="form-control" required placeholder="Everest Discount">
                           </div>
                        </div>
                     </div>
                     <div class="text-center">
                        <input type="hidden" name="everest_discount_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['everest_discount_id'] : ''; ?>">
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
               <strong>Everest Discount List</strong>
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
               <li></li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="table-responsive">
               <table class="table table-bordered table-hover text-center example" financer_name>
                  <thead>
                     <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Discount</th>
                        <!--  <th scope="col" class="text-center">User Mobile</th> -->
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
                        <td><?php echo $value['discount']; ?></td>
                        <td>
                           <a href="<?php echo ADMIN_DISCOUNT_URL . $value['everest_discount_id']; ?>"
                              class="btn btn-primary" title="Edit"><i class="fas fa-handshake"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_DISCOUNT_URL.$value['everest_discount_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger" title="Delete"><i class="fa fa-trash"
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