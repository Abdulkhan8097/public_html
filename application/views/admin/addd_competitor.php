<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
  <header id="page-header">
  <h1><i class="fa fa-user"></i> Competitor Management</h1>
  <ol class="breadcrumb">
    <li><a href="#">Competitor</a></li>
    <li class="active">Add Competitor</li>
  </ol>
  </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Add Competitor</strong>
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
               <div class="col-md-12">
                  <!-- Contact Form  -->
                  <div id="error-message" class="messages"></div>
                  <div id="success-message" class="messages"></div>
                  <form action="<?php echo base_url('admin/Add_competitor/saveCompetitor'); ?>" method="POST">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="city">Company Id<span class="required"></span></label>
                              <input name="company_id[]" type="text" id="tag" class="form-control" value="<?php echo (isset($get_product) && !empty($get_product)) ? $get_product['company_id'] : ''; ?>" readonly>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-group">
                              <label for="city">Select Competitor<span class="required"></span></label>
                              <select name="cometitor_id[]" class="form-control" id="article" >
                                 <option selected disabled>--Select Competitor--</option>
                                 
                                 <?php if(isset($get_series) && !empty($get_series)){foreach ($get_series as $key => $value) {
                                   # code...
                                                            
                                        ?>
                                 <option value="<?php echo $value['id']; ?>">
                                    <?php echo $value['company_name']; ?>
                                 </option>
                                 <?php }}
                                     ?>          
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2" >
                           <button class="btn btn-success" id="add" type="submit" style="margin-top: 18%;">Add</button>  
                        </div>
                        <div class="card mt-5 pt-5 catag col-md-12">
                           <div class="card-header">
                              <i class="fa fa-tags"></i> Selected Tags
                           </div>
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table class="table table-bordered" width="100%" cellspacing="0" id="tabtag">
                                    <thead>
                                       <tr>
                                          <th>Competitor id</th>
                                          <th>Company id</th>
                                       </tr>
                                    </thead>
                                    <tfoot>
                                       <tr>
                                         <th>Competitor id</th>
                                          <th>Company id</th>
                                       </tr>
                                    </tfoot>
                                    <tbody id="load-table">         
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="text-center">
                        <!--  <input type="hidden" name="user_id"
                           value="<?php echo (isset($edit) && !empty($edit)) ? $edit['user_id'] : ''; ?>"> -->
                        <a href="<?php echo base_url('admin/Company/index'); ?>" class="btn btn-3d btn-red text-center">
                        <i class="fa fa-backward"></i> Back
                        </a>
                        <button type="reset" class="btn btn-3d btn-default text-center">
                        <i class="fa fa-redo"></i> Reset
                        </button>
                        <button type="submit" id="save-button"  class="btn btn-3d btn-green text-center">
                        <i class="fa fa-save"></i> Submit
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section id="middle">
    <div id="content" class="dashboard padding-20">
        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>Competitor List</strong>
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
                    <table id="example" class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <!-- <th scope="col" class="textt-center">Vehicle Make</th> -->
                                <th scope="col" class="text-center">Company Name</th>
                                <th scope="col" class="text-center">Competitor Name</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // print_r($get_seriesdetails);
                                if (isset($get_seriesdetails) && !empty($get_seriesdetails)) {
                                     $i = 0;
                                foreach ($get_seriesdetails as $value ) {
                                    $i++;
                            ?>
                           <tr>
                            <td><?php echo $i; ?></td> 
                            <td><?php echo $value['c_name']; ?></td> 
                            <td><?php echo $value['company_name']; ?></td>
                            
                            <td>
                               <a onclick="return confirm('Do you really want to delete this record ?')" href="<?php echo ADMIN_DELETE_ASERIES_URL. $value['cc_id']; ?>"
                                        class="btn btn-danger" title="Edit"><i class="fas fa-trash-alt"
                                            style="padding-right: 0;"></i></a>
                            </td>
                           </tr>

                       <?php }}  ?>
                        </tbody>
                    </table>
                    <iframe id="dummyFrame" style="display:none"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
   $(document).ready(function() {   
   
     $(".catag").hide();
     $("#tabtag").hide();
   
     $('#add').click(function(event) {
       event.preventDefault();
   
       var article = $("#article").val();
       var tag = $("#tag").val();
       if (article == null) {
         alert('Please Select series');
         return false;
       }
   
       // console.log(article);
       // console.log(tag);
   
       $.ajax({
         type:'POST',
         url:'<?php echo ADMIN_ADD_SERIES_URL ?>',
         data:{article:article,tag:tag},
         success:function(data){
   
           if (data == 0) {
             alert('Please Select Article');
             return false;
           }
   
            $(".catag").show();
            $("#tabtag").show();
         
           $("#load-table").append("<tr>" +
             "<td>" + article +"<input type='hidden' class='articl-insert' id='order' name='send_model_id[]' value='"+ article + "'></td>" +
             "<td>" + tag +"<input type='hidden' class='tag-insert' id='order' name='company_id[]' value='"+ tag + "'></td>" +
             "</tr>"
                 
                 );
             insertdata(article,tag);
             }
   
           });
   
       });
   
     
   
   });  
</script>