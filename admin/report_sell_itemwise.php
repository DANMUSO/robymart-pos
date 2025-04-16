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
$document->setTitle(trans('title_sell_report'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/ReportSellItemWiseController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.css">
<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ReportSellItemWiseController">
  
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_selling_report_title'); ?>
      <small>
        <?php echo store('name');?>
      </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="dashboard.php">
          <i class="fa fa-dashboard"></i>
           <?php echo trans('text_dashboard'); ?>
         </a>
       </li>
      <li class="active">
        <?php echo trans('text_selling_report_title'); ?>
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
              <?php echo trans('text_selling_report_sub_title'); ?>
            </h3>
            <div class="box-tools pull-right">
              
                <div class="btn-group">
                  <button type="button" class="btn btn-info">
                    <span class="fa fa-filter"></span> 
                    <?php if (current_nav() == 'report_sell_itemwise') : ?>
                      <?php echo trans('button_itemwise'); ?>
                    <?php elseif (current_nav() == 'report_sell_categorywise') : ?>
                      <?php echo trans('button_categorywise'); ?>
                    <?php elseif (current_nav() == 'report_sell_supplierwise') : ?>
                      <?php echo trans('button_supplierwise'); ?>
                    <?php else: ?>
                      <?php echo trans('button_filter'); ?>
                    <?php endif; ?>
                  </button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                      <li>
                        <a href="report_sell_itemwise.php">
                          <?php echo trans('button_itemwise'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="report_sell_categorywise.php">
                          <?php echo trans('button_categorywise'); ?>
                        </a>
                      </li>
                      <li>
                        <a href="report_sell_supplierwise.php">
                          <?php echo trans('button_supplierwise'); ?>
                        </a>
                      </li>
                   </ul>
                </div>

            </div>
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
 <div class="row" style="background-color:#f0f4f4; height:70px;" >
 <form action="report_sell_itemwise.php" method="post">
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
     </div></center>  <br>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Serial Number</th>
                <th>Date</th>
                <th>Invoice No</th>
                <th>Product Name</th>
                <th>Supplier Name</th>
                <th>Quantity</th>
                <th>Purchase Price per Unit</th>
                <th>Selling  Price per Unit</th>
                <th>Purchasing Price</th>
                <th>Selling Price</th>
                 <th>Profit Margin</th>
                 <th>Retailer Margin</th>
                <th>Supplier Profit</th>
                <th>Total Supplier Amount</th>
            </tr>
        </thead>
        <tbody>
                                         <?php
if(store('name') == "Robbymart- Ola"){
    $name = store('name');
    $ola = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($ola);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
     $id = $rows['store_id'];
  }
    }   
  }elseif(store('name') == "RobbyMart T-Point"){
        $name = store('name');
    $point = "SELECT store_id FROM stores WHERE name = '$name' ";
    $store_id = $link->query($point);
    if ($store_id->num_rows > 0) {
  // output data of each row
  while($rows = $store_id->fetch_assoc()) {
      $id = $rows['store_id'];
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
      $id = $rows['store_id'];
  }
    }
        }
        
        
  if (isset($_POST['search'])) {
       $startdate = $_POST['startdate'];
       $enddate = $_POST['enddate'];
       $sql = "SELECT S.id, S.item_total, S.invoice_id, Ps.purchase_price, S2.created_at, S.item_quantity, S2.store_id, P.p_name, P.p_code, S3.sup_name 
       FROM selling_item S 
       INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id 
       INNER JOIN products P ON S.item_id = P.p_id 
       INNER JOIN (
           SELECT product_id, MAX(id) AS max_id 
           FROM product_to_store 
           GROUP BY product_id
       ) AS MaxPs ON S.item_id = MaxPs.product_id 
       INNER JOIN product_to_store Ps ON MaxPs.product_id = Ps.product_id AND MaxPs.max_id = Ps.id 
      
       WHERE DATE(S2.created_at) BETWEEN '$startdate' AND '$enddate'
       ORDER BY S.invoice_id ASC";
   
   $sqltotal = "SELECT 
   SUM(Ps.purchase_price) AS purchase_price, 
       SUM(Ps.purchase_price * S.item_quantity) AS total_purchase_price, 
       SUM(S.item_total) AS total_item_total, 
       SUM(S.item_total - (Ps.purchase_price * S.item_quantity)) AS total_margin
   FROM robymartpos.selling_item S
   INNER JOIN robymartpos.selling_info S2 ON S.invoice_id = S2.invoice_id
   INNER JOIN robymartpos.products P ON S.item_id = P.p_id
   INNER JOIN (
       SELECT product_id, MAX(id) AS max_id 
       FROM robymartpos.product_to_store 
       GROUP BY product_id
   ) AS MaxPs ON S.item_id = MaxPs.product_id
   INNER JOIN robymartpos.product_to_store Ps ON MaxPs.product_id = Ps.product_id AND MaxPs.max_id = Ps.id
  
   WHERE DATE(S2.created_at) BETWEEN '$startdate' AND '$enddate'";
   
   $resulttotal = $link->query($sqltotal);
   $result = $link->query($sql);
   
   $Totalsunit = 0;
   $Totalsquantity = 0;
   $Totalsales = 0;
   $Totalpurchase = 0;
   $Totalprice = 0;
   $Totalprofit = 0;
   $Totalrprofit = 0;
   $Totalssprofit = 0;
   $Totalsprofit = 0;
   
   if ($result && $result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           echo "<tr>";
           echo "<td>" . $row['p_code'] . "</td>";
           echo "<td>" . $row['created_at'] . "</td>";
           echo "<td>" . $row['invoice_id'] . "</td>";
           echo "<td>" . $row['p_name'] . "</td>";
           echo "<td>" . $row['sup_name'] . "</td>";
           echo "<td>" . number_format($row['item_quantity'], 2, '.', ',') . "</td>";
           echo "<td>" . number_format($row['purchase_price'], 2, '.', ',') . "</td>";
           echo "<td>" . number_format($row['item_total'] / $row['item_quantity'], 2, '.', ',') . "</td>";
           echo "<td>" . number_format($row['purchase_price'] * $row['item_quantity'], 2, '.', ',') . "</td>";
           echo "<td>" . number_format($row['item_total'], 2, '.', ',') . "</td>";
           echo "<td>" . number_format(($row['item_total'] - ($row['purchase_price'] * $row['item_quantity'])), 2, '.', ',') . "</td>";
           echo "<td>" . number_format(($row['item_total'] - ($row['purchase_price'] * $row['item_quantity'])) * 0.8, 2, '.', ',') . "</td>";
           echo "<td>" . number_format(($row['item_total'] - ($row['purchase_price'] * $row['item_quantity'])) * 0.2, 2, '.', ',') . "</td>";
           echo "<td>" . number_format($row['purchase_price'] + (($row['item_total'] - ($row['purchase_price'] * $row['item_quantity'])) * 0.2), 2, '.', ',') . "</td>";
           echo "</tr>";
   
           $Totalsunit += round($row['item_quantity'], 2);
           $Totalsales += round($row['item_total'], 2);
           $Totalsprofit += round($row['purchase_price'] + (($row['item_total'] - ($row['purchase_price'] * $row['item_quantity'])) * 0.2), 2);
       }
   }
   
   if ($resulttotal && $resulttotal->num_rows > 0) {
       $totals = $resulttotal->fetch_assoc();
       $Totalpurchase = isset($totals['total_purchase_price']) ? $totals['total_purchase_price'] : 0;
       $Totalprice = isset($totals['total_item_total']) ? $totals['total_item_total'] : 0;
       $Totalprofit = isset($totals['total_margin']) ? $totals['total_margin'] : 0;
       $Totalrprofit += round($Totalprofit * 0.8, 2);
       $Totalssprofit += round($Totalprofit * 0.2, 2);
       $Totalsquantity = isset($totals['purchase_price']) ? $totals['purchase_price'] : 0;
   }
 
  }else{
$sql = "SELECT S.id, S.item_total, Ps.purchase_price, S.invoice_id, S2.created_at, S.item_quantity, S2.store_id, P.p_name, P.p_code,S3.sup_name
FROM selling_item S 
INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
INNER JOIN product_to_store Ps ON S.item_id = Ps.product_id
INNER JOIN products P ON S.item_id = P.p_id
  INNER JOIN suppliers S3
  ON S.sup_id = S3.sup_id
  WHERE S2.store_id = '$id'
  LIMIT 500
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
  $Totalsales = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  echo "<tr>";
    echo "<td>" . $row['p_code'] ."</td>";
    echo "<td>" . $row['created_at'] . "</td>";
     echo "<td>" . $row['invoice_id'] . "</td>";
    echo "<td>" . $row['p_name'] . "</td>";
    echo "<td>" . $row['sup_name'] . "</td>";
    echo "<td>" . number_format($row['item_quantity'], 2, '.', ',') . "</td>";
    echo "<td>" . number_format($row['purchase_price']/$row['item_quantity'], 2, '.', ',') . "</td>";
    echo "<td>" . number_format($row['item_total']/$row['item_quantity'] , 2, '.', ','). "</td>";
    echo "<td>" . number_format($row['purchase_price'], 2, '.', ',') . "</td>";
    echo "<td>" . number_format($row['item_total'] , 2, '.', ','). "</td>";
    echo "<td>" . number_format(((float)$row['item_total'] - (float)$row['purchase_price'] ), 2, '.', ','). "</td>";
    echo "<td>" . number_format(((float)$row['item_total'] - (float)$row['purchase_price'] )*0.8, 2, '.', ','). "</td>"; 
    echo "<td>" . number_format(((float)$row['item_total'] - (float)$row['purchase_price'] )*0.2 , 2, '.', ','). "</td>";
    echo "<td>" .  number_format(((float)$row['purchase_price']+ ((float)$row['item_total'] - (float)$row['purchase_price'] )*0.2), 2, '.', ','). "</td>";
    echo "</tr>";
    $Totalsunit = round($row['item_quantity'] , 2) + $Totalsunit;
     $Totalsquantity = round($row['purchase_price']/$row['item_quantity'] , 2) + $Totalsquantity;
     $Totalsales = round($row['item_total']/$row['item_quantity'] , 2) + $Totalsales;
     $Totalpurchase = round($row['purchase_price'], 2) + $Totalpurchase;
    $Totalprice = round($row['item_total'], 2) + $Totalprice;
    $Totalprofit = round(((float)$row['item_total'] - (float)$row['purchase_price'] ), 2) + $Totalprofit;
    $Totalrprofit = round(((float)$row['item_total'] - (float)$row['purchase_price'] )*0.8, 2) + $Totalrprofit;
    $Totalssprofit = round(((float)$row['item_total'] - (float)$row['purchase_price'] )*0.2, 2) + $Totalssprofit;
    $Totalsprofit = round((((float)$row['purchase_price']+ ((float)$row['item_total'] - (float)$row['purchase_price'] )*0.2)), 2) + $Totalsprofit;
    
  }
} else {
  echo "0 results";
}
$link->close();
}

?>
 
        </tbody>
        <tfoot>
             <tr>
               
                <th colspan="8">Total</th>
                <th><?php echo number_format($Totalpurchase , 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalprice, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalrprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalssprofit, 2, '.', ','); ?></th>
                <th><?php echo number_format($Totalsprofit, 2, '.', ','); ?></th>
                
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
    </script>
   
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