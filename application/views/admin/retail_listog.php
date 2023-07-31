<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <div id="content" class="dashboard padding-20">
     
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>RETAIL LIST</strong>
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
         <div class="panel-body">
            </ul>
         </div>
            <div class="row">
               <div class="col-md-12">
                  <form action="<?php echo ADMIN_SAVE_RETAIL_URL; ?>" method="POST">
                     <div class="row">
                        
                           <div class="form-group">
                                <div class="col-md-5">
                              <label for="type">Part No<span class="required">*</span></label>
                                 <select name="part_no" class="form-control" id="part_no">
                                            <option selected disabled>--Select Type--</option>
                                             <?php if(isset($product) && !empty($product)){
                                            foreach ($product as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['product_id']; ?>">

                                                     <?php echo $value['eve_part_no']; ?>
                                                </option>
                                           <?php }
                                        } ?>          
                                        </select>

                              
                           </div>

                           <div class="form-group">
                             <div class="col-md-5">
                              <label for="customer_loan_id">Child Part No<span class="required">*</span></label>
                              
                              <select name="part_no1" class="form-control" id="part_no1">

                                            <option selected disabled>--Select Type--</option>
                                             <?php if(isset($product) && !empty($product)){
                                            foreach ($product as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['product_id']; ?>">

                                                     <?php echo $value['eve_part_no']; ?>
                                                </option>
                                           <?php }
                                        } ?>          
                                        </select>
                           </div>
                        </div>

                        <div class="form-group">
                             <div class="col-md-1">
                                <button class="btn btn-success" id="add" type="submit" style="margin-top: 40%;">Add</button>
                            </div>
                        </div>

                       </div>
                     </div>
                     <div class="card mb-3 catag">
        
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0" id="tabtag">
              <thead>
                <tr>
                  <th>Part No</th>
                  <th>Child Part No.</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
               <th>Part No.</th>
                  <th>Child Part No.</th>   
                </tr>
              </tfoot>
              <tbody id="load-table">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
                     <div class="text-center">
                        
                                              
                        <button type="reset" class="btn btn-3d btn-default text-center"><i
                           class="fa fa-redo"></i>
                        Reset</button>
                        <button type="submit" id="save-button" class="btn btn-3d btn-green text-center"><i
                           class="fa fa-save"></i>
                        Submit</button>
                     </div>
                  </form>
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
                    <strong>Retail List</strong>
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
                                <th scope="col" class="text-center">Part no</th>
                                <th scope="col" class="text-center">Child Part no</th>
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
                           
                            <td><?php echo $value['product_id']; ?></td>
                             <td><?php echo $value['child_id']; ?></td>

                            
                           

                            <td>
                                   
                                    <a href="<?php echo ADMIN_DELETE_RETAIL_URL.$value['retail_id']; ?>"
                                        onclick="return confirm('Are you sure to Delete?');"
                                        class="btn btn-danger btn-sm"><i class="fa fa-trash"
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
    $(document).ready(function() {
        $(".catag").hide();
        $("#tabtag").hide();

        $("#add").click(function(event) {
          event.preventDefault();

          var part_no = $("#part_no").val();
          var part_no1 = $("#part_no1").val();
          if (part_no1 == null) {
            alert('Please Select child part no');
            return false;
          } 

          $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_RETAIL_URL ?>',
            data:{part_no:part_no,part_no1:part_no1},
            success:function(data){

              if (data == 00) {
                alert('Please Select Part No.');
                return false;
              }

               $(".catag").show();
               $("#tabtag").show();
              
              
              $("#load-table").append("<tr>" +
                "<td>" + part_no +"<input type='hidden' value='"+ part_no + "'></td>" +
                "<td>" + part_no1 +"<input type='hidden' value='"+ part_no1 + "'></td>" +
                "</tr>"

                    
                    );
               // insertdata(part_no,part_no1);
                }

              });

          });
        
        });

      
    
</script>