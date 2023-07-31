<style type="text/css">
  .btn-5 {
  border: 0 solid;
  box-shadow: inset 0 0 20px rgba(140, 255, 255, 0);
  outline: 1px solid;
  outline-color: rgba(255, 255, 255, .5);
  outline-offset: 0px;
  text-shadow: none;
  transition: all 1250ms cubic-bezier(0.19, 1, 0.22, 1);
} 

.btn-5:hover {
  border: 1px solid;
  box-shadow: inset 0 0 20px rgba(9, 112, 9, 0.94), 0 0 20px rgba(9, 112, 9, 0.94);
  outline-color: rgba(255, 255, 255, 0);
  outline-offset: 15px;
  text-shadow: 1px 1px 2px #427388; 

}
</style>
<?php $this->load->view('admin/header'); ?>
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">stock management</a></li>
         <li class="active">stock adustment</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
   <div id="panel-1" class="panel panel-default">
      <div class="panel-heading">
         <span class="title elipsis">
            <strong>ADD stock</strong>
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
                      <a href="<?php echo ADMIN_Stock_Adjustment_LIST_URL; ?>" class="btn btn-primary btn-sm "> 
                            <i class="fa fa-search"></i>Search Stock
                        </a>
                    </li>
         </ul>
      </div>
      <div class="panel-body">
         <form action="<?php echo base_url('admin/Add_stock/sava_data/'); ?>" method="POST">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label for="company name">Everest Part No.<span class="required">*</span></label>
                     <select name="fk_product_id" class=" select2 form-control" id="product_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['product_id'] : ''; ?>">
                        <option selected disabled ><--Select Part No. --></option>
                        <?php if(isset($fetch_data) && !empty($fetch_data)){
                           foreach ($fetch_data as $key => $value) {
                               ?>
                        <option value="<?php echo $value['product_id']; ?>"<?php echo (isset($edit) && !empty($edit) && $edit['product_id']==$edit['product_id']) ? 'selected' : ''; ?> >
                           <?php echo $value['eve_part_no']; ?>
                        </option>
                        <?php }
                           } ?>          
                     </select>
                  </div>
               </div>
              <div class="col-md-4">
               <div class="form-group">
                   <label for="qty">Add Qty<span class="required">*</span></label>
                   <input type="number"  class="form-control" name="current_stock" id="qty"  >
               </div>
               </div>
               <div class="col-md-4 operators">
                  <h4>Stock Adjustment</h4>
                  <input name="radio"  type="radio" id="plus" value="1" required>
                  <label  for="age1">InWord <b>(+)</b></label>&nbsp; &nbsp; &nbsp;

                  <input  name="radio" type="radio" id="minus" value="0" required >
                  <label for="age1">OutWord<b>(-)</b></label><br>
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

          
               <button onclick="calc();" type="submit"  class="btn btn-5 btn-3d btn-green text-center"><i
                  class="fa fa-save"></i>
               Update Stock</button>
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
<strong>stock management list</strong>
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
                        <th scope="col" class="text-center">Everest Part No</th>
                        <th scope="col" class="text-center">MRP</th>
                        <th scope="col" class="text-center">QTY</th>
</tr>
</thead>
<tbody>
<?php
   if (isset($stock_data_list) && !empty($stock_data_list)) {
        $i = 0;
   foreach ($stock_data_list as $key => $value ) {
       $i++;
   ?>
<tr>
<td><?php echo $i; ?></td>
                         <td><?php echo $value['eve_part_no']; ?></td>
                        <td><?php echo $value['mrp']; ?></td>
                        <td><?php echo $value['current_stock']; ?></td>
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
<script>
   function calc(){
      var qty = parseInt(document.getElementById('qty').value);
      var oper = parseInt(document.getElementById('operators').value);
      if(oper === '+'){
          var qty = document.getElementById('result').value = qty + qty;
      }
      if(oper === '-'){
          var qty = document.getElementById('result').value = qty - qty;
      }
   }
</script>
<script type="text/javascript">
  var SessionScope = (function() {
  var o = {};

  // outside scripts can't see this:
  var user_id = '';

  // these methods can see session_id and use it to channel/sign requests
  o.get = function(url, data) { };
  o.post = function(url, data) { };
  o.whateverelse = function() { };

  return o;
})();
</script>