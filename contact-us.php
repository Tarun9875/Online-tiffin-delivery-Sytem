<?php
// Include MongoDB client library
require 'vendor/autoload.php'; // Ensure MongoDB PHP Library is installed via Composer

// Check if the form is submitted
if (isset($_POST['submit'])) {

    // Connection to MongoDB
    try {
        // Replace with your MongoDB connection string
        $client = new MongoDB\Client("mongodb://localhost:27017");

        // Select the database and collection
        $db = $client->tiffin; // Replace with your database name
        $collection = $db->contactus; // The 'contactus' collection will store contact form submissions

    } catch (MongoDB\Driver\Exception\Exception $e) {
        die("Failed to connect to MongoDB: " . $e->getMessage());
    }

    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address. Please try again.'); window.location.href='contact-us.php';</script>";
        exit();
    }

    // Create a document to insert
    $contactDocument = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'submitted_at' => new MongoDB\BSON\UTCDateTime() // Store the current date and time
    ];

    // Insert the document into the collection
    try {
        $result = $collection->insertOne($contactDocument);
        if ($result->getInsertedCount() == 1) {
            echo "<script>alert('Thank you for your message. We will get back to you shortly!'); window.location.href='contact-us.php';</script>";
        } else {
            echo "<script>alert('Failed to submit your message. Please try again.'); window.location.href='contact-us.php';</script>";
        }
    } catch (MongoDB\Exception\Exception $e) {
        die("Failed to insert data into MongoDB: " . $e->getMessage());
    }
}
?>

<?php include('partials-font/menu.php'); ?>

<!-- Contact Us Section Starts Here -->
<section class="contact-us">
    <div class="container">
        <h2 class="text-center">Contact Us</h2>
        <p class="text-center">We'd love to hear from you! Reach out to us with any questions or feedback.</p>

        <form action="contact-us.php" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="5" placeholder="Your Message" required></textarea>
            </div>
            <input type="submit" name="submit" value="Send Message" class="btn btn-primary">
        </form>
    </div>
</section>
<!-- Contact Us Section Ends Here -->

<?php include('partials-font/footer.php'); ?>
