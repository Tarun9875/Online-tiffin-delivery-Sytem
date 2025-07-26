<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'categorie';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if c_id is set
if (isset($_GET['c_id']) && !empty($_GET['c_id'])) {
    $c_id = $_GET['c_id']; // Get the c_id from the query string
    
    // Debug: Log the c_id value
    echo "Received C_ID: $c_id<br>";
    
    // Ensure c_id is an integer
    $c_id = (int) $c_id;

    // Prepare the filter for deletion
    $filter = ['c_id' => $c_id];

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
                alert("Category with C_ID <?php echo $c_id; ?> deleted successfully!");
                window.location.href = "manage-categories.php"; // Redirect to the manage categories page
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Failed to delete the category with C_ID <?php echo $c_id; ?>. It may not exist.");
                window.location.href = "manage-categories.php"; // Redirect to the manage categories page
            </script>
            <?php
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        ?>
        <script>
            alert("Error deleting category: <?php echo addslashes($e->getMessage()); ?>");
            window.location.href = "manage-categories.php"; // Redirect to the manage categories page
        </script>
        <?php
    }
} else {
    // Handle case where no c_id is specified or empty
    echo "No C_ID specified for deletion.<br>";
    ?>
    <script>
        alert("No C_ID specified for deletion.");
        window.location.href = "manage-categories.php"; // Redirect to the manage categories page
    </script>
    <?php
}
?>
