<?php
require 'vendor/autoload.php';

// MongoDB Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'order_mast';

$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Fetch orders from MongoDB
$query = new MongoDB\Driver\Query([]);
$rows = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);

include('partials-customer/menu.php');
?>

<!-- Booking page start -->
<div class="wrapper">
    <div class="main">
        <div>
            <h3><strong>View My Bookings</strong></h3>
            <br>
        </div>
        <table class="tbl-full">
            <tr>
                <th>S.NO</th>
                <th>Tiffin</th>
                <th>Order Id</th>
                <th>Customer Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                
                <th>Time</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Price</th>
                <th>Status</th>
            </tr>

            <?php
            $sn = 1;
            foreach ($rows as $row) {
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo isset($row->tiffin) ? $row->tiffin : 'N/A'; ?></td>
                    <td><?php echo $row->order_id; ?></td>
                    <td><?php echo $row->full_name; ?></td>
                    <td><?php echo $row->contact; ?></td>
                    <td><?php echo $row->email; ?></td>
                   
                    <td><?php echo $row->time; ?></td>
                    <td><?php echo $row->quantity; ?></td>
                    <td><?php echo $row->order_date; ?></td>
                    <td>₹<?php echo $row->total_cost; ?></td>
                    <td><?php echo isset($row->status) ? $row->status : 'Pending'; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
<!-- Booking page End -->

<?php include('partials-customer/footer.php'); ?>
