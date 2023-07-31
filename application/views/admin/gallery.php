<?php $this->load->view('admin/header'); ?>
<!--  MIDDLE  -->
<section id="middle">
   <header id="page-header">
      <h1><i class="fa fa-user"></i> Gallery Management</h1>
      <ol class="breadcrumb">
         <li><a href="#">Gallery List</a></li>
         <li class="active"> Add Gallery</li>
      </ol>
   </header>
   <div id="content" class="dashboard padding-20">
      <div id="panel-1" class="panel panel-default">
         <div class="panel-heading">
            <span class="title elipsis">
               <strong>ADD Gallery</strong>
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
               <form action="<?php echo base_url('admin/Gallery/save_gallery/') ?>" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                     <div class="form-group">
                              <label for="city">Gallery Category name<span class="required"></span></label>
                              <select name="g_category_name" class="form-control" id="article">
                                 <option selected disabled><---Select Gallery Category name---></option>
                                  <?php if(isset($list1) && !empty($list1)){
                           foreach ($list1 as $key => $value) {
                               ?>
                        <option value="<?php echo $value['g_category_id']; ?>" <?php echo (isset($edit) && !empty($edit) && $edit['g_category_id']==$value['g_category_id']) ? 'selected' : ''; ?>>
                           <?php echo $value['g_category_name']; ?>
                        </option>
                        <?php }
                           } ?>   
                                
                                 
                                        
                              </select>
                           </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                            <label for="title">Gallery Subcategory name</label>
                            <select name="g_subcategory_name" class="form-control">
                             <option><?php echo (isset($edit) && !empty($edit)) ? $edit['g_subcategory_id'] : ''; ?></option>
                            </select>
                            </div> 
                  </div>


                  <div class="col-md-6">
                     <label for="chkPassport">Gallery Type
                        <div class="form-check">
                  <input class="form-check-input" id="chkPassport1" type="radio" value="image" name="g_type" >
                  <label  class="form-check-label image" for="flexRadioDefault2">Image</label>
                  <input class="form-check-input" id="chkPassport" type="radio" value="video" name="g_type" >
                    <label class="form-check-label video"  for="flexRadioDefault1">Video</label>
                  </div>
                     
                  </div>

                  <div class="col-md-6">
                           <div id="dvPassport" class="form-group" style="display: none">
                          <label for="exampleFormControlTextarea2">Gallery Youtube</label>
                          <textarea name="g_youtube" class="form-control rounded-0"  rows="3"><?php echo (isset($edit) && !empty($edit)) ? $edit['g_youtube'] : ''; ?></textarea>
                        </div>
                  </div>
                  


                  <div class="col-md-6">
                     <div id="dvPassport1" class="form-group" style="display: none">
                           <div class="col-md-8">
                              <div class="form-group" >
                                 <label for="customer_image">Gallery Image<span class="required"></span></label>
                                 <input type="file" name="g_image" onchange="display_img(this);" class="form-control" id="customer_image" >
                              </div>
                           </div>
                           <div class="col-md-4">
                              <img  src="<?php echo (isset($edit['g_image']) && !empty($edit['g_image'])) ? PROFILE_DISPLAY_PATH_NAME.$edit['g_image'] : BLANK_IMG; ?>" id="display_image_here" style="height: 100px; width: 100px; border: 2px solid gray; border-radius: 50%;" >
                           </div>
                        </div>
                  </div>




                  

                  <div class="col-md-6">
                     <div id="AddPassport1">
   
                      </div>
                  </div>

                  <div class="col-md-6">
                     <div id="AddPassport">
   
                      </div>
                  </div>



                                    <div class="col-md-6">
                     

                     <div class="form-group">
                              <label for="city">Gallery Sequence<span class="required"></span></label>
                              <select name="g_seq"  class="form-control" id="article">
                                 <option selected disabled><---Select Gallery Sequence---></option>
                                 
                                 <option value="1" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='1') ? 'selected' : ''; ?>>1</option>
                                 <option value="2" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='2') ? 'selected' : ''; ?>>2</option>
                                 <option value="3" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='3') ? 'selected' : ''; ?>>3</option>
                                 <option value="4" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='4') ? 'selected' : ''; ?>>4</option>
                                 <option value="5" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='5') ? 'selected' : ''; ?>>5</option>
                                 <option value="6" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='6') ? 'selected' : ''; ?>>6</option>
                                 <option value="7" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='7') ? 'selected' : ''; ?>>7</option>
                                 <option value="8" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='8') ? 'selected' : ''; ?>>8</option>
                                 <option value="9" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='9') ? 'selected' : ''; ?>>9</option>
                                 <option value="10" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='10') ? 'selected' : ''; ?>>10</option>
                                 <option value="11" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='11') ? 'selected' : ''; ?>>11</option>
                                 <option value="12" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='12') ? 'selected' : ''; ?>>12</option>
                                 <option value="13" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='13') ? 'selected' : ''; ?>>13</option>
                                 <option value="14" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='14') ? 'selected' : ''; ?>>14</option>
                                 <option value="15" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='15') ? 'selected' : ''; ?>>15</option>
                                 <option value="16" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='16') ? 'selected' : ''; ?>>16</option>
                                 <option value="17" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='17') ? 'selected' : ''; ?>>17</option>
                                 <option value="18" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='18') ? 'selected' : ''; ?>>18</option>
                                 <option value="19" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='19') ? 'selected' : ''; ?>>19</option>
                                 <option value="20" <?php echo (isset($edit) && !empty($edit) && $edit['g_seq']=='20') ? 'selected' : ''; ?>>20</option>

                                        
                              </select>
                           </div>
                  </div>


                  <div class="col-md-6">
                     <input type="hidden" name="gallery_id" value=" <?php echo (isset($edit) &&!empty($edit))? $edit['gallery_id'] : ''; ?>">
                  </div>

                
                   
            </div>
            <div class="text-center" style="margin-top: 30px;">
            <!-- <input type="hidden" name="g_subcategory_id" value="<?php echo (isset($edit) && !empty($edit)) ? $edit['g_subcategory_id'] : ''; ?>"> -->
            
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
<strong>Gallery LIST</strong>
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
</li>
</ul>
</div>
<div class="panel-body">
<div class="table-responsive">
<table class="table table-bordered table-hover text-center example" financer_name>
<thead>
<tr>
<th scope="col" class="text-center">#</th>
<th scope="col" class="text-center">Gallery Category Name</th>
<th scope="col" class="text-center">Gallery Subcategory Name</th>
<th scope="col" class="text-center">Gallery Sequence</th>
<th scope="col" class="text-center">Gallery Type</th>
<th scope="col" class="text-center">Gallery Image</th>
<th scope="col" class="text-center">Gallery Video</th>

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
<td><?php echo $value['g_category_name']; ?></td>
<td><?php echo $value['g_subcategory_name']; ?></td>
<td><?php echo $value['g_seq']; ?></td>
<td><?php echo $value['g_type']; ?></td>

