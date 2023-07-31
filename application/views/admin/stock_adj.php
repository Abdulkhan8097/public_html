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

<!--  MIDDLE  -->
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
                      <a href="<?php echo ADMIN_Stock_CHECK_LIST_URL; ?>" class="btn btn-primary btn-sm "> 
                            <i class="fa fa-plus"></i>ADD STOCK
                        </a>
                    </li>
            </ul>
         </div>
         <div class="panel-body">
               <form action="<?php echo ADMIN_CHECKED_DATA_URL; ?>" method="POST">
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="company name">Everest Part No.<span class="required">*</span></label>
                         <select name="product_id" class="select2 form-control">
                           <option selected disabled ><--Select Part No. --></option>
                           <?php if(isset($fetch_data) && !empty($fetch_data)){
                              foreach ($fetch_data as $key => $value) {
                                  ?>
                           <option value="<?php echo $value['product_id']; ?>">
                              <?php echo $value['eve_part_no']; ?>
                           </option>
                           <?php }
                              } ?>          
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
         
         
         <button type="submit" class="btn btn-5 btn-3d btn-green text-center"><i
            class="fa fa-save"></i>
         chacked Data</button>
         </div>
         </form>
      <!-- </div> -->
   </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<!-- checked in end -->
<div style="margin-top: 220px">
<?php $this->load->view('admin/footer'); ?>
</div>

<!-- checkbox radio -->
