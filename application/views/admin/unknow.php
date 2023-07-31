<?php include 'includes/header.php'; ?>
<?php include 'config.php'; ?>
<style type="text/css">
  #success-message,
#error-message{
  background: #DEF1D8;
  color: green;
  font-size: 20px;
  padding: 20px;
  margin: 15px;
  display: none;
  position: fixed;
  right: 10px;
  top: 20%;
  z-index: 20;
}
#error-message{
  background: #EFDCDD;
  color: red;
}
</style>
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Manage Article Tags</li>
    </ol>
    <!-- Contact Form  -->
    <div id="error-message" class="messages"></div>
    <div id="success-message" class="messages"></div>
      <form class="needs-validation" id="" method="POST" novalidate>
        <div class="form-row">
          <div class="col-md-6 mb-3">
            <label for="validationCustom01">Select article</label>
            <select name="article" id="article" class="form-control">
              <option value="0" disabled="" selected="">Choose Article</option>
              <?php
                $sql = "SELECT * FROM artical";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['article_id']."'>".$row['article_title']."</option>\n";
                  }
                } else {
                  echo "0 results";
                }
                ?>
            </select>
            <div class="invalid-feedback">
            Please choose a Category name.
          </div>
        </div>
      </div>
      <div class="form-row">
          <div class="col-md-6 mb-3">
            <label for="validationCustom01">Select tag</label>
            <select name="tag" id="tag" class="form-control">
              <option value="0" disabled="" selected="">Choose Tag</option>
              <?php
                $sql = "SELECT * FROM tag_master";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['tag_id']."'>".$row['tag_name']."</option>\n";
                  }
                } else {
                  echo "0 results";
                }
                ?>
            </select>
            <div class="invalid-feedback">
            Please choose a Category name.
          </div>
        </div>
        <div class="col-md-2" >
            <button class="btn btn-success" id="add" type="submit" style="margin-top: 18%;">Add</button>  
        </div>        
      </div>
      <div class="card mb-3 catag">
        <div class="card-header">
          <i class="fa fa-tags"></i> Selected Tags
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0" id="tabtag">
              <thead>
                <tr>
                  <th>artical id</th>
                  <th>Tag id</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>artical id</th>
                  <th>Tag id</th>  
                </tr>
              </tfoot>
              <tbody id="load-table">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <br>
      <button class="btn btn-primary" id="save-button" type="submit">Add Data</button>
    </form><br>
    
  </div>
</div>
<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    }, false);
  })();
  </script>
<?php include 'includes/footer.php'; ?>

<script type="text/javascript">
  $(document).ready(function() {


     function message(message,status){
          if (status == true) {
            $("#success-message").html(message).slideDown();
            $("#error-message").slideUp();
            setTimeout(function(){
              $("#success-message").slideUp();
            },2000);

          }else if(status == false){
            $("#error-message").html(message).slideDown();
            $("#success-message").slideUp();
            setTimeout(function(){
              $("#error-message").slideUp();
            },2000);
          }
        }

    $(".catag").hide();
    $("#tabtag").hide();

    $('#add').click(function(event) {
      event.preventDefault();

      var article = $("#article").val();
      var tag = $("#tag").val();
      if (tag == null) {
        alert('Please Select tag');
        return false;
      }

      // console.log(article);
      // console.log(tag);

      $.ajax({
        type:'POST',
        url:'php/articaltag.php',
        data:{article:article,tag:tag},
        success:function(data){

          if (data == 00) {
            alert('Please Select Article');
            return false;
          }

           $(".catag").show();
           $("#tabtag").show();
          
          
          $("#load-table").append("<tr>" +
            "<td>" + data +"<input type='hidden' class='articl-insert' id='order' value='"+ data + "'></td>" +
            "<td>" + tag +"<input type='hidden' class='tag-insert' id='order' value='"+ tag + "'></td>" +
            "</tr>"

                
                );
            insertdata(data,tag);
            }

          });

      });

    function insertdata(data,tag){

      $('#save-button').click(function(event) {
        event.preventDefault();
        
        // console.log(data);
        // console.log(tag);

        $.ajax({
          url : 'php/add_articaltag.php',
          type : "POST",
          data:{data:data,tag:tag},
          success : function(data){
            if (data) {
              alert("Article Tags Add  SuccessFully");
              setTimeout(function()
                {
                  location.reload();
                }, 2000);
            }else{
              alert("Data Not Inserted");
            }
            
          }
        });
      });
    }

  });
  
</script>


