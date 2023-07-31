<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Competotor</a></li>
         <li class="active"> Competotor List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>add Competitor Part</strong>
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
                  <a href="<?php echo ADMIN_ADD_VEHICLE_URL; ?>" class="btn btn-primary btn-sm ">
                      <i class="fa fa-plus"></i>Add Part Number</a>
                </li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="row">
               <form action="<?php echo ADMIN_ADD_SAVE_PART_URL; ?>" method="POST" enctype="multipart/form-data">   <div class="col-md-4">
                           <div class="form-group">
                              <label for="city">Company Name<span class="required"></span></label>
                              <select name="fk_com_master_id" class="form-control" id="type">
                                 <option selected disabled><--Select Vechicle Brand--></option>
                                 <?php if(isset($getComp) && !empty($getComp)){
                                    foreach ($getComp as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['fk_com_master_id']==$value['id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['company_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>
                        </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Part Number</label>
                        <input type="text" name="comp_part_no" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['comp_part_no'] : ''; ?>" class="form-control" required placeholder="Part Number">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Remark</label>
                        <input type="text" name="remark" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['comp_part_no'] : ''; ?>" class="form-control" required placeholder="Remark">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">Effective Date</label>
                        <input type="text" name="eff_date" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['comp_part_no'] : ''; ?>" class="form-control" required placeholder="Effective Date">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="customer_loan_id">MRP</label>
                        <input type="text" name="mrp" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['mrp'] : ''; ?>" class="form-control" required placeholder="MRP">
                     </div>
                  </div>
                  
              </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['competitor_id'] : ''; ?>">
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
               <strong>Part LIST</strong>
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
                        <th scope="col" class="text-center">Company Name</th>
                        <th scope="col" class="text-center">Part Number</th>
                        <th scope="col" class="text-center">Remark</th>
                        <th scope="col" class="text-center">Effective Date</th>
                        <th scope="col" class="text-center">Mrp</th>
                        <th scope="col" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (isset($part) && !empty($part)) {
                             $i = 0;
                        foreach ($part as $key => $value ) {
                            $i++;
                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $value['company_name']; ?></td>
                        <td><?php echo $value['comp_part_no']; ?></td>
                        <td><?php echo $value['remark']; ?></td>
                        <td><?php echo $value['eff_date']; ?></td>
                        <td><?php echo $value['mrp']; ?></td>
                        <td>
                         
                           <a href="<?php echo ADMIN_DELETE_PART_URL.$value['competitor_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;" title="Delete"></i></a>

                                <a href="<?php echo ADMIN_DETAILS_COMPTETOR_URL . $value['competitor_id']; ?>" 
                              class="btn btn-info" title="View Product"><i class="fas fa-eye"
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