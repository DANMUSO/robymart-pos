<?php
// Database connection
$link = mysqli_connect("127.0.0.1", "admin", "@12345PoS", "robymartpos");

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Disable Safe Mode for this session
mysqli_query($link, "SET SQL_SAFE_UPDATES = 0");

// Delete from selling_item first to avoid foreign key constraint issues
$delete_items_query = "
DELETE si FROM robymartpos.selling_item si
JOIN (
    SELECT si1.invoice_id
    FROM robymartpos.selling_info si1
    JOIN robymartpos.selling_info si2
        ON si1.created_by = si2.created_by
        AND si1.total_items = si2.total_items
        AND ABS(TIMESTAMPDIFF(SECOND, si1.created_at, si2.created_at)) < 10
        AND si1.invoice_id > si2.invoice_id
        AND si1.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
) AS duplicates ON si.invoice_id = duplicates.invoice_id;
";

if (mysqli_query($link, $delete_items_query)) {
    echo "Duplicate records deleted from selling_item.<br>";
} else {
    echo "ERROR deleting from selling_item: " . mysqli_error($link) . "<br>";
}

// Now delete from selling_info
$delete_info_query = "
DELETE si FROM robymartpos.selling_info si
JOIN (
    SELECT si1.invoice_id
    FROM robymartpos.selling_info si1
    JOIN robymartpos.selling_info si2
        ON si1.created_by = si2.created_by
        AND si1.total_items = si2.total_items
        AND ABS(TIMESTAMPDIFF(SECOND, si1.created_at, si2.created_at)) < 10
        AND si1.invoice_id > si2.invoice_id
        AND si1.created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
) AS duplicates ON si.invoice_id = duplicates.invoice_id;
";

if (mysqli_query($link, $delete_info_query)) {
    echo "Duplicate records deleted from selling_info.<br>";
} else {
    echo "ERROR deleting from selling_info: " . mysqli_error($link) . "<br>";
}

// Re-enable Safe Mode (Optional)
mysqli_query($link, "SET SQL_SAFE_UPDATES = 1");

// Close connection
mysqli_close($link);
?>
