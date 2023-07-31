<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Product Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Model</a></li>
         <li class="active"> Model List</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>add MODEL</strong>
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
                      <i class="fa fa-plus"></i>Add Vehicle</a>
                </li>
            </ul>
         </div>
         <div class="panel-body">
            <div class="row">
               <form action="<?php echo ADMIN_SAVE_MODAL_URL; ?>" method="POST" enctype="multipart/form-data">   
                  <div class="col-md-6">
                           <div class="form-group">
                              <label for="city">Vechicle Brand<span class="required"></span></label>
                              <select name="fk_vm_id" class="select2 form-control" id="vechicle_make" onselect="myFunction()" >
                                 <option selected disabled>--Select Vechicle Brand--</option>
                                 <?php if(isset($vechicle) && !empty($vechicle)){
                                    foreach ($vechicle as $key => $value) {
                                        ?>
                                 <option value="<?php echo $value['vm_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['vm_id']==$value['vm_id']) ? 'selected' : ''; ?>>
                                    <?php echo $value['vehicle_make_name']; ?>
                                 </option>
                                 <?php }
                                    } ?>          
                              </select>
                           </div>

                          
                        </div>
                        <div class="col-md-6">
                           <div class="form-group" >
                              <label for="title">Vechicle Brand Type<span class="required">*</span></label>
                              <select name="fk_model_category_id" class="select2 form-control">
                             </select>
                           </div>
                        </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Model Name<span class="required">*</span></label>
                        <input type="text" name="model_name" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_name'] : ''; ?>" class="form-control" required placeholder="Model Name">
                     </div>
                  </div>