<td><img class=" user-avatar" alt="" src="<?php echo (isset($value['g_image'])) ? PROFILE_DISPLAY_PATH_NAME . $value['g_image'] : BLANK_IMG; ?>" height="70px;"></td>
<td style="height: 100px !important;width: 50px !important;"><?php echo $value['g_youtube']; ?></td>
<td>
   <a href="<?php echo base_url('admin/gallery/index/') . $value['gallery_id']; ?>" class="btn btn-primary" title="Edit"><i class="fas fa-edit" style="padding-right: 0;"></i></a>
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


<script type="text/javascript">


    $(document).ready(function() {
        $('select[name="g_category_name"]').on('change', function() {
            var stateID = $(this).val();
            if(stateID) {
                $.ajax({
                    url: '/admin/Gallery/myformajax/'+stateID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                         $('select[name="g_subcategory_name"]').empty();
                        $.each(data, function(key, value) {
      $('select[name="g_subcategory_name"]').append('<option value="'+ value.g_category_id +'">'+ value.g_subcategory_name +'</option>');
       
                        });
                    }
                });
            }else{
                $('select[name="g_subcategory_name"]').empty();
            }
        });
    });
</script>

<script type="text/javascript">
   $(function () {
        $("#chkPassport").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
                $("#AddPassport").hide();
            } else {
                $("#dvPassport").hide();
                $("#AddPassport").show();
            }
        });
    });
</script>

<script type="text/javascript">
   $(function () {
        $("#chkPassport1").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport1").show();
                $("#AddPassport1").hide();
            } else {
                $("#dvPassport1").hide();
                $("#AddPassport1").show();
            }
        });
    });
</script>


<script type="text/javascript">
   $(function () {
        $("#chkPassport1").click(function () {
            if ($(this).is(":checked")) {
                $("#AddPassport").show();
                $("#dvPassport").hide();
            } else {
                $("#AddPassport").hide();
                $("#dvPassport").show();
            }
        });
    });
</script>

<script type="text/javascript">
   $(function () {
        $("#chkPassport").click(function () {
            if ($(this).is(":checked")) {
                $("#AddPassport1").show();
                $("#dvPassport1").hide();
            } else {
                $("#AddPassport1").hide();
                $("#dvPassport1").show();
            }
        });
    });
</script>