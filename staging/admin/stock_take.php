<?php 
ob_start();
session_start();
include ("../_init.php");

// Redirect, If user is not logged in
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}

// Redirect, If User has not Read Permission
if (user_group_id() != 1 && !has_permission('access', 'read_stock_alert')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('stock_take_value'));

// Add Script
// $document->addScript('../assets/itsolution24/angular/modals/PurchaseProductModal.js');
$document->addScript('../assets/itsolution24/angular/controllers/StockAlertController.js');

// Include Header and Footer
include ("header.php") ;
include ("left_sidebar.php") ; 

$link = mysqli_connect("localhost", "robymart", "robymart", "robymart");
if(isset($_POST["submit"])){
$quantity = $_POST["quantity"];
$quantity = number_format((float)$quantity, 4, '.', '');
$sql = "UPDATE `product_to_store` SET `quantity_in_stock`='".$quantity."' WHERE `product_id` = '".$_POST["product_id"]."'";

if ($link->query($sql) === TRUE) {
echo "
    <script type= 'text/javascript'>
        alert('Record Updated Successfully');
    </script>";
} 
else 
{
    echo 
    "<script type= 'text/javascript'>
        alert('Error: " . $sql . "<br>" . $link->error."');
    </script>";
}
}
//insert discrepancy records
if(isset($_POST["submit_discrepancy"])){
$quantity = $_POST["quantity"];
$quantity = number_format((float)$quantity, 4, '.', '');
$date_created = time();
$sql = "INSERT INTO `stock_take_discrepancy` (`id`, `product_id`, `quantity_in_system`, `quantity_in_stock`, `date_created`) VALUES (NULL, '".$_POST["product_id"]."', '".$_POST["quantity_in_system"]."', '".$quantity."','".$date_created."')"; 

if ($link->query($sql) === TRUE) {
echo "
    <script type= 'text/javascript'>
        alert('Record Created Successfully');
    </script>";
} 
else 
{
    echo 
    "<script type= 'text/javascript'>
        alert('Error: " . $sql . "<br>" . $link->error."');
    </script>";
}
}


?>

<!-- Content Wrapper Start -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.css">
<div class="content-wrapper" ng-controller="StockAlertController">

  <!-- Header Content Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('stock_take_value'); ?>
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
        <a href="product.php"><?php echo trans('text_products'); ?></a>  
      </li>
      <li class="active">
        <?php echo trans('stock_take_value'); ?>
      </li>
    </ol>
  </section>
  <!-- Header Content End -->

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
        <div class="box box-warning">
          <div class="box-header">
            <h3 class="box-title">
              <?php echo trans('stock_take_value'); ?>
            </h3>
            <br>
            <p style="font-size: 20px;">Check out <a href="http://34.232.46.231/modernpos/admin/stock_discrepancy.php">Stock Take Report</a>.</p>
          </div>
          <div class="box-body">
              <?php
              $sql = "select products.p_name, products.p_id, product_to_store.id, product_to_store.quantity_in_stock from products inner join product_to_store on products.P_id = product_to_store.product_id";
              $result = mysqli_query($link, $sql);
              ?>
            <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity In Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($row = mysqli_fetch_array($result)){
        echo "<tr>";
             echo "<td>" . $row['p_name'] . "</td>";
             echo "<td>" . $row['quantity_in_stock'] . "</td>";
             echo"<td><button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal". $row['p_id'] ."'>Edit Quantity</button> <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#myModal2". $row['p_id'] ."'>Record Discrepancy</button></td>";
        echo "</tr>";
        
       echo'
<div id="myModal'. $row['p_id'] .'" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <h5>Edit Quantity</h5>
      <hr>
      <form action="" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Stock Quantity</label>
    <input type="number" class="form-control" id="exampleInputEmail1" name="quantity">
    <input type="text" id="input" value="' .$row['p_id'] .'" hidden="true" name="product_id">
  </div>
  <button type="submit" value=" Submit Details " name="submit" class="btn btn-primary">Save</button>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>';

//discrepancy modal

 echo'
<div id="myModal2'. $row['p_id'] .'" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <h5>Insert Current Quantity</h5>
      <hr>
      <form action="" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Stock Quantity</label>
    <input type="number" class="form-control" id="exampleInputEmail1" name="quantity">
    <input type="text" id="input" value="' .$row['p_id'] .'" hidden="true" name="product_id">
     <input type="text" id="input" value="' .$row['quantity_in_stock'] .'" hidden="true" name="quantity_in_system">
  </div>
  <button type="submit" value=" Submit Details " name="submit_discrepancy" class="btn btn-primary">Save</button>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>';
        }
    ?>
    </tbody>
</table>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.js"></script>
<script>
    $(document).ready( function () {
    $('#table_id').DataTable();
} );

function copyText(){
   document.getElementById("output").value=document.getElementById("input").value;
}
</script>

<?php include ("footer.php"); ?>