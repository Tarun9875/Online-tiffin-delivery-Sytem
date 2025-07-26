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

// Fetch tiffin data from MongoDB
$query = new MongoDB\Driver\Query([]); // Empty filter to get all documents
$rows = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);

?>

<?php include('partials/admin-menu.php'); ?>
<div class="wraper">
    <div class="main">
        <div>
            <h3><strong>Manage Tiffin</strong></h3>
            <br>
        </div>
        <br/>
        <!-- button add Tiffin-->
        <a href="add-tiffin.php" class="btn-primary">Add Tiffin</a>

        <br />
        <br /><br />
        <br />
        <table class="tbl-full">
            <tr>
                <th>S.NO</th>
                <th>Tiffin id</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>

            <?php 
            $sn = 1; // Serial Number initialization
            foreach ($rows as $row) {
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $row->tiffin_id; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->type; ?></td>
                    <td><?php echo $row->description; ?></td>
                    <td><?php echo '₹' . $row->price; ?></td> <!-- Display price with rupee symbol -->
                    <td>
                        <?php if (isset($row->image->path)) { ?>
                            <img src="<?php echo $row->image->path; ?>" width="100">
                        <?php } else { ?>
                            No Image
                        <?php } ?>
                    </td>
                    <td>
                        <a href="update-tiffin.php?id=<?php echo $row->tiffin_id; ?>" class="btn-secondary">Update</a>
                        <a href="delete-tiffin.php?id=<?php echo $row->tiffin_id; ?>" class="btn-denger">Delete</a>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
