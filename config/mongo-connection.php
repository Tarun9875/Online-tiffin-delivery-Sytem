<?php
// mongo-connection.php
require 'vendor/autoload.php'; // Load the MongoDB library

// Connection string
//$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Select the database
$database = $mongoClient->selectDatabase('tiffin');

// Now you can use $database to interact with collections
?>
