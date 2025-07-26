<?php
require 'vendor/autoload.php';
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('You must be logged in to place an order!'); window.location.href = 'login.php';</script>";
    exit;
}

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'order_mast';

$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

if (isset($_POST['submit'])) {
    $order_id = htmlspecialchars($_POST['oid']);
    
    $full_name = htmlspecialchars($_POST['full-name']); // From session
    $contact = htmlspecialchars($_POST['contact']);
    $email = htmlspecialchars($_POST['email']);
    $time = htmlspecialchars($_POST['time']);
    $quantity = (int) $_POST['qty'];
    $address = htmlspecialchars($_POST['address']);
    $order_date = htmlspecialchars($_POST['date3']);
    $total_cost = (float) $_POST['price'];

    // Prepare order data
    $orderData = [
        'order_id' => $order_id,
        'full_name' => $full_name,
        'contact' => $contact,
        'email' => $email,
        'time' => $time,
        'quantity' => $quantity,
        'address' => $address,
        'order_date' => $order_date,
        'total_cost' => $total_cost
    ];

    // Insert order into MongoDB
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($orderData);

    try {
        $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);
        echo "<script>alert('Order placed successfully!'); window.location.href = 'foods.php';</script>";
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "<script>alert('Error placing order: " . addslashes($e->getMessage()) . "');</script>";
    }
}

include('partials-customer/menu.php');
?>

<section class="food-search">
    <div class="container">
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>
        <form action="" method="post" class="order">
            <fieldset>
                <legend>Order Page</legend>
                
                <div class="order-label">Order Id</div>
                <input type="text" name="oid" placeholder="E.g. o101" class="input-responsive" required>


                <div class="order-label">Customer  Name</div>
                <input type="text" name="full-name" value="<?php echo $_SESSION['customer_id']; ?>" class="input-responsive" required readonly>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive" required>

                <div class="order-label">Email</div>
                <input type="email" name="email" value="<?php echo isset($_SESSION['customer_email']) ? $_SESSION['customer_email'] : ''; ?>" class="input-responsive" required>

                <div class="order-label">Time</div>
                <input type="time" name="time" class="input-responsive" required>

                <div class="order-label">Quantity</div>
                <input type="number" name="qty" class="input-responsive" value="1" required>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" class="input-responsive" required></textarea>

                <div class="order-label">Order Date</div>
                <input type="date" name="date3" class="input-responsive" required>

                
                <div class="order-label">Price</div>
                <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    <input type="number" name="price" class="input-responsive" placeholder="Price in Rupees" required>
                </div>
                <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
            </fieldset>
        </form>
    </div>
</section>

<?php include('../partials-font/footer.php'); ?>
