<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'customer_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if customer exists
    $filter = array('email' => $email);
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
    $customer = $cursor->toArray();

    if (count($customer) > 0) {
        $hashedPassword = $customer[0]->password;
        if (password_verify($password, $hashedPassword)) {
            // Create a session for the logged in customer
            session_start();
            $_SESSION['customer_id'] = $customer[0]->name;
            $_SESSION['customer_email'] = $email;

            ?>
            <script>
                alert("You have logged in successfully!");
                window.location.href = "index.php";
            </script>
            <?php
        } else {
            ?>
            <script>alert("Invalid password!");</script>
            <?php
        }
    } else {
        ?>
        <script>alert("Customer not found!");</script>
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

    <!-- Login form -->
    <div class="container">
        <h1>Customer Login</h1>
        <form id="f" action="" method="post">
            <div class="form-group col-md-6">
                <label for="email">Enter Email Id:</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email Id" required>
            </div>
            <br>
            <div class="form-group col-md-6">
                <label for="password">Enter Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>
            </div>
            <br>
            <input type="submit" name="submit" value="Login" class="btn btn-primary">
            <p>Do You Want To Forget Password? <a href="password_chenge.php" data-toggle="modal" data-dismiss="modal">Click here</a></p>
            <p>Don't you have an account? <a href="register.php">Click here</a></p>
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        </form>
    </div>

    <!-- Footer section -->
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
