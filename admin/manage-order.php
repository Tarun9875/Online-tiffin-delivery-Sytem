<?php 
require 'vendor/autoload.php';

$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'order_mast';

$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

$query = new MongoDB\Driver\Query([]); 
$rows = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);

include('partials/admin-menu.php');
?>

<div class="wraper">
    <div class="main">
        <div>
            <h3><strong>Manage Orders</strong></h3>
            <br>
        </div>
        <br/>
        <table class="tbl-full">
            <tr>
                <th>S.NO</th>
                <th>Order Id</th>
               
                <th>Full Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Order Date</th>
                <th>Price</th>

                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php 
            $sn = 1; 
            foreach ($rows as $row) {
                $status = isset($row->status) ? $row->status : 'Pending';
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $row->order_id; ?></td>
                   
                    <td><?php echo $row->full_name; ?></td>
                    <td><?php echo $row->contact; ?></td>
                    <td><?php echo $row->email; ?></td>
                    <td><?php echo $row->order_date; ?></td>
                    <td>₹<?php echo $row->total_cost; ?></td>
                   
                    <td><?php echo $status; ?></td>
                    <td>
                        <form method="post" action="update-order-status.php" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $row->order_id; ?>" />
                            <select name="status">
                                <option value="Confirmed" <?php echo $status === 'Confirmed' ? 'selected' : ''; ?>>Confirm</option>
                                <option value="Cancelled" <?php echo $status === 'Cancelled' ? 'selected' : ''; ?>>Cancel</option>
                                <option value="Pending" <?php echo $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            </select>
                            <input type="submit" value="Update" class="btn-primary"/>
                        </form>
                        <a href="delete-order.php?id=<?php echo $row->order_id; ?>" class="btn-denger" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                    </td>
                </tr>
                <?php
            }
            ?>

        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
