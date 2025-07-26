<?php 
// Include the MongoDB PHP Library
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'tiffin_mast';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if tiffin ID is provided
if (isset($_GET['id'])) {
    $tiffin_id = (int) $_GET['id']; // Ensure id is an integer

    // Prepare the filter for deletion
    $filter = ['tiffin_id' => $tiffin_id];

    // Prepare the BulkWrite operation
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->delete($filter);

    try {
        // Execute the delete operation
        $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);

        // Check if the deletion was successful
        if ($result->getDeletedCount() > 0) {
            ?>
            <script>
                alert("Tiffin with ID <?php echo $tiffin_id; ?> deleted successfully!");
                window.location.href = "manage-tiffin.php"; // Redirect to the manage tiffin page
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Failed to delete the tiffin with ID <?php echo $tiffin_id; ?>. It may not exist.");
                window.location.href = "manage-tiffin.php"; // Redirect to the manage tiffin page
            </script>
            <?php
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        ?>
        <script>
            alert("Error deleting tiffin: <?php echo addslashes($e->getMessage()); ?>");
            window.location.href = "manage-tiffin.php"; // Redirect to the manage tiffin page
        </script>
        <?php
    }
} else {
    // Handle case where no tiffin ID is specified
    echo "No Tiffin ID specified for deletion.<br>";
    ?>
    <script>
        alert("No Tiffin ID specified for deletion.");
        window.location.href = "manage-tiffin.php"; // Redirect to the manage tiffin page
    </script>
    <?php
}
?>
