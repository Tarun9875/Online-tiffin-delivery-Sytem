<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'admin_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Check if an admin ID is passed for deletion
if (isset($_GET['id'])) {
    $adminId = (int) $_GET['id']; // Ensure the admin ID is an integer

    // Delete the admin from the collection
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete(['a_id' => $adminId]);
    $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);

    if ($result->getDeletedCount() > 0) {
        ?>
        <script>
            alert("Admin with ID <?php echo $adminId; ?> deleted successfully!");
            window.location.href = "manage_admin.php"; // Redirect to the admin management page
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Failed to delete admin with ID <?php echo $adminId; ?>.");
            window.location.href = "manage_admin.php"; // Redirect to the admin management page
        </script>
        <?php
    }
} else {
    ?>
    <script>
        alert("No admin ID specified for deletion.");
        window.location.href = "manage_admin.php"; // Redirect to the admin management page
    </script>
    <?php
}
?>