<div class="col-md-6">
                        <div class="form-group">
                           <label for="customer_loan_id">Display Sequence No.<span class="required">*</span></label>
                           <select name="model_sequence" class="form-control" id="model_sequence" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_sequence'] : ''; ?>">
                              <option selected disabled><--Select Display Sequence No.--></option>
                              <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='1') ? 'selected' : ''; ?>>1</option>
                              <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='2') ? 'selected' : ''; ?>>2</option>
                              <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='3') ? 'selected' : ''; ?>>3</option>
                              <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='4') ? 'selected' : ''; ?>>4</option>
                              <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='5') ? 'selected' : ''; ?>>5</option>
                              <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='6') ? 'selected' : ''; ?>>6</option>
                              <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='7') ? 'selected' : ''; ?>>7</option>
                              <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='8') ? 'selected' : ''; ?>>8</option>
                              <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='9') ? 'selected' : ''; ?>>9</option>
                              <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='10') ? 'selected' : ''; ?>>10</option>
                              <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='11') ? 'selected' : ''; ?>>11</option>
                              <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='12') ? 'selected' : ''; ?>>12</option>
                              <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='13') ? 'selected' : ''; ?>>13</option>
                              <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='14') ? 'selected' : ''; ?>>14</option>
                              <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='15') ? 'selected' : ''; ?>>15</option>
                              <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='16') ? 'selected' : ''; ?>>16</option>
                              <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='17') ? 'selected' : ''; ?>>17</option>
                              <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='18') ? 'selected' : ''; ?>>18</option>
                              <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='19') ? 'selected' : ''; ?>>19</option>
                              <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='20') ? 'selected' : ''; ?>>20</option>
                              <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='21') ? 'selected' : ''; ?>>21</option>
                              <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='22') ? 'selected' : ''; ?>>22</option>
                              <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='23') ? 'selected' : ''; ?>>23</option>
                              <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='24') ? 'selected' : ''; ?>>24</option>
                              <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='25') ? 'selected' : ''; ?>>25</option>
         <option value="26" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='26') ? 'selected' : ''; ?>>26</option>
         <option value="27" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='27') ? 'selected' : ''; ?>>27</option>
         <option value="28" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='28') ? 'selected' : ''; ?>>28</option>
         <option value="29" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='29') ? 'selected' : ''; ?>>29</option>
         <option value="30" <?php echo (isset($edit) && !empty($edit) && $edit['model_sequence']=='30') ? 'selected' : ''; ?>>30</option>
                           </select>
                        </div>
                     </div>
                  <div class="form-group">
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label for="customer_image">Model Image<span class="required"></span></label>
                                 <input type="file" onchange="display_img(this);" class="form-control" name="image" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-6">
                              <img src="<?php echo (isset($edit['image']) && !empty($edit['image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                  </div>
              </div>
            <div class="text-center" style="margin-top: 30px;">
            <input type="hidden" name="model_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['model_id'] : ''; ?>">
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
               <strong>Model LIST</strong>
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
                        <th scope="col" class="text-center">image</th>
                        <th scope="col" class="text-center">Vechicle Brand</th>
                        <th scope="col" class="text-center">Vechicle Type</th>
                        <th scope="col" class="text-center">Model Name</th>
 <th scope="col" class="text-center">Sequence</th>
                        
                   
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                    
                     <?php
                     // print_r($details);
                        if (isset($list) && !empty($list)) {
                             $i = 0;
                        foreach ($list as $key => $value ) {
                            $i++;                                              

                        ?>
                     <tr>
                        <td><?php echo $i; ?></td>
                        <td><img class=" user-avatar" alt="" src="<?php echo (isset($value['image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['image'] : BLANK_IMG; ?>" height="70px;"></td>
                        <td><?php echo $value['vehicle_make_name']; ?></td>
                        <td><?php echo $value['fk_model_category_id']; ?></td>
                        <td><?php echo $value['model_name']; ?></td>
<td><?php echo $value['model_sequence']; ?></td>

                        <td>
                              
                              <?php if($value['status']=='1'){ ?>
                                 <div>
                                     <span class="badge badge-success">Active</span>
                                 </div>
                              <?php }else { ?>
                                 <div>
                                     <span class="badge badge-danger">InActive</span>
                                 </div>
                              <?php } ?>

                        </td>
                        <td>

                           <?php if($value['status']=='0'){ ?>

                              <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['model_id']; ?>','1','<?php echo base_url('admin/Model/updatestatus');?>');"
                                        class="btn btn-success" title="Active"><i class="fa fa-toggle-on"  style="padding-right: 0;"></i></a>

                           <?php } else{ ?>
                              <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['model_id']; ?>','0','<?php echo base_url('admin/Model/updatestatus');?>');"
                                        class="btn btn-danger" title="Inactive"><i class="fa fa-toggle-off"  style="padding-right: 0;"></i></a>
                           <?php } ?>

                           <a href="<?php echo ADMIN_MODEL_URL . $value['model_id']; ?>" 
                              class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                              style="padding-right: 0;"></i></a>
                           <a href="<?php echo ADMIN_DELETE_MODEL_URL.$value['model_id']; ?>"
                              onclick="return confirm('Are you sure to Delete?');"
                              class="btn btn-danger"><i class="fa fa-trash"
                              style="padding-right: 0;" title="Delete"></i></a>
                              <a href="<?php echo ADMIN_ASSIGN_MODEL_URL . $value['model_id']; ?>"
                                        class="btn btn-info" title="Add Model"><i class="fas fa-plus-circle" style="padding-right: 0;"></i></a>

                              <a href="<?php echo ADMIN_DETAILS_MODEL_URL . $value['model_id']; ?>" 
                              class="btn btn-warning" title="View Product"><i class="fas fa-eye"
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
<script>
function myFunction() {
  alert("You selected some text!");
}
</script>

<script type="text/javascript">
                     function statusChange(model_id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo base_url('admin/Model/updatestatus');?>",
                          data: {model_id:model_id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>

<script type="text/javascript">


    $(document).ready(function() {
        $('select[name="fk_vm_id"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: '/index.php/admin/model/myformajax/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('select[name="fk_model_category_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="fk_model_category_id"]').append('<option value="'+ value.category_model_id +'">'+ value.model_category_name +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="fk_model_category_id"]').empty();
            }
        });
    });
</script>
