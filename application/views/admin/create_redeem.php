<?php $this->load->view('admin/header'); ?>


<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i>Redeem Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Redeem</a></li>
         <li class="active">Create Redeem</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Create Redeem</strong>
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
           
            <div class="row">
                
                  <form action="<?php echo base_url('admin/Redeem_request/saveSeries'); ?>" id="myform" method="POST" enctype="multipart/form-data" >
                     <div class="row">
                        
                      

                        <div class="col-md-6">
                           <div class="form-group">
  <label for="exampleFormControlTextarea2">Redeem Details</label>
  <textarea class="form-control rounded-0" name="redeem_details" id="exampleFormControlTextarea2" rows="10"><?php echo (isset($edit) && !empty($edit)) ? $edit['redeem_details'] : ''; ?></textarea>
</div>
                        </div>


                        <div class="col-md-6">
                           <div class="form-group">
                              <input type="hidden" name="redeem_status" class="form-control" value="Yes" readonly>
                              
                           </div>
                        </div>
                        
                     <div class="row">
                     	<div class="col-md-12">
                     		<div class="text-center">
                        <input type="hidden" name="redeem_request_id"
                           value="<?php echo (isset($edit) && !empty($edit)) ? $edit['redeem_request_id'] : ''; ?>">
                        <a href="<?php echo base_url('admin/Redeem_request/index/'); ?>" class="btn btn-3d btn-red text-center">
                        <i class="fa fa-backward"></i> Back
                        </a>
                        <button type="reset" class="btn btn-3d btn-default text-center">
                        <i class="fa fa-redo"></i> Reset
                        </button>
                        <button type="submit" id="Submit" name="Submit" class="btn btn-3d btn-green text-center">
                        <i class="fa fa-save"></i> Submit
                        </button>
                     </div>
                     	</div>
                     </div>
                  </form>
              
            </div>
         </div>
      </div>
   </div>
</section>
</div>
<?php $this->load->view('admin/footer'); ?>
                 


