<?php
require 'vendor/autoload.php';
include('partials-customer/menu.php');

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

<!-- Categories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Categories</h2>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): 
                // Handle image data
                if (!empty($category->image->data)) { 
                    $imageData = base64_encode($category->image->data); 
                    $imageUrl = "data:image/jpeg;base64,$imageData"; 
                } else {
                    $imageUrl = "http://yourwebsite.com/" . $category->image->path; // If image path is stored
                }
            ?>
            <a href="category-foods.php?catid=<?php echo $category->_id; ?>">
                <div class="box-3 float-container">
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8'); ?>" class="img-responsive img-curve" width="100%" height="auto">
                    <h3 class="float-text text-white"><?php echo htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No categories found</p>
        <?php endif; ?>

        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

<?php include('partials-customer/footer.php'); ?>
