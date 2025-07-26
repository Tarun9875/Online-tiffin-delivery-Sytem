<?php
require 'vendor/autoload.php';

$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'order_mast';

$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['order_id' => $order_id],
        ['$set' => ['status' => $newStatus]]
    );

    $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulk);
}

header('Location: manage-order.php');
exit();
?>
