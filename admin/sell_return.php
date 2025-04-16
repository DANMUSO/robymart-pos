<?php 
ob_start();
session_start();
include ("../_init.php");
include ("config.php");
// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_sell_return')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('title_sell_return'));

// Add Script
$document->addScript('../assets/itsolution24/angular/modals/SellReturnViewModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/SellReturnController.js');

// SIDEBAR COLLAPSE
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;

if(store('name') == 'Robbymart- Ola'){
    $name = store('name');
    $ola = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($ola);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
     $storeid = $rows['store_id'];
     $_SESSION[''] = $storeid;
  }
    }   
  }elseif(store('name') == 'RobbyMart T-Point'){
        $name = store('name');
    $point = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($point);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
      $storeid = $rows['store_id'];
  }
    }
        }
else{
            
    $name = store('name');
    $palace = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($palace);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
      $storeid = $rows['store_id'];
  }
    }
        }
?>
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="SellReturnController">

  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_sell_return_title'); ?>
      <small>
        <?php echo store('name'); ?>
      </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          <?php echo trans('text_dashboard'); ?>
        </a>
      </li>
      <li>
          <?php echo trans('text_return_list_title'); ?>  
      </li>
    </ol>
  </section>
  <!-- Content Header end -->

  <!-- Content Start -->
  <section class="content">

    <?php if(DEMO) : ?>
    <div class="box">
      <div class="box-body">
        <div class="alert alert-info mb-0">
          <p><span class="fa fa-fw fa-info-circle"></span> <?php echo $demo_text; ?></p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('text_sell_return_list_title'); ?>
            </h3>
          </div>
          <div>
         
          </div>
          <div class='box-body'> 
          
         <?php 
         if(limit_char($user->getRole(), 14) == "Cashier" ){
  
             
         }else{
             ?>
             
                        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
  <i class="fa fa-plus"></i> Sell Return
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Sell Return</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
           <form action="process.php" id="form" method="post">
            <div class="row">
                 <input type="hidden" id="storeid" class="form-control" value='<?php echo $storeid;?>' required name="storeid"><br>
            <div class="col-sm-6">
                
        <div class="form-group">
          <label for="email">Invoice No</label>
          <select name="invoiceno" id="invoiceno" class="form-control" onchange="FetchState(this.value)"  required>
            <option value="">Select Invoice No</option>
          <?php
          $invoice = "SELECT invoice_id FROM selling_item WHERE store_id = '$storeid' GROUP BY invoice_id";
          $invoiceID = $link->query($invoice);
            if ($invoiceID->num_rows > 0 ) {
               while ($row = $invoiceID->fetch_assoc()) {
                echo '<option value='.$row['invoice_id'].'>'.$row['invoice_id'].'</option>';
               }
            }
          ?> 
          </select>
        </div>
        </div>
        <div class="col-sm-6">
        <div class="form-group">
          <label for="pwd">Product Name</label>
          <select name="product_id" id="product_id" class="form-control" onchange="Fetchsupplier(this.value)"  required>
            <option>Select Product Name</option>
          </select>
        </div>
        </div>
        <div class="col-sm-6">

        <div class="form-group">
          <label for="pwd">Supplier</label>
          <select name="supplier_id" id="supplier_id" class="form-control">
          </select>
        </div>
        </div>
        
  <div class="col-sm-6">
   <label for="fname">Units:</label><br>
          <input type="number" id="units" class="form-control" required name="units"><br>
         
  </div>
 
  <div class="col-sm-6">
   <label for="fname">Customer Name:</label><br>
          <input type="text" id="cname" class="form-control" required name="cname"><br>
 </div>

  <div class="col-sm-6">
   <label for="fname">Returned By:</label><br>
          <input type="text" id="return_by" class="form-control" required  name="return_by"><br>
         
  </div>
   <div class="col-sm-6">
   <label for="fname">Note:</label><br>
          <textarea type="text" id="note" class="form-control" required  name="note"></textarea><br>
         
  </div>
 <div class="col-sm-12">
 </div>
  <div class="col-sm-6">
  <button type="submit" name="submit" class="btn btn-primary">Save</button>
  </div>
        </div>
      </form>
    
      </div>
      <div class="modal-footer">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    </div>
  </div>
</div>
<br>


<div class="row" style="background-color:#f0f4f4; height:70px;" >
 <form action="sell_return.php" method="post">
        <div class="col-md-4">
         </div>
         <div class="col-md-2">
             <label>Start Date</label>
             <input type="date" name="startdate" value="<?php if(isset($_POST['search'])) { echo htmlentities ($_POST['startdate']); }?>" required>
         </div>
          <div class="col-md-2">
              <label> End Date</label>
              <input type="date" name="enddate" value="<?php if(isset($_POST['search'])) { echo htmlentities ($_POST['enddate']); }?>" required>
            
         </div>
          <div class="col-md-1">
             <label style="color:#f0f4f4">.........</label>
             <button name="search" class="btn btn-primary">
                 Search
             </button>
         </div>
        
 </form>
     </div>
<br>
            <div class="table-responsive"> 
                 <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                
                <th>Supplier Name</th>
                <th>Product Name</th>
                <th>Customer Name</th>
                <th>Invoice No</th>
                <th>Quantity </th>
                <th>Purchase Price </th>
                <th>Sell Price </th>
                <th>Returned By</th>
                <th>Returned Note</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
              if (isset($_POST['search'])) {
       $startdate = $_POST['startdate'];
       $enddate = $_POST['enddate'];
       $product = "SELECT SP.invoice_no, SP.units, SP.cname, SP.return_by,SP.purchase, SP.sell, SP.return_date, SP.note , S.sup_name, P.p_name  FROM sells_return_p SP 
            INNER JOIN products P ON P.p_id = SP.product_id
            INNER JOIN suppliers S ON SP.sup_id = S.sup_id
            WHERE DATE(SP.return_date) BETWEEN '$startdate' AND '$enddate' AND store_id = '$storeid'";

            $result = $link->query($product);
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['sup_name']. "</td>";
                echo "<td>" . $row['p_name']. "</td>";
                
               echo "<td>" . $row['cname']. "</td>";
                echo "<td>" . $row['invoice_no'] . "</td>";
                echo "<td>" . $row['units']. "</td>";
                echo "<td>" . $row['purchase']. "</td>";
                echo "<td>" . $row['sell']. "</td>";
               echo "<td>" . $row['return_by']. "</td>";
               echo "<td>" . $row['note']. "</td>";
               echo "<td>" . $row['return_date']. "</td>";
                echo "</tr>";
                
              }
            }

 
  }else{
            $product = "SELECT SP.invoice_no, SP.units, SP.cname, SP.return_by,SP.purchase, SP.sell, SP.return_date, SP.note , S.sup_name, P.p_name  FROM sells_return_p SP 
            INNER JOIN products P ON P.p_id = SP.product_id
            INNER JOIN suppliers S ON SP.sup_id = S.sup_id 
            WHERE SP.store_id = '$storeid'
            ";

            $result = $link->query($product);
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['sup_name']. "</td>";
                echo "<td>" . $row['p_name']. "</td>";
                
               echo "<td>" . $row['cname']. "</td>";
                echo "<td>" . $row['invoice_no'] . "</td>";
                echo "<td>" . $row['units']. "</td>";
                echo "<td>" . $row['purchase']. "</td>";
                echo "<td>" . $row['sell']. "</td>";
               echo "<td>" . $row['return_by']. "</td>";
               echo "<td>" . $row['note']. "</td>";
               echo "<td>" . $row['return_date']. "</td>";
                echo "</tr>";
                
              }
            }
  }
            ?>
        </tbody>
        <tfoot>
             <tr>
               
                <th colspan="9">Total</th>
              
                
            </tr> 
          
        </tfoot>
    </table>
              <?php
              $hide_colums = "";
              ?> 
            </div>
          </div>
             <?php
         }
         
         ?> 
          
          
          
          
          
        </div>
      </div>
    </div>
      <script>
     $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

    </script>
    
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>



