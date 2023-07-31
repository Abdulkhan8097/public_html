
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
	<title>login</title>
</head>
<body>
<div class="container mt-5">
  <div class="row"> 
    <div class="col-md-12">
      <table class="table table-striped table-bordered m-top20">
                     <tbody>
                     	<?php  
 							foreach ($detail as $key => $item) {
  
                          ?>
                        <tr>
                           <th scope="row">Redeem 
                           Details</th>
                           <td><?php echo $item['redeem_details']; ?></td>
                      
                        </tr>
                         <?php }; ?>
                       
                        
                     </tbody>
                  </table>
                   <a href="<?php echo base_url('admin/Redeem_request/index/'); ?>" class="btn btn-warning w-100" style="width:100%;">Cancel</a>
    </div>
  </div>
</div>
		
</body>
</html>