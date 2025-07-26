<?php
require 'vendor/autoload.php';

// Configuration
$mongoDbHost = 'localhost';
$mongoDbPort = 27017;
$mongoDbName = 'tiffin';
$mongoDbCollection = 'admin_login';

// Connect to MongoDB
$mongoClient = new MongoDB\Driver\Manager("mongodb://$mongoDbHost:$mongoDbPort/");

// Handle form submission for updating admin details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $adminId = (int) $_POST['a_id'];
    $email = $_POST['email'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['password'];

    // Filter to find the specific admin by a_id
    $filter = ['a_id' => $adminId];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);
    $admin = $cursor->toArray();

    if (count($admin) > 0) {
        $storedPasswordHash = $admin[0]->password;

        // Verify the old password
        if (password_verify($oldPassword, $storedPasswordHash)) {
            // Prepare the update data
            $updateData = ['email' => $email];

            // If new password field is not empty, update the password
            if (!empty($newPassword)) {
                $updateData['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
            }

            // Prepare the bulk write operation
            $bulkWrite = new MongoDB\Driver\BulkWrite;
            $bulkWrite->update($filter, ['$set' => $updateData]);

            // Execute the update
            $result = $mongoClient->executeBulkWrite("$mongoDbName.$mongoDbCollection", $bulkWrite);

            if ($result->getModifiedCount() > 0) {
                echo "<script>alert('Admin updated successfully!'); window.location.href = 'manage_admin.php';</script>";
            } else {
                echo "<script>alert('No changes were made.'); window.location.href = 'manage_admin.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect old password.'); window.location.href = 'update-admin.php';</script>";
        }
    } else {
        echo "<script>alert('Admin ID not found!'); window.location.href = 'update-admin.php';</script>";
    }
}

// Handle AJAX request to fetch admin details
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['submit'])) {
    $adminId = (int) $_POST['a_id'];

    // Filter to find the specific admin by a_id
    $filter = ['a_id' => $adminId];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $mongoClient->executeQuery("$mongoDbName.$mongoDbCollection", $query);
    $admin = $cursor->toArray();

    if (count($admin) > 0) {
        $adminData = $admin[0];
        $response = [
            'success' => true,
            'email' => $adminData->email,
        ];
    } else {
        $response = ['success' => false];
    }

    echo json_encode($response);
    exit;
}
?>

<?php include('partials/admin-menu.php'); ?>

<div class="main-content">
    <div class="wraper">
        <h3>Update Admin</h3>
        <br/><br/>

        <form action="" method="post" id="updateAdminForm">
            <table class="tbl-30">
                <tr>
                    <td>Admin ID:</td>
                    <td>
                        <input type="text" name="a_id" id="a_id" placeholder="Enter Admin ID" required onblur="fetchAdminDetails()">
                    </td>
                </tr>
                <tr>
                    <td>Email ID:</td>
                    <td>
                        <input type="email" name="email" id="email" placeholder="Enter New Email ID" required>
                    </td>
                </tr>
                <tr>
                    <td>Old Password:</td>
                    <td>
                        <input type="password" name="old_password" placeholder="Enter Old Password" required>
                    </td>
                </tr>
                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="password" placeholder="Enter New Password (optional)">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Update Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<script>
// JavaScript function to fetch admin details based on Admin ID
function fetchAdminDetails() {
    var adminId = document.getElementById("a_id").value;
    if (adminId) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // Sends request to the same page
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById("email").value = response.email;
                } else {
                    alert("Admin ID not found!");
                }
            }
        };
        xhr.send("a_id=" + adminId);
    }
}
</script>
