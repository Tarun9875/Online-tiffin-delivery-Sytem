<?php
// Include the MongoDB PHP Library
require 'vendor/autoload.php';

// Include Admin Menu
include('partials/admin-menu.php');

// MongoDB Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Function to get the count of documents in a collection
function getCollectionCount($mongoClient, $dbName, $collectionName) {
    $command = new MongoDB\Driver\Command(['count' => $collectionName]);
    try {
        $result = $mongoClient->executeCommand($dbName, $command);
        foreach ($result as $res) {
            return $res->n;
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        // Log error instead of displaying
        error_log("Error fetching count for $collectionName: " . $e->getMessage());
        return 0;
    }
}

// Fetch counts
$totalCategories = getCollectionCount($mongoClient, $mongoDbName, 'categorie');
$totalOrders = getCollectionCount($mongoClient, $mongoDbName, 'order_mast');
$totalAdmins = getCollectionCount($mongoClient, $mongoDbName, 'admin_login');
$totalTiffins = getCollectionCount($mongoClient, $mongoDbName, 'tiffin_mast');

// Fetch order statuses
function getOrderStatusCount($mongoClient, $dbName, $collectionName, $status) {
    $filter = ['status' => $status];
    $command = new MongoDB\Driver\Command([
        'count' => $collectionName,
        'query' => $filter
    ]);
    try {
        $result = $mongoClient->executeCommand($dbName, $command);
        foreach ($result as $res) {
            return $res->n;
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        // Log error instead of displaying
        error_log("Error fetching count for $status orders: " . $e->getMessage());
        return 0;
    }
}

$ordersConfirmed = getOrderStatusCount($mongoClient, $mongoDbName, 'order_mast', 'Confirmed');
$ordersCanceled = getOrderStatusCount($mongoClient, $mongoDbName, 'order_mast', 'Cancelled');

?>

<div class="wrapper">
    <div class="main">
        <h3><strong>DASHBOARD</strong></h3>
        <br>
        <div class="dashboard-container">
            <div class="dashboard-card">
                <h2><?php echo $totalCategories; ?></h2>
                <p>Total Categories</p>
            </div>
            <div class="dashboard-card">
                <h2><?php echo $totalOrders; ?></h2>
                <p>Total Orders</p>
            </div>
            <div class="dashboard-card">
                <h2><?php echo $totalAdmins; ?></h2>
                <p>Total Admins</p>
            </div>
            <div class="dashboard-card">
                <h2><?php echo $totalTiffins; ?></h2>
                <p>Total Tiffins</p>
            </div>
            <div class="dashboard-card">
                <h2><?php echo $ordersConfirmed; ?></h2>
                <p>Orders Confirmed</p>
            </div>
            <div class="dashboard-card">
                <h2><?php echo $ordersCanceled; ?></h2>
                <p>Orders Canceled</p>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<style>
/* Add some basic styling for the dashboard */
.dashboard-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.dashboard-card {
    background-color: #f4f4f4;
    padding: 20px;
    flex: 1 1 200px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.dashboard-card h2 {
    font-size: 2.5em;
    margin-bottom: 10px;
    color: #333;
}

.dashboard-card p {
    font-size: 1.2em;
    color: #666;
}

/* Additional styling to match your existing layout */
.wrapper {
    padding: 20px;
}

.main {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
