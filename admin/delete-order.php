<?php
require 'vendor/autoload.php';

$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'order_mast';

$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");
$mongoBulkWrite = new MongoDB\Driver\BulkWrite();

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Delete order
    $mongoBulkWrite->delete(['order_id' => $orderId]);

    $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $mongoBulkWrite);

    if ($result->getDeletedCount() > 0) {
        echo "Order deleted successfully.";
    } else {
        echo "Failed to delete order.";
    }

    // Redirect back to manage orders page
    header("Location: manage-order.php");
    exit();
}
?>
