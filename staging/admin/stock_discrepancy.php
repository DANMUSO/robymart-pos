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
               <p style="font-size: 20px;">Back to <a href="http://34.232.46.231/modernpos/admin/stock_take.php">Stock Take</a>.</p>
          
            </h3>
          </div>
          <div class="box-body">
              <?php
              $sql = "select products.p_name, products.p_id, stock_take_discrepancy.quantity_in_system, stock_take_discrepancy.quantity_in_stock, stock_take_discrepancy.date_created from products inner join stock_take_discrepancy on products.P_id = stock_take_discrepancy.product_id";
              $result = mysqli_query($link, $sql);
              ?>
            <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity In System</th>
            <th>Quantity In Stock</th>
            <th>Date Created</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($row = mysqli_fetch_array($result)){
        echo "<tr>";
             echo "<td>" . $row['p_name'] . "</td>";
             echo "<td>" . $row['quantity_in_system'] . "</td>";
             echo "<td>" . $row['quantity_in_stock'] . "</td>";
             echo "<td>" . gmdate("F j, Y, g:i a", $row['date_created']). "</td>";
            
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