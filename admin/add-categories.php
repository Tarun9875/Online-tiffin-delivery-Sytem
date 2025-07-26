<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'categorie';
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
    // Check if title is set
    if (empty($_POST['title'])) {
        echo "<script>alert('Please enter a title!');</script>";
        exit;
    }
    $title = $_POST['title'];

    // Check if featured is set
    //if (!isset($_POST['featured'])) {
   //     echo "<script>alert('Please select a featured option!');</script>";
  ///      exit;
 //   }
   // $featured = $_POST['featured'];

    // Check if active is set
  //  if (!isset($_POST['active'])) {
  //      echo "<script>alert('Please select an active option!');</script>";
  //      exit;
 //   }
  //  $active = $_POST['active'];

    // Check if image is uploaded
 ///   if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
  ///      echo "<script>alert('Please select an image!');</script>";
 //       exit;
  //  }

    $image = $_FILES['image'];
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

        // Generate a new ObjectId and get the next sequence value
        $id = new MongoDB\BSON\ObjectId();
        $c_id = getNextSequenceValue($mongoClient, $counterCollection, "categorie");

        $document = [
            '_id' => $id,
            'c_id' => $c_id,
            'title' => $title,
            'image' => [
                'name' => $imageName,
                'type' => $imageType,
                'path' => $imagePath
            ],
            //'featured' => $featured,
            //'active' => $active
        ];

        $bulkWrite->insert($document);

        // Insert the document into MongoDB
        try {
            $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);
            
            // Check if the insertion was successful
            if ($result->getInsertedCount() > 0) {
                echo "<script>alert('Category added successfully!'); window.location.href = 'add-categories.php';</script>";
            } else {
                echo "<script>alert('Error: Category could not be added!');</script>";
            }
        } catch (MongoDB\Driver\Exception\Exception $e) {
            echo "<script>alert('Error adding category: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image!'); window.location.href = 'add-categories.php';</script>";
    }
}
?>

<?php include('partials/admin-menu.php'); ?>
<div class="main-content">
    <div class="wraper">
        <h1><strong>Add Categories</strong></h1>
        <br><br>

        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" placeholder="category title">
                    </td>
                </tr>
                <tr>
                    <td>Select Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>
               
            </table>
            <br>
            <input type="submit" value="Add Categories" name="submit" class="btn-primary">
        </form>
        <br><br>
    </div>
</div>
<?php include('partials/footer.php'); ?>
