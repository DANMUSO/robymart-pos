              <?php
                   include ("config.php");
                  $date = date("Y-m-d");
                  $invoice_no = $_POST['invoiceid'];
                  $product_id = $_POST['productid'];
                  $sup_id = $_POST['supplierid'];
                  $units = $_POST['productunit'];
                  $cname = $_POST['productname'];
                  $return_by = $_POST['returnby'];
                  $return_date = $date;
                  $note = $_POST['returnnote'];
                  
                  $storeid =  $_POST['storeid'];
                   $price = "SELECT S.item_purchase_price, S.item_total, S.item_quantity FROM selling_item S WHERE invoice_id ='$invoice_no' AND item_id ='$product_id' AND sup_id = '$sup_id' ";
                               $unitprice = $link->query($price);
                                if ($unitprice->num_rows > 0 ) {
                                   while ($row = $unitprice->fetch_assoc()) {
                                     $itemquantity =  $row['item_quantity'];
                                    $purchase = $row['item_purchase_price'];
                                    $sell = $row['item_total'];
                                    
                                   }
                                }
                                $stock = "SELECT P.quantity_in_stock, P.purchase_price, P.sell_price FROM product_to_store P WHERE product_id ='$product_id' AND sup_id = '$sup_id' ";
                               $productstock = $link->query($stock);
                                if ($productstock->num_rows > 0 ) {
                                   while ($row = $productstock->fetch_assoc()) {
                                     $stockquantity =  $row['quantity_in_stock'];
                                    
                                   }
                                }
                                if ($itemquantity < $units  ){
                                    $response['exceed'] = "Entered value exceed the available quantity!"; 
                                    echo json_encode($response);
                                    
                                }elseif($itemquantity == $units){
                                    
                            $sql = "INSERT INTO sells_return_p (product_id, sup_id, invoice_no, units, store_id , cname, return_by, return_date, note, purchase, sell) VALUES('$product_id','$sup_id', '$invoice_no', '$units','$storeid', '$cname', '$return_by', '$return_date', '$note','$purchase','$sell')";
                            mysqli_query($link, $sql);
                            $totalquantity = $stockquantity + $units;
                             $updatestock = "UPDATE product_to_store SET quantity_in_stock ='$totalquantity'  WHERE product_id ='$product_id' AND sup_id = '$sup_id' ";
                            mysqli_query($link, $updatestock);
                            $delete = "DELETE FROM selling_item WHERE invoice_id ='$invoice_no' AND item_id ='$product_id' AND sup_id = '$sup_id' ";
                            $deleterecord = $link->query($delete);
                                    $response['success'] = "Entered value exceed the available quantity!"; 
                                    echo json_encode($response);
                                }else{
                                    
                            $total_purchase = ($purchase/$itemquantity)*$units;
                            $total_sell = ($sell/$itemquantity)*$units;
                            
                                     $sql = "INSERT INTO sells_return_p (product_id, sup_id, invoice_no, units, store_id, cname, return_by, return_date, note, purchase, sell) VALUES('$product_id','$sup_id', '$invoice_no', '$units','$storeid', '$cname', '$return_by', '$return_date', '$note','$total_purchase','$total_sell')";
                            mysqli_query($link, $sql);
                            $digit = $itemquantity - $units;
                            $totalpurchase = ($purchase/$itemquantity)*$digit;
                            $totalsell = ($sell/$itemquantity)*$digit;
                            $totalquantity = $stockquantity + $units;
                            $updatestock = "UPDATE product_to_store SET quantity_in_stock ='$totalquantity'  WHERE product_id ='$product_id' AND sup_id = '$sup_id' AND store_id ='$storeid' ";
                            mysqli_query($link, $updatestock);
                            
                            $update = "UPDATE selling_item SET item_quantity ='$digit', item_purchase_price = '$totalpurchase', item_total = '$totalsell'   WHERE invoice_id ='$invoice_no' AND item_id ='$product_id' AND sup_id = '$sup_id' ";
                            mysqli_query($link, $update);
                            $response['less'] = "less!"; 
                                    echo json_encode($response);
                     
                                }
                    
                    
                      
                  
              ?>