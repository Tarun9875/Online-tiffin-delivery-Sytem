<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Foods</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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

    <?php include( 'partials-customer/menu.php'); ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Tiffin Menu</h2>

            <div class="row">
                <?php 
                foreach ($rows as $row) {
                    ?>
                    <div class="food-menu-box col-lg-4 col-md-6 col-sm-12">
                        <div class="food-menu-img">
                            <img src="images/menu-<?php echo $row->tiffin_id; ?>.jpg" alt="<?php echo $row->name; ?>" class="img-responsive img-curve">
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo $row->name; ?></h4>
                            <p class="food-price">₹<?php echo $row->price; ?></p>
                            <p class="food-detail">
                                <?php echo $row->description; ?>
                            </p>
                            <br>

                            <a href="order.php?id=<?php echo $row->tiffin_id; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="clearfix"></div>

            </div>

        </div>
    </section>
    <!-- fOOD Menu Section Ends Here -->


    <?php include( 'partials-customer/footer.php'); ?>

</body>
</html>