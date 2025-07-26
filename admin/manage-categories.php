<?php
require 'vendor/autoload.php';
include('partials/admin-menu.php');

// MongoDB Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'categorie';

// MongoDB Connection
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Fetch all categories from MongoDB
$query = new MongoDB\Driver\Query([]);
$rows = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);
$categories = iterator_to_array($rows);
?>

<div class="wraper">
    <div class="main">
        <div>
            <h3><strong>Manage Categories</strong></h3>
            <br>
        </div>
        <br />
        <!-- Button to add category -->
        <a href="add-categories.php" class="btn-primary">Add Categories</a>
        <br /><br /><br /><br />

        <table class="tbl-full">
            <tr>
                <th>S.NO</th>
                <th>C_ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Action</th>
            </tr>

            <?php if (!empty($categories)): ?>
                <?php $sn = 1; ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo $sn++; ?>.</td>
                        <td><?php echo isset($category->c_id) ? $category->c_id : 'N/A'; ?></td>
                        <td><?php echo $category->title; ?></td>
                        <td>
                            <?php if (!empty($category->image->path)): ?>
                                <img src="<?php echo $category->image->path; ?>" alt="Category Image" width="100">
                            <?php else: ?>
                                <p>No Image</p>
                            <?php endif; ?>
                        </td>
                      
                        <td>
                            <a href="update-categories.php?id=<?php echo $category->_id; ?>" class="btn-secondary">Update</a>
                            <a href="delete-categorie.php?id=<?php echo $category->_id; ?>" class="btn-denger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No Categories Found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
