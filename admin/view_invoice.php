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
if (user_group_id() != 1 && !has_permission('access', 'read_sell_invoice')) {
  redirect(root_url() . '/'.ADMINDIRNAME.'/dashboard.php');
}

if (!isset($request->get['invoice_id'])) { 
  redirect('invoice.php');
}
$invoice_id = $request->get['invoice_id'];

// INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');
$invoice_info = $invoice_model->getInvoiceInfo($invoice_id);
if (!$invoice_info) {
  redirect('invoice.php');
}

$document->setTitle(trans('text_invoice') . ' - ' . $invoice_id);

// SIDEBAR COLLAPSE
$document->setBodyClass('sidebar-collapse');
$document->setBodyClass('invoice-page');

// ADD BODY CLASS
$document->setBodyClass('sidebar-collapse');

// Add Script
$document->addScript('../assets/itsolution24/angular/controllers/InvoiceViewController.js'); 

// Include Header and Footer
include("header.php"); 
include ("left_sidebar.php"); 
?>
<link href="file.css" rel="stylesheet" type="text/css" />
<!-- Content Wrapper Start -->
<div class="content-wrapper">


    <?php
    
    if (isset($_GET['invoice_id'])) {
    $invoice_id= $_GET['invoice_id'];
}
             $receipts = "SELECT S.item_price, S.item_total, S3.username, S2.created_at, S.item_quantity, S.item_name, S.invoice_id 
             FROM selling_item S INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id INNER JOIN users S3 ON S3.id = S2.created_by 
             INNER JOIN ( SELECT invoice_id, MAX(created_at) as latest_created_at FROM selling_info GROUP BY invoice_id ) 
             latest_records ON S2.invoice_id = latest_records.invoice_id AND S2.created_at = latest_records.latest_created_at WHERE S.invoice_id = '$invoice_id'";
  $result = $link->query($receipts);
  $Total = 0;
  $created_at = 0;
  $username = 0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
 $created_at = $row['created_at'];
  $username = $row['username'];
   $Total = round($row['item_total'] , 2) + $Total;
  }
}


             ?>
  <!-- Content Header Start -->
  <section class="content-header">
    <h1>
      <?php echo trans('text_invoice_title'); ?> &larr; <?php echo $invoice_id ; ?>
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
        <a href="invoice.php">
          <?php echo trans('text_invoice'); ?>
        </a>
      </li>
      <li class="active">
        <?php echo $invoice_id ; ?>
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
        <div class="box box-info">
        	<div class='box-body'>  
<div class="container bootstrap snippets bootdeys">
<div class="row">
  <div class="col-sm-12"  id="dvContents">
	  	<div class="panel panel-default invoice receipt" id="invoice">
		  <div class="panel-body">
		    <div class="row">

				<div class="col-sm-6 top-left">
					<img src="http://34.232.46.231/modernpos/assets/itsolution24/img/logo-favicons/4_logo.png" style="width:30%; height:70px;">
				</div>

				<div class="col-sm-6 top-right">
						<h3 class="marginright">INVOICE- <?php echo $invoice_id;?></h3>
						<span class="marginright"><?php echo $created_at;?></span>
			    </div>

			</div>
			<hr>
			<div class="row">
			    <div class="col-md-4">
			        <h2><?php echo store('name'); ?></h2>
			        <p>1164-00502, Karen</p>
					<p>Phone:  254759194946</p>
					<p>Email: robbymart24@gmail.com</p>
			    </div>
			     <div class="col-md-4">
			         <h2>Cash Invoice</h2>
			        	<p>For Deliveries, Call - 254714982656</p>
					<p>M-Pesa Till No <?php 
					if( store('name') == "Robbymart- Ola"){
					    echo "9365641";
					}else{
					    echo "9375223";
					}
					
					?>  </p>
			    </div>
			     <div class="col-md-4">
			         <h2>Payment details</h2>
			        	<p>Date: <b><?php echo $created_at;?></b></p>
					<p>Total Amount: <b>  KES <?php echo number_format($Total, 2, '.', ',') ;?></b></p>
					<p>Served By:<b> <?php echo $username;?></b></p>
			    </div>
			</div>
<hr>
			<div class="row">
				<table class="table table-striped">
			      <thead>
			        <tr>
			           <tr>
    <th>SL </th>
    <th>Name</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Amount</th>
			        </tr>
			      </thead>
			      <tbody>
			      
<?php
$receipts = "SELECT S.item_name, COUNT(S.item_name) as item_count, 
                    SUM(S.item_quantity) as total_quantity, 
                    S.item_price, 
                    SUM(S.item_total) as total_item_total
             FROM selling_item S 
             INNER JOIN selling_info S2 ON S.invoice_id = S2.invoice_id
             INNER JOIN (
                 SELECT invoice_id, MAX(created_at) as latest_created_at
                 FROM selling_info
                 GROUP BY invoice_id
             ) latest_records ON S2.invoice_id = latest_records.invoice_id AND S2.created_at = latest_records.latest_created_at
             WHERE S.invoice_id = '$invoice_id'
             GROUP BY S.item_name, S.item_price";

$result = $link->query($receipts);
$Total = 0;
$_SESSION['total'] = $Total;
?>

             
 <?php while($row = $result->fetch_assoc()): 
            $item_name = $row['item_name'];
            $item_count = $row['item_count'];
            $total_quantity = number_format($row['total_quantity'], 0, '.', ',');
            $item_price = number_format($row['item_price'], 2, '.', ',');
            $total_item_total = number_format($row['total_item_total'], 2, '.', ',');
            $Total += round($row['total_item_total'], 2);
        ?>
   <tr>
        <td class="count"></td>
        <td><?php echo $item_name; ?></td>
        <td><?php echo $total_quantity; ?></td>
        <td><?php echo $item_price; ?></td>
        <td><?php echo $total_item_total; ?></td>
    </tr>
   
<?php endwhile; ?>
 
 
             <tr><td colspan="6"><br></td></tr>
                  <tr class="lead marginbottom"><td colspan="5">Total Amount :</td><td> KES <?php echo number_format($Total, 2, '.', ',') ;?></td></tr>
                   <tr class="lead marginbottom"><td colspan="5">Amount Paid:</td><td> KES <?php echo number_format($Total, 2, '.', ',') ;?></td></tr>
			       </tbody>
			    </table>

			</div>

			<div class="row">
			<div class="col-xs-6 margintop">
				<p class="lead marginbottom">THANK YOU!</p>

			</div>
			</div>

		  </div>
		  <hr>
		  <center><i><a href="https://qloudpointsolutions.com/" target="_blank">Powered by Qloud Point Solutions Ltd</a></i></center>	
		</div>
	
	</div>
	<center><input type="button" class="btn btn-primary" id="btnPrint" value="Print" /></center>

</div>
</div>

    		  </div> 
        </div>
      </div>
    </div>
  </section>
  <!-- Content End-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">


$(function () {
    $("#btnPrint").click(function () {
        var contents = $("#dvContents").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title></title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write('<link href="file.css" rel="stylesheet" type="text/css" />');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    });
});
</script>
<script type="text/javascript">

</script>
</div>
<!-- Content Wrapper End -->

<?php include ("footer.php"); ?>