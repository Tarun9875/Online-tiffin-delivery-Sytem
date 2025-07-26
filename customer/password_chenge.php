<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'customer_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Check if user is logged in
session_start();
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['customer_email'])) {
    ?>
    <script>
        alert("You are not logged in!");
        window.location.href = "login.php";
    </script>
    <?php
}

// Change password form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if old password is correct
    $filter = array('email' => $_SESSION['customer_email']);
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery($mongoDbName . '.' . $mongoDbCollection, $query);
    $customer = $cursor->toArray();

    if (count($customer) > 0) {
        $hashedPassword = $customer[0]->password;
        if (password_verify($oldPassword, $hashedPassword)) {
            // Update password
            $updateFilter = array('email' => $_SESSION['customer_email']);
            $updateData = array('$set' => array('password' => password_hash($newPassword, PASSWORD_BCRYPT)));
            $updateOptions = array('upsert' => true);
            $result = $mongoClient->executeUpdate($mongoDbName . '.' . $mongoDbCollection, $updateFilter, $updateData, $updateOptions);

            ?>
            <script>
                alert("Password changed successfully!");
                window.location.href = "login.php";
            </script>
            <?php
        } else {
            ?>
            <script>alert("Old password is incorrect!");</script>
            <?php
        }
    } else {
        ?>
        <script>alert("Customer not found!");</script>
        <?php
    }
}

?>


<!--php code End-->


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
                        <a href="register.php">Login/Registration</a>
                    </li>
                  
                    <li>
                        <a href="#">About Us</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                    
                    <li>
                    <a href="../admin/login.php">Admin login</a> 
                    </li>
        
        </ul>
        </nav >
    </header>

<!-- login code -->

        <div class="container"> 
    <h2>Customer  Password Change</h2>
    <form id="f" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
    <div class="form-group"> 
    <div class="col-md-6">
    <div>
        <label for="email">Enter Email Id:</label>
        <input type="email" id="email" class="form-control" placeholder="Enter Email Id">
    </div></div><br>
    <div class="col-md-6">
    <div>
    <label for="password">Enter old Password:</label>
    <input type="password" name="old_password" class="form-control" placeholder="Enter Your Password">
    </div></div><br>
    <div class="col-md-6">
    <div>
    <label for="password">Enter New Password:</label>
    <input type="password" name="new_password" class="form-control" placeholder="Enter Your New Password">
    </div></div><br>


    <div class="col-md-6">
    <div>
    <label for="password">Conform Password:</label>
    <input type="password" name="confirm_password" class="form-control" placeholder="Enter Your New Password">
    </div></div><br>

    <a href="login.php"><input type="button" name="submit" value="Change" class="btn-primary" ></a> 
    <a href="register.php"> <input type="button"  value="Back" class="btn-primary" ></a>
    </div>    
    </form>
</div>
          <!--login page  End form -->
        
            

  <!-- footer Section Starts Here -->
    <section class="footer">
        <div class="container text-center">
                                     <!-- social Section Starts Here -->
    <section class="social">
        <div class="container text-center">
            <ul>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a>
                </li>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a>
                </li>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a>
                </li>
            </ul>
        </div>
    </section>
    <!-- social Section Ends Here -->
            <p>All rights reserved. Designed By <a href="#">Tarun Patel</a></p>
        </div>
    </section>
    <!-- footer Section Ends Here -->
</body>
</html>