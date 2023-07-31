<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
  <header id="page-header">
  <h1><i class="fa fa-user"></i> Product Management </h1>
  <ol class="breadcrumb">
    <li><a href="#">Series</a></li>
    <li class="active">Series Category</li>
  </ol>
  </header>
   <div id="content" class="dashboard padding-20">
     
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD SERIES CATEGORY</strong>
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
                  <form action="<?php echo ADMIN_SAVES_SERIES_CATEGORY_URL; ?>" method="POST">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="customer_loan_id">Series Category Name<span class="required">*</span></label>
                              <input type="text" name="series_category_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['series_category_name'] : ''; ?>" placeholder="Series Category Name" required>
                              
                           </div>
                        </div>
                        <div class="col-md-6">
                     <div class="form-group">
                        <label for="customer_loan_id">Series Category Sequence</label>
                        
                        <select name="series_category_sequence" id="" class="form-control">
                          <option value="" class="disabled"><--Select Series Category Sequence--></option>
                          <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='1') ? 'selected' : ''; ?>>1</option>
                          <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='2') ? 'selected' : ''; ?>>2</option>
                          <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='3') ? 'selected' : ''; ?>>3</option>
                          <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='4') ? 'selected' : ''; ?>>4</option>
                          <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='5') ? 'selected' : ''; ?>>5</option>
                          <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='6') ? 'selected' : ''; ?>>6</option>
                          <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='7') ? 'selected' : ''; ?>>7</option>
                          <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='8') ? 'selected' : ''; ?>>8</option>
                          <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='9') ? 'selected' : ''; ?>>9</option>
                          <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='10') ? 'selected' : ''; ?>>10</option>
                          <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='11') ? 'selected' : ''; ?>>11</option>
                          <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='12') ? 'selected' : ''; ?>>12</option>
                          <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='13') ? 'selected' : ''; ?>>13</option>
                          <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='14') ? 'selected' : ''; ?>>14</option>
                          <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='15') ? 'selected' : ''; ?>>15</option>
                          <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='16') ? 'selected' : ''; ?>>16</option>
                          <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='17') ? 'selected' : ''; ?>>17</option>
                          <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='18') ? 'selected' : ''; ?>>18</option>
                          <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='19') ? 'selected' : ''; ?>>19</option>
                          <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='20') ? 'selected' : ''; ?>>20</option>
                          <option value="21" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='21') ? 'selected' : ''; ?>>21</option>
                          <option value="22" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='22') ? 'selected' : ''; ?>>22</option>
                          <option value="23" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='23') ? 'selected' : ''; ?>>23</option>
                          <option value="24" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='24') ? 'selected' : ''; ?>>24</option>
                          <option value="25" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='25') ? 'selected' : ''; ?>>25</option>
                          <option value="26" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='26') ? 'selected' : ''; ?>>26</option>
                          <option value="27" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='27') ? 'selected' : ''; ?>>27</option>
                          <option value="28" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='28') ? 'selected' : ''; ?>>28</option>
                          <option value="29" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='29') ? 'selected' : ''; ?>>29</option>
                          <option value="30" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='30') ? 'selected' : ''; ?>>30</option>
                          <option value="31" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='31') ? 'selected' : ''; ?>>31</option>
                          <option value="32" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='32') ? 'selected' : ''; ?>>32</option>
                          <option value="33" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='33') ? 'selected' : ''; ?>>33</option>
                          <option value="34" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='34') ? 'selected' : ''; ?>>34</option>
                          <option value="35" <?php echo (isset($edit) && !empty($edit) && $edit['series_category_sequence']=='35') ? 'selected' : ''; ?>>35</option>
                          
                        </select>
                     </div>
                  </div>  
                     </div>
                     
                     <div class="text-center">
                        <input type="hidden" name="category_series_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['category_series_id'] : ''; ?>">  
                                              
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
                    <strong>Series Category</strong>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Series Category Name</th>
                                <th scope="col" class="text-center">Series Category Sequence</th>
 <td scope="col" class="text-center">Status</td>
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
                           
                            <td><?php echo $value['series_category_name']; ?></td>
                            <td><?php echo $value['series_category_sequence']; ?></td>
<td class="text-center">
                                             <?php if($value['status']==1)
                                             {?>
                                                <span class="badge badge-success">Active</span>
                                            <?php }else{  ?>
                                                <span class="badge badge-error">Inactive</span>
                                                 <?php } ?>
                        </td>

                            
                            <!-- <td><?php echo $value[''] ?></td> -->

                            <td>

                              <a href="<?php echo ADMIN_SERIES_CATEGORY_URL . $value['category_series_id']; ?>"
                                        class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                                            style="padding-right: 0;"></i></a>
                                    <a href="<?php echo ADMIN_DELETE_SERIES_CATEGORY_URL.$value['category_series_id']; ?>"
                                        onclick="return confirm('Are you sure to Delete?');"
                                        class="btn btn-danger"><i class="fa fa-trash"
                                            style="padding-right: 0;"></i></a>

 <?php if($value['status']==0)
                                                    {?>


                                                      <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_series_id']; ?>','1','<?php echo ADMIN_DOWNLOADS_STATUS_URL ?>');"
                                        class="btn btn-success" title="Active"><i class="fas fa-toggle-on"  style="padding-right: 0;"></i></a>
                                                <?php } else
                                                    {?>
                                                        <a href="javascript:void(0);" onclick="statusChange('<?php echo $value['category_series_id']; ?>','0','<?php echo ADMIN_DOWNLOADS_STATUS_URL ?>');"
                                        class="btn btn-danger" title="Inactive"><i class="fas fa-toggle-off"  style="padding-right: 0;"></i></a>
                                                <?php } ?> 
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
                     function statusChange(id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo ADMIN_STATUS_SERIES_UPDATE_CATEGORY_URL; ?>",
                          data: {id:id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>
