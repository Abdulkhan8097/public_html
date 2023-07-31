<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
  <header id="page-header">
  <h1><i class="fa fa-user"></i> Product Management </h1>
  <ol class="breadcrumb">
    <li><a href="#">Series</a></li>
    <li class="active">Series List</li>
  </ol>
  </header>
   <div id="content" class="dashboard padding-20">
     
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD SERIES</strong>
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
                  <form action="<?php echo ADMIN_SAVES_SERIES_URL; ?>" method="POST">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="customer_loan_id">Series Name<span class="required">*</span></label>
                              <input type="text" name="series_name"  class="form-control" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['series_name'] : ''; ?>" placeholder="Series Name" required>
                              
                           </div>
                        </div>  
                     </div>
                     
                     <div class="text-center">
                        <input type="hidden" name="series_id"  value="<?php echo (isset($edit) && !empty($edit)) ? $edit['series_id'] : ''; ?>">  
                                              
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
                    <strong>Series List</strong>
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
                                <th scope="col" class="text-center">Series Name</th>
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
                           
                            <td><?php echo $value['series_name']; ?></td>

                            
                            <!-- <td><?php echo $value[''] ?></td> -->

                            <td>

                              <a href="<?php echo ADMIN_SERIES_URL . $value['series_id']; ?>"
                                        class="btn btn-primary" title="Edit"><i class="fas fa-edit"
                                            style="padding-right: 0;"></i></a>
                                    <a href="<?php echo ADMIN_DELETE_SERIES_URL.$value['series_id']; ?>"
                                        onclick="return confirm('Are you sure to Delete?');"
                                        class="btn btn-danger"><i class="fa fa-trash"
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