<script type="text/javascript">
  function FetchState(id){
    $('#product_id').html('');
    $('#supplier').html('<option>Select Supplier</option>');
    $.ajax({
      type:'post',
      url: 'ajaxdata.php',
      data : { country_id : id},
      success : function(data){
         $('#product_id').html(data);
      }

    })
  }

  function Fetchsupplier(id){ 
    $('#supplier_id').html('');
    $.ajax({
      type:'post',
      url: 'ajaxdata.php',
      data : { state_id : id},
      success : function(data){
         $('#supplier_id').html(data);
      }

    })
  }

  $(document).ready(function () {
  $("#form").submit(function (event) {
    var formData = {
      invoiceid: $("#invoiceno").val(),
      productid: $("#product_id").val(),
      supplierid: $("#supplier_id").val(),
      productunit: $("#units").val(),
      productname: $("#cname").val(),
      returnby: $("#return_by").val(),
      storeid: $("#storeid").val(),
      returnnote: $("#note").val(),
    };
Swal.fire({
  title: 'Are you sure?',
  text: "You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, return it!'
}).then((result) => {
  if (result.isConfirmed) {
        $.ajax({
      type: "POST",
      url: "process.php",
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
      console.log(data);
      if(data.exceed){
           Swal.fire(
      'Execution Failed!',
      'Entered value exceed the available quantity.',
      'warning'
    )
      }else{
             Swal.fire(
      'Executed!',
      'This record has been returned successfully.',
      'success'
    )
      }
      
    });
   
  }
});
  

    event.preventDefault();
  });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
 <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script> 
  </section>
  <!-- Content End -->
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>