<?php $this->load->view('admin/header'); ?>
<style type="text/css">
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 150px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    margin-left: auto;
    margin-right: auto;
}

/* Modal Content */
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 50%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

.modal-body {padding: 30px 30px;}

.modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}
</style>
<!--  MIDDLE  -->
<section id="middle">
  <!-- page title -->
  <header id="page-header">
  <h1><i class="fa fa-user"></i> Redeem Management</h1>
  <ol class="breadcrumb">
    <li><a href="#">Redeem</a></li>
    <li class="active">Redeem List</li>
  </ol>
  </header>
    <div id="content" class="dashboard padding-20">

        <div id="panel-2" class="panel panel-default">
            <div class="panel-heading">
                <span class="title elipsis">
                    <strong>Redeem list</strong>
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




<!-- The Modal1 -->
<div id="myModal1" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    
    <div class="modal-body">
   <div class="form-group">
  <label for="exampleFormControlTextarea2">Redeem Details</label>
  <textarea class="form-control rounded-0" name="redeem_details" id="exampleFormControlTextarea2" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-success">Submit</button>
    </div>
    
  </div>

</div>


                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-hover text-center example" financer_name>
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Redeem Request Date</th>
                                <th scope="col" class="text-center">Company</th>
                                <th scope="col" class="text-center">Redeem Requested Points</th>
                                <th scope="col" class="text-center">Redeem Status</th>
                                <th scope="col" class="text-center">Redeemed Date</th>
                               
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                if (isset($user) && !empty($user)) {
                                     $i = 0;
                                foreach ($user as $key => $value ) {
                                    $i++;
                            ?>
                           <tr>
                            <td><?php echo $i; ?></td>
                            
                            <td><?php echo $value['redeem_request_datetime']; ?></td>
                            <td><?php echo $value['c_name']; ?></td>
                            <td><?php echo $value['redeem_request_points']; ?></td>
                            <td><?php echo $value['redeem_status']; ?></td>
                            <td><?php echo $value['redeemed_datetime']; ?></td>

                             
                            
							<td>
								 <?php if($value['redeem_status']=='No'){ ?>
								   <a href="<?php echo base_url('admin/Redeem_request/edit/').$value['redeem_request_id'];?>" 
								   class="btn btn-success "><i class="fa fa-plus"
								   style="padding-right: 0;"> Redeem Now</i></a>

								   <a style="" href=""
								   class="btn btn-warning" disabled><i class="fa fa-eye"
								   style="padding-right: 0;"> Redeem Details</i></a>
                                
                              <?php }else { ?>
                                
                                   
								   <a
								   class="btn btn-success"disabled><i class="fa fa-plus"
								   style="padding-right: 0;"> Redeem Now</i></a>

								   <a style="" href="<?php echo base_url('admin/Detail/index/').$value['redeem_request_id'];?>"
								   class="btn btn-warning" ><i class="fa fa-eye"
								   style="padding-right: 0;"> Redeem Details</i></a>
                                 
                              <?php } ?>
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



</div>
<?php $this->load->view('admin/footer'); ?>
<script type="text/javascript">
                     function statusChange(user_id,status)
                    {
                      $.ajax({
                          type: "POST",
                          url: "<?php echo ADMIN_USER_UPDATE_STATUS_URL; ?>",
                          data: {user_id:user_id,status:status},
                          cache: false,
                          success:function(responseData)
                          {            
                             location.reload();
                          }   
                      });
                    }
</script>


<script type="text/javascript">
	$(document).ready(function(){


	$('.popUpBtn').on('click', function(){
		$('#'+$(this).data('modal')).css('display','block');
	})


	$('span.close').on('click', function(){
		$('.modal').css('display','none');
	})


	$(window).on('click', function(event){
		if (jQuery.inArray( event.target, $('.modal') ) != "-1") {
        	$('.modal').css('display','none');
    	}
	})



})






// Get the modal
// var modal = document.getElementById('myModal');

// Get the button that opens the modal
// var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
// btn.onclick = function() {
//     modal.style.display = "block";
// }

// When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//     modal.style.display = "none";
// }

// When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//     if (event.target == modal) {
//         modal.style.display = "none";
//     }
// }


$(document).ready(function(){


	$('.popUpBtn').on('click', function(){
		$('#'+$(this).data('modal')).css('display','block');
	})


	$('span.close').on('click', function(){
		$('.modal').css('display','none');
	})


	$(window).on('click', function(event){
		if (jQuery.inArray( event.target, $('.modal') ) != "-1") {
        	$('.modal').css('display','none');
    	}
	})



})
</script>


