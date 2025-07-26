<?php
require 'vendor/autoload.php';

// MongoDB Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'categorie';

// MongoDB Connection
try {
    $mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");
} catch (MongoDB\Driver\Exception\Exception $e) {
    die('Failed to connect to MongoDB: ' . $e->getMessage());
}
?>
