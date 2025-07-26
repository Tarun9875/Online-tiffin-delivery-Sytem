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

    // Fetch current tiffin details
    $filter = ['tiffin_id' => $tiffin_id];
    $query = new MongoDB\Driver\Query($filter);
    $rows = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query)->toArray();

    if (count($rows) > 0) {
        $tiffin = $rows[0]; // Get the first (and should be only) result
    } else {
        echo "No tiffin found with ID $tiffin_id.";
        exit;
    }
} else {
    echo "No Tiffin ID specified.";
    exit;
}

// Handle form submission for updating the tiffin
if (isset($_POST['submit'])) {
    // Get the form data
    $new_tiffin_id = (int) $_POST['id']; // Get the new Tiffin ID
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = (float) $_POST['price'];

    // Prepare update document
    $updateData = [
        'tiffin_id' => $new_tiffin_id, // Update with new Tiffin ID
        'name' => $name,
        'description' => $description,
        'price' => $price,
    ];

    // Check if a new image has been uploaded
    if (isset($_FILES['iamge']) && $_FILES['iamge']['error'] == 0) {
        $imagePath = "uploads/" . basename($_FILES['iamge']['name']);
        if (move_uploaded_file($_FILES['iamge']['tmp_name'], $imagePath)) {
            $updateData['image'] = ['path' => $imagePath];
        }
    }

    // Update the tiffin in MongoDB
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->update($filter, ['$set' => $updateData]);

    try {
        $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);

        if ($result->getModifiedCount() > 0) {
            echo "<script>alert('Tiffin updated successfully!'); window.location.href = 'manage-tiffin.php';</script>";
        } else {
            echo "<script>alert('No changes were made to the tiffin.');</script>";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "<script>alert('Error updating tiffin: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>

<?php include('partials/admin-menu.php'); ?>
<div class="main-content">
    <div class="wraper">
        <h3><strong>Update Tiffin</strong></h3>
        <br/><br/>

        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Tiffin Id:</td>
                    <td>
                        <input type="text" name="id" value="<?php echo $tiffin->tiffin_id; ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Name:</td>
                    <td>
                        <input type="text" name="name" value="<?php echo $tiffin->name; ?>" placeholder="Enter Tiffin name" required>
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Tiffin" required><?php echo $tiffin->description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price" value="<?php echo $tiffin->price; ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Current image:</td>
                    <td>
                        <?php if (isset($tiffin->image->path)) { ?>
                            <img src="<?php echo $tiffin->image->path; ?>" width="100"><br>
                        <?php } else { ?>
                            No Image Available
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="iamge">
                    </td>
                </tr>
            </table>
            <br>
            <input type="submit" value="Update Tiffin" name="submit" class="btn-primary">
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
