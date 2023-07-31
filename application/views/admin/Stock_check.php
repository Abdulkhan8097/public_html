
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
 
         
           
 
</section>

<section id="middle">
   <div id="content" class="dashboard padding-20">
      <div id="panel-2" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>Stock List</strong>
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
                   <li>
                      <a href="<?php echo ADMIN_Stock_Adjustment_LIST_URL; ?>" class="btn btn-primary btn-sm "> 
                            <i class="fas fa-backward"></i>BACK
                        </a>
                    </li>
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
                        <!-- <th scope="col" class="text-center">Action</th> -->
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        if (isset($idby) && !empty($idby)) {
                             $i = 0;
                        foreach ($idby as $key => $value ) {
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<!-- checked in end -->



<!-- checkbox radio -->
