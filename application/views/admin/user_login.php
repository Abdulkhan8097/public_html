<?php $this->load->view('admin/header'); ?>

<!--  MIDDLE  -->
<section id="middle">
  <!-- page title -->
  <header id="page-header">
  <h1><i class="fa fa-user"></i> User Management</h1>
  <ol class="breadcrumb">
    <li><a href="#">User</a></li>
    <li class="active">User List</li>
  </ol>
  </header>
    <div id="content" class="dashboard padding-20">

        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>user list</strong>
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
                        <a href="<?php echo base_url('admin/User/get_excel_report/'); ?>" class="btn btn-primary btn-sm ">
                            <i class="fa fa-plus"></i>Export to Excel
                        </a>
                    </li>
                     
                </ul>

            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">User Name</th>
                                <th scope="col" class="text-center">User Email</th>
                              
                            
                                <th scope="col" class="text-center">Contact No.</th>
                             
                               

                                
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
                           
                            <td><?php echo $value['first_name']; ?></td>
                            <td><?php echo $value['email']; ?></td>
                          
                            
                            <td><?php echo $value['phone']; ?></td>
                            
                            
 
                            
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



</div>
<?php $this->load->view('admin/footer'); ?>



