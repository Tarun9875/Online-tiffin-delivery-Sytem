<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'admin_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Get all admins from the collection
$filter = array();
$query = new MongoDB\Driver\Query($filter);
$cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
$admins = $cursor->toArray();

// Display the admins in a table
?>
<?php include( 'partials/admin-menu.php'); ?>
<form>
    <div class="wraper">  
<div class="main">
        <div >
        <h3><strong>Manage Admin</strong></h3>
        <br>
    
    <!-- button add Admin-->
    <a href="add-admin.php" class="btn-primary">Add Admin</a>     
        <table class="tbl-full">
            <tr>
                <th>S.NO</th>
                <th>Admin id</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </tr>
        <?php foreach ($admins as $admin) { ?>
        <tr>
            <td><?= $admin->a_id ?></td>
            <td><?= $admin->a_id ?></td>
            <td><?= $admin->email ?></td>
            <td>
                <a href="update-admin.php?id=<?= $admin->a_id ?>" class="btn-secondary">Update</a>
                <a href="delete-admin.php?id=<?= $admin->a_id ?>" class="btn-denger">Delete</a>
            </td>
        </tr>
        <?php } ?>
       
</div>
</div>

</div>


</form>


