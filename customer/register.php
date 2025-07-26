<?php

require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'customer_login';
$counterCollection = 'counters';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Function to get the next sequence value for auto-increment
function getNextSequence($mongoClient, $sequenceName, $mongoDbName, $counterCollection) {
    $bulk = new MongoDB\Driver\BulkWrite;
    $filter = ['_id' => $sequenceName];
    $update = ['$inc' => ['seq' => 1]];
    $options = ['upsert' => true];

    $bulk->update($filter, $update, $options);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $mongoClient->executeBulkWrite("$mongoDbName.$counterCollection", $bulk, $writeConcern);

    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery("$mongoDbName.$counterCollection", $query);
    $result = $cursor->toArray();

    return $result[0]->seq;
}

// Create a new customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the next CustomerID
    $customerID = getNextSequence($mongoClient, 'customer_id_seq', $mongoDbName, $counterCollection);

    $customerData = [
        "CustomerID" => $customerID,
        "name" => $_POST['name'],
        "ContactNo" => $_POST['number'],
        "Address" => $_POST['address'],
        "email" => $_POST['email'],
        "password" => password_hash($_POST['password'], PASSWORD_BCRYPT)
    ];

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($customerData);

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $mongoClient->executeBulkWrite($mongoDbName . '.' . $mongoDbCollection, $bulkWrite, $writeConcern);

    // Check if customer is already registered
    $filter = ['email' => $_POST['email']];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
    $existingCustomer = $cursor->toArray();

    if (count($existingCustomer) > 0) {
        ?>
        <script>alert("This customer is already registered!");</script>
        <?php
    } else {
        // Create a session for the registered customer
        session_start();
        $_SESSION['customer_id'] = $customerID;
        $_SESSION['customer_name'] = $_POST['name'];
        $_SESSION['customer_email'] = $_POST['email'];

        ?>
        <script>
            alert("You have registered successfully! Please click on the link to login.");
            window.location.href = "login.php";
        </script>
        <?php
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Tiffin Delivery System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styless.css">
</head>
<body>
    <!-- Menu section -->
    <header>
        <nav>
            <ul class="right">
                <li><a href="../index.php">Home</a></li>
                <li><a href="register.php">Login/Registration</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="../admin/login.php">Admin login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Registration form -->
    <div class="container">
        <h1>Customer Registration</h1>
        <form id="f" action="" method="post">
            <div class="form-group">
                <div class="col-md-6">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                </div><br>
                <div class="col-md-6">
                    <label for="number">Enter Mobile Number:</label>
                    <input type="number" name="number" class="form-control" placeholder="Enter Mobile Number" required>
                </div><br>
                <div class="col-md-6">
                    <label for="address">Enter Your Address:</label>
                    <textarea id="address" name="address" class="form-control" placeholder="Enter your address like city, village" rows="5" required></textarea>
                </div><br>
                <div class="col-md-6">
                    <label for="email">Enter Email Id:</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email Id" required>
                </div><br>
                <div class="col-md-6">
                    <label for="password">Enter Password:</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div><br>
                <input type="submit" name="submit" value="Register" class="btn btn-primary">
                <p>Do you have an account? <a href="login.php">Click here</a></p>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <section class="footer">
        <div class="container text-center">
            <section class="social">
                <ul>
                    <li><a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a></li>
                    <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a></li>
                    <li><a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a></li>
                </ul>
</section>
</div>
</section>
</body>
</html>