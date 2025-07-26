<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'admin_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if admin already exists
    $filter = array('email' => $email);
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
    $admin = $cursor->toArray();

    if (count($admin) == 0) {
        // Get the next admin ID
        $filter = array();
        $options = array('sort' => array('a_id' => -1), 'limit' => 1);
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
        $lastAdmin = $cursor->toArray();

        //$nextAdminId = 1;
        //if (count($lastAdmin) > 0) {
      //$nextAdminId = $lastAdmin[0]['a_id'] + 1;
        //}   
        $nextAdminId = 1;
        if (count($lastAdmin) > 0) {
        $nextAdminId = $lastAdmin[0]->a_id + 1;
    }

       // Insert new admin
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $document = array('a_id' => $nextAdminId, 'email' => $email, 'password' => password_hash($password, PASSWORD_BCRYPT));
    $bulkWrite->insert($document);
    $result = $mongoClient->executeBulkWrite($mongoDbName . '.' . $mongoDbCollection, $bulkWrite);
        ?>
        <script>
            alert("Admin added successfully!");
          //  window.location.href = "add-admin.php";
        </script>
        <?php
    } else {
        ?>
        <script>alert("Admin already exists!");</script>
        <?php
    }
}
?>
<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styless.css">
</head>
<body>-->
    <?php include('partials/admin-menu.php'); ?>

    <div class="main">
        <div class="wraper">
            <h1>Add Admin</h1>
            <br>
            <form action="" method="post">
                <table class="tbl-30">
                    <tr>
                        <td>Email id: </td>
                        <td><input type="text" name="email" placeholder="Your Email id" > </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="password" placeholder="your password" >  </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <?php include('partials/footer.php');?>