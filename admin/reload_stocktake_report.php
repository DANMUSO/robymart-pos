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
if (user_group_id() != 1 && !has_permission('access', 'read_sell_report')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

// Set Document Title
$document->setTitle(trans('stock_take_value'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/ReportSellItemWiseController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.css">
<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ReportSellItemWiseController">
  <script>
    function add_number() {
      var first_number = parseFloat(document.getElementById("inhouse").value);
      if (isNaN(first_number)) first_number = 0;
      var second_number = parseFloat(document.getElementById("sahodaya").value);
      if (isNaN(second_number)) second_number = 0;
      var result = first_number + second_number;
      document.getElementById("total").value = result;
    }
  </script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  <!-- Content Header Start -->
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
  <!-- Content Header End -->

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
              <?php echo trans('stock_take_value'); ?>
            </h3>
            <br>
            <p style="font-size: 20px;">Check out <a href="http://34.232.46.231/modernpos/admin/stock_discrepancy.php">Stock Take Report</a>.</p>
          </div>
          <div class="box-body">
            <div class="table-responsive">  
              <?php
                  $print_columns = '0,1,2,3,4,5';
                  if (user_group_id() != 1) {
                    if (! has_permission('access', 'show_purchase_price')) {
                      $print_columns = str_replace('4,', '', $print_columns);
                    }
                  }
                  $hide_colums = "4,";
                  if (user_group_id() != 1) {
                    if (!has_permission('access', 'show_purchase_price')) {
                      $hide_colums .= "4,";
                    }
                  }
                 
                ?>
                <center>
                    
                </center> 
                <div class="row" style="background-color:#f0f4f4; height:70px;" >
                 <form action="stock_take.php" method="post">
        <div class="col-md-4">
         </div>
          <div class="col-md-2">
            
              <label>Category</label>
             <select name="category_id">
           <?php 
             
                  $categories = "SELECT category_id, category_name FROM categorys";
                  $categoryID = $link->query($categories);
            while($cate = $categoryID->fetch_assoc()) {
                  echo "<option value=".$cate['category_id'].">".$cate['category_name']."</option>";
              }
            ?>        
            </select>
            
         </div>
          <div class="col-md-1">
             <label style="color:#f0f4f4">.........</label><br>
             <button name="search" class="btn btn-primary">
                 Search
             </button>
         </div>
        
             </form>
             </div>
             <br>
                            <br>
            <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Current Quantity</th>
                <th>Physical Quantity</th>
                <th>Variance</th>
                <th>Adjusted Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            
  <div class="container my-3">
  
                                         <?php
$dow   = 'Saturday';
$step  = 1;
$unit  = 'W';

$start = new DateTime('2022-06-25');
$end   = clone $start;

$start->modify($dow); // Move to first occurence
$end->add(new DateInterval('P1Y')); // Move to 1 year from start

$interval = new DateInterval("P{$step}{$unit}");
$period   = new DatePeriod($start, $interval, $end);

foreach ($period as $date) {
    if($date->format('Y-m-d') == date("Y-m-d")){
        $sqli = "UPDATE product_to_store SET p_status ='0'";
        mysqli_query($link, $sqli);
        echo $date->format('Y-m-d');
    }else{
        
    }
    
}

  
if(store('name') == 'Robbymart- Ola'){
    $name = store('name');
    $ola = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($ola);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
     $storeid = $rows['store_id'];
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
                                         
                                                                                           
  if (isset($_POST['takestock'])) {
       $quantity = $_POST['quantity'];
       $product_id = $_POST['productID'];
      
      $sqli = "UPDATE product_to_store SET physical_stock ='$quantity'  WHERE store_id='$storeid' AND product_id ='$product_id' ";
     
if (mysqli_query($link, $sqli)) {
    
      echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal({
            title: "Stock details updated successfully!",
            icon: "success",
            button: "Ok",
            timer: 2000
        });';
  echo '}, 1000);</script>';
  
    $category = "SELECT * FROM products WHERE p_id = '$product_id' ";
    $category_id = $link->query($category);
    if ($category_id->num_rows > 0) {
  // output data of each row
  
  
  while($rows = $category_id->fetch_assoc()) {
      $id = $rows['category_id'];
      
      
  }
    $categories = "SELECT category_id, category_name FROM categorys WHERE category_id = '$id' ";
                  $categoryID = $link->query($categories);
            while($cate = $categoryID->fetch_assoc()) {
                  $category_name = $cate['category_name'];
              }
$sql = "SELECT P.p_id, P.p_name,P.p_code, S.sup_name, P.category_id,P1.physical_stock, P1.quantity_in_stock, S.sup_id, P1.purchase_price, sell_price 
FROM products as P 
INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id
INNER JOIN suppliers as S ON S.sup_id = P1.sup_id

  WHERE P1.store_id = '$storeid' AND P.category_id = '$id'
  ORDER BY P1.quantity_in_stock DESC
";

$no = "SELECT COUNT(P.p_id) AS no FROM products as P INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id WHERE P1.store_id = '$storeid' AND P.category_id = '$id'";
$number = $link->query($no);
$row = $number->fetch_assoc();

echo "<center>". "<button class='btn btn-primary'>". $category_name .'   '."<p style='color:white'>"."Results:" .' '. $row['no'] . "</p>"."</button>"."<center>"."<br>";
$result = $link->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  echo "<tr>";
    echo "<td>" . $row['p_code']. "</td>";
    echo "<td>" . $row['p_name']. "</td>";
    echo "<td>" . number_format((float)$row['quantity_in_stock'], 2, '.', ''). "</td>";
    echo "<form action='stock_take.php' method='POST'>";
    echo "<td>" . '<input type="text" name="quantity" value="'.$row['physical_stock'].'" id="quantity2" class="form-control">' .
    '<input type="hidden" name="productID" onkeyup="add_number()" value="'.$row['p_id'].'" id="productID" class="form-control">' . "</td>";
    echo "<td>" . number_format(round(((float)$row['physical_stock'] - (float)$row['quantity_in_stock'] ) ,2), 2, '.', ','). "</td>";
    echo "<td>" . number_format((float)$row['physical_stock'], 2, '.', '') . "</td>";
     echo "<td>" . "<input type='submit' value='Save' name='takestock' class='btn btn-primary'>" . "</td>";
     echo "</form>";
    echo "</tr>";
    
  }
}
    }
} else {
  echo "Error updating record: " . mysqli_error($link);
}
 

 
  }
 
 if (isset($_POST['search'])) {
                   $category_id = $_POST['category_id'];
   $categories = "SELECT category_id, category_name FROM categorys WHERE category_id = '$category_id' ";
                  $categoryID = $link->query($categories);
            while($cate = $categoryID->fetch_assoc()) {
                  $category_name = $cate['category_name'];
              }
$sql = "SELECT P.p_id, P.p_name,P.p_code, S.sup_name, P.category_id,P1.physical_stock, P1.quantity_in_stock, S.sup_id, P1.purchase_price, sell_price 
FROM products as P 
INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id
INNER JOIN suppliers as S ON S.sup_id = P1.sup_id

  WHERE P.category_id = '$category_id'
  ORDER BY P1.quantity_in_stock DESC
";

$no = "SELECT COUNT(P.p_id) AS no FROM products as P INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id WHERE P1.store_id = '$storeid' AND P.category_id = '$category_id'";
$number = $link->query($no);
$row = $number->fetch_assoc();
echo "<center>". "<button class='btn btn-primary'>". $category_name .'   '."<p style='color:white'>"."Results:" .' '. $row['no'] . "</p>"."</button>"."<center>"."<br>";
$result = $link->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  echo "<tr>";
    echo "<td>" . $row['p_code']. "</td>";
    echo "<td>" . $row['p_name']. "</td>";
    echo "<td>" . number_format((float)$row['quantity_in_stock'], 2, '.', ''). "</td>";
    echo "<form action='stock_take.php' method='POST'>";
    echo "<td>" . '<input type="text" name="quantity" value="'.$row['physical_stock'].'" id="quantity2" class="form-control">' .
    '<input type="hidden" name="productID" onkeyup="add_number()" value="'.$row['p_id'].'" id="productID" class="form-control">' . "</td>";
    echo "<td>" . number_format(round(((float)$row['physical_stock'] - (float)$row['quantity_in_stock'] ) ,2), 2, '.', ','). "</td>";
    echo "<td>" . number_format((float)$row['physical_stock'], 2, '.', '') . "</td>";
     echo "<td>" . "<input type='submit' value='Save' name='takestock' class='btn btn-primary'>" . "</td>";
     echo "</form>";
    echo "</tr>";
    
  }
} 
               } else{   
        
$sql = "SELECT P.p_id, P.p_name,P.p_code, S.sup_name,P1.physical_stock, P1.quantity_in_stock, S.sup_id, P1.purchase_price, sell_price 
FROM products as P 
INNER JOIN product_to_store as P1 ON P.p_id = P1.product_id
INNER JOIN suppliers as S ON S.sup_id = P1.sup_id

  WHERE P1.store_id = '$storeid'
  ORDER BY P1.quantity_in_stock DESC
";

$result = $link->query($sql);
 $Totalpurchase = 0;
 $Totalprice = 0;
 $Totalprofit = 0;
 $Totalrprofit = 0;
  $Totalssprofit = 0;
 $Totalsprofit = 0;
  $Totalsquantity =0; 
  $Totalsunit = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {

    
  }
} else {
  echo "0 results";
}
}
?>
 
        </tbody>
        <tfoot>
             <tr>
               
                <th colspan="6">Total</th>
              
                
            </tr> 
          
        </tfoot>
    </table>
            </div>
          </div>
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
$(document).ready(function() {
  setInterval(function() {
    cache_clear()
  }, 3000);
});

function cache_clear() {
  window.location.reload(true);
  // window.location.reload(); use this if you do not remove cache
}
    </script>
    
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
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
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
  </section>
  <!-- Content End -->

</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>