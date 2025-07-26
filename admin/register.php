<?php

require 'vendor/autoload.php';
// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'admin_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Create a new customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerData = array(
       // "CustomerID" => $_POST['id'] ,
        "name" => $_POST['name'],
        "ContactNo" => $_POST['number'],
       // "Address" => $_POST['address'],
        "email" => $_POST['email'],
        "password" => password_hash($_POST['password'], PASSWORD_BCRYPT)
    );

    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($customerData);

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $mongoClient->executeBulkWrite($mongoDbName . '.' . $mongoDbCollection, $bulkWrite, $writeConcern);

    // Check if customer is already registered
    $filter = array('email' => $_POST['email']);
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
    $existingCustomer = $cursor->toArray();

    if (count($existingCustomer) > 1) {
        ?>
        <script>alert("This customer is already registered!");</script>
        <?php
    } else {
        // Create a session for the registered customer
        session_start();
       // $_SESSION['customer_id'] = $_POST['id'];
      //  $_SESSION['customer_name'] = $_POST['name'];
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styless.css">
</head>
<body>
               <!-- menu section-->
    <header  >
      <h1></h1>
        <nav>

            <ul class="right">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
                    <li>
                        <a href="../index.php">Home</a>
                    </li>
                    <li>
                        <a href="../customer/register.php">Login/Registration</a>
                    </li>
                  
                    <li>
                        <a href="#">About Us</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                   
                    <li>
                    <a href="../admin/register.php">Admin login/Register</a> 
                    </li>
        
        </ul>
        </nav >
    </header>

<!-- login code -->

    <!-- Registration form -->
    <div class="container">
        <h1>Admin Registration</h1>
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
            <p>All rights reserved. Designed By <a href="#">Tarun Patel</a></p>
        </div>
    </section>
</body>
</html>
