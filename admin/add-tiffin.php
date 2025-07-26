
<?php 


// Include the MongoDB PHP Library
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'tiffin_mast';
$counterCollection = 'counters'; // Collection for counters

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to get the next sequence value for a given counter name
function getNextSequenceValue($mongoClient, $counterCollection, $counterName) {
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $filter = ['_id' => $counterName];
    $update = ['$inc' => ['sequence_value' => 1]];
    $options = ['upsert' => true];

    $bulkWrite->update($filter, $update, $options);
    $mongoClient->executeBulkWrite("tiffin.$counterCollection", $bulkWrite);

    // Fetch the updated counter value
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery("tiffin.$counterCollection", $query);
    $counter = current($cursor->toArray());
    
    return $counter->sequence_value;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the input fields
    if (empty($_POST['name'])) {
        echo "<script>alert('Please enter the Tiffin name!');</script>";
        exit;
    }

    $name = $_POST['name'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Check if image is uploaded
    if (!isset($_FILES['iamge']) || $_FILES['iamge']['error'] != UPLOAD_ERR_OK) {
        echo "<script>alert('Please select an image!');</script>";
        exit;
    }

    $image = $_FILES['iamge'];
    $imageName = $image['name'];
    $imageType = $image['type'];
    $imagePath = 'uploads/' . $imageName;

    // Ensure the uploads directory exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Move the uploaded file
    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        // Prepare the document to be inserted
        $bulkWrite = new MongoDB\Driver\BulkWrite;

        // Generate a new Tiffin ID (auto-incremented)
        $tiffinId = getNextSequenceValue($mongoClient, $counterCollection, "tiffin_mast");

        $document = [
            'tiffin_id' => $tiffinId,
            'name' => $name,
            'type' => $type,
            'description' => $description,
            'price' => $price,
            'image' => [
                'name' => $imageName,
                'type' => $imageType,
                'path' => $imagePath
            ]
        ];

        $bulkWrite->insert($document);

        // Insert the document into MongoDB
        try {
            $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);
            
            // Check if the insertion was successful
            if ($result->getInsertedCount() > 0) {
                echo "<script>alert('Tiffin added successfully!'); window.location.href = 'add-tiffin.php';</script>";
            } else {
                echo "<script>alert('Error: Tiffin could not be added!');</script>";
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo "<script>alert('Error adding tiffin: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image!'); window.location.href = 'add-tiffin.php';</script>";
    }
}
?>
<?php include( 'partials/admin-menu.php'); ?>
<div class="main-content">
<div class="wraper">
    <div class="main">
        <div>
            <h3><strong>Add Tiffin</strong></h3>
            <br>
        </div>
        <br/>

        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Tiffin Id:</td>
                    <td>
                        <input type="text" name="id" placeholder="Tiffin id" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Name:</td>
                    <td>
                        <input type="text" name="name" placeholder="Enter Tiffin name">
                    </td>
                </tr>

                <tr>
                    <td>Type:</td>
                    <td>
                        <input type="radio" name="type" value="vege"> Vege
                        <input type="radio" name="type" value="nonvege"> Non-Vege
                    </td>
                </tr>

                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Tiffin"></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price:</td>
                    <td>
                        <input type="number" name="price">
                    </td>
                </tr>

                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="iamge">
                    </td>
                </tr>
            </table>
            <br/>
            <input type="submit" value="Add Tiffin" name="submit" class="btn-primary">
        </form>
        <br/>
        <br/>
    </div>
</div>
</div>

<?php include('partials/footer.php'); ?>
