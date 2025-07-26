<?php
// MongoDB connection
require 'vendor/autoload.php'; // Include Composer's autoloader for MongoDB

try {
    // Establish MongoDB connection
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->tiffin_service; // Connect to the 'tiffin_service' database
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}

// Handle search form submission
$foods = [];
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')];
    $collection = $db->tiffin_mast;
    $foods = $collection->find($filter);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Search</title>
    <!-- Include your CSS files here -->
</head>
<body>

<?php include('partials-customer/menu.php'); ?>

<!-- FOOD SEARCH Section Starts Here -->
<section class="food-search text-center">
    <div class="container">
        <h2>Search for Foods</h2>
        <form id="searchForm" method="POST">
            <input type="text" id="searchInput" name="search" placeholder="Search for Food.." required>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</section>
<!-- FOOD SEARCH Section Ends Here -->

<!-- FOOD MENU Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Tiffin Menu</h2>

        <div id="food-menu-container">
            <?php
            if (isset($_POST['search'])) {
                if ($foods->isDead()) {
                    echo '<p class="text-center">No matching foods found.</p>';
                } else {
                    foreach ($foods as $food) {
                        // Access the fields of each BSONDocument object correctly
                        $name = htmlspecialchars($food->name ?? 'Unknown');
                        $price = htmlspecialchars($food->price ?? '0.00');
                        $description = htmlspecialchars($food->description ?? 'No description');
                        $image = htmlspecialchars($food->image ?? 'default.jpg');
                        $id = htmlspecialchars($food->_id);
                        
                        echo '
                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <img src="' . $image . '" alt="' . $name . '" class="img-responsive img-curve">
                            </div>
                            <div class="food-menu-desc">
                                <h4>' . $name . '</h4>
                                <p class="food-price">₹' . $price . '</p>
                                <p class="food-detail">' . $description . '</p>
                                <br>
                                <a href="order.php?id=' . $id . '" class="btn btn-primary">Order Now</a>
                            </div>
                        </div>';
                    }
                }
            }
            ?>
        </div>

        <div class="clearfix"></div>
    </div>
</section>
<!-- FOOD MENU Section Ends Here -->

<?php include('partials-customer/footer.php'); ?>

<!-- Include jQuery for AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            var query = $('#searchInput').val();

            $.ajax({
                url: '', // Since we merged the files, the same page will handle the request
                type: 'POST',
                data: { search: query },
                success: function(response) {
                    // Replace content of the food-menu-container
                    $('#food-menu-container').html($(response).find('#food-menu-container').html());
                },
                error: function() {
                    alert("An error occurred while searching for food.");
                }
            });
        });
    });
</script>

</body>
</html>
