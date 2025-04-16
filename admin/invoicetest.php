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
$document->setTitle(trans('title_invoice'));

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/ReportSellItemWiseController.js');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php") ;
?>

<!-- Content Wrapper Start -->
<div class="content-wrapper" ng-controller="ReportSellItemWiseController">
  
    
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  <!-- Content Header Start -->
  <section class="content-header">
    <?php include ("../_inc/template/partials/apply_filter.php"); ?>
    <h1>
      <?php echo trans('text_invovoice_title'); ?>
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
        <?php echo trans('text_invoice_title'); ?>
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
              <?php echo trans('text_invoice_sub_title'); ?>
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
 <form action="invoice.php" method="post">
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
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>View</th>
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
   $sql = "SELECT S.id, SUM(S.item_quantity) AS Quantity, SUM(S.item_total) AS Total, S.item_purchase_price, S2.created_at, S.item_quantity, S2.store_id, P.p_name , S.invoice_id ,S2.payment_status , P.p_code,S3.sup_name
FROM selling_item S 
INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
INNER JOIN products P ON S.item_id = P.p_id
  INNER JOIN suppliers S3
  ON S.sup_id = S3.sup_id
   WHERE DATE(S2.created_at) BETWEEN '$startdate' AND '$enddate' AND S2.store_id = '$id' GROUP BY  S2.created_at DESC LIMIT 200"; 
$result = $link->query($sql);
 $Totalpurchase = 0;
 $Totalprice = 0;
 $Totalprofit = 0;
 $Totalrprofit = 0;
  $Totalssprofit = 0;
 $Totalsprofit = 0;
  $Totalsquantity = 0;
  $Totalsunit = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
   $invoice_id = $row['invoice_id'];
   echo "<tr>";
    echo "<td>" . $row['invoice_id'] ."</td>";
    echo "<td>" . $row['created_at'] . "</td>";
    echo "<td>" . number_format($row['Quantity'], 2, '.', ',') . "</td>";
    echo "<td>" . number_format($row['Total'], 2, '.', ',') . "</td>";
    echo "<td>" . $row['payment_status'] . "</td>";
     echo "<td>" ."<a href='http://34.232.46.231/modernpos/admin/view_invoice.php?invoice_id=$invoice_id'> <i class='fa fa-eye' style='font-size:20px'></i> </a>" ."</td>";
    echo "</tr>";
  }
}

 
  }else{
$sql = "SELECT S.id, SUM(S.item_quantity) AS Quantity, SUM(S.item_total) AS Total, S.item_purchase_price, S2.created_at, S.item_quantity, S2.store_id, P.p_name , S.invoice_id,S2.payment_status, P.p_code,S3.sup_name
FROM selling_item S 
INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
INNER JOIN products P ON S.item_id = P.p_id
  INNER JOIN suppliers S3
  ON S.sup_id = S3.sup_id
  WHERE S2.store_id = '$id' GROUP BY  S2.created_at DESC LIMIT 200
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
}

?>

<?php
    while($row = $result->fetch_assoc()):
        $invoice_id = $row['invoice_id'];
        $created_at = $row['created_at'];
        $quantity =  number_format($row['Quantity'], 2, '.', ',');
        $total = number_format($row['Total'], 2, '.', ',');
        $payment_status = $row['payment_status'];
?>
    <tr>
        <td><?php echo "$invoice_id"; ?></td>
        <td><?php echo "$created_at"; ?></td>
        <td><?php echo "$quantity"; ?></td>
        <td><?php echo "$total"; ?></td>
        <td><?php echo "$payment_status"; ?></td>
         <td><a href='http://34.232.46.231/modernpos/admin/view_invoice.php?invoice_id=<?php echo "$invoice_id"; ?>' target="_blank"> <i class='fa fa-eye' style='font-size:20px'></i> </a></td>
    </tr>
<?php endwhile; ?>
 
        </tbody>
        <tfoot>
             <tr>
               
                   <th colspan="2">Total</th>
                   <th>Total Quantity</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>View</th>
                
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